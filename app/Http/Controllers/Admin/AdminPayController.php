<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AdminPayStatus;
use App\Enums\AdminPayType;
use App\Enums\PlatformRevenueDirection;
use App\Enums\PlatformRevenueType;
use App\Enums\TransactionDirection;
use App\Enums\TransactionType;
use App\Http\Controllers\Controller;
use App\Models\AdminPay;
use App\Models\PlatformRevenue;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserPay;
use App\Models\Wallet;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Morilog\Jalali\Jalalian;

class AdminPayController extends Controller
{
    public function index()
    {
        return view('admin.pay.index');
    }

    public function create()
    {
        return view('admin.pay.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:1,2',
            'user_id' => 'required|exists:users,id',
            'user_pay_id' => 'required_if:type,2|nullable|exists:user_pays,id',
            'amount' => 'required|numeric|min:1000',
            'bank_name' => 'required|string|max:100',
            'iban_number' => 'required|string|max:24',
            'card_number' => 'required|string|max:16',
            'paid_at' => 'required|date',
        ], [
            'user_id.required' => 'انتخاب کاربر/ماما الزامی است.',
            'user_pay_id.required_if' => 'در حالت بازگشت وجه، انتخاب تراکنش کاربر الزامی است.',
            'paid_at.required' => 'ثبت تاریخ پرداخت الزامی است.',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $trackNumber = strtoupper(Str::random(6));

                // 1. ثبت رکورد پرداخت ادمین (وضعیت پیش‌فرض پرداخت‌شده: status = 1)
                $adminPay = AdminPay::create([
                    'user_id' => $validated['user_id'],
                    'user_pay_id' => $validated['type'] == AdminPayType::Refund->value ? $validated['user_pay_id'] : null,
                    'track_number' => $trackNumber,
                    'amount' => $validated['amount'],
                    'type' => $validated['type'],
                    'status' => AdminPayStatus::Paid, // پرداخت شده
                    'bank_name' => $validated['bank_name'],
                    'iban_number' => $validated['iban_number'],
                    'card_number' => $validated['card_number'],
                    'paid_at' => Jalalian::fromFormat('Y/n/j', $validated['paid_at'])->toCarbon(),
                ]);

                // حالت اول: سهم ماما (کسر از کیف پول)
                if ($validated['type'] == AdminPayType::MamaShare->value) {
                    $wallet = Wallet::where('user_id', $validated['user_id'])->lockForUpdate()->firstOrFail();

                    if ($wallet->available_balance < $validated['amount']) {
                        throw new Exception('موجودی آزاد کیف پول ماما کمتر از مبلغ پرداختی است!');
                    }

                    // کسر از موجودی
                    $wallet->withdraw($validated['amount']);
                    $wallet->refresh();

                    // ثبت تراکنش کیف پول
                    Transaction::create([
                        'wallet_id' => $wallet->id,
                        'amount' => $validated['amount'],
                        'direction' => TransactionDirection::Debit, // بدهکار / Debit
                        'type' => TransactionType::Withdraw, // برداشت / Withdraw
                        'total_balance_after' => $wallet->total_balance,
                        'available_balance_after' => $wallet->available_balance,
                        'blocked_balance_after' => $wallet->blocked_balance,
                        'referenceable_type' => AdminPay::class,
                        'referenceable_id' => $adminPay->id,
                        'description' => 'تسویه سهم ماما به شماره تراکنش: '.$adminPay->track_number,
                    ]);
                }

                // حالت دوم: بازگشت وجه (کسر از درآمد پلتفرم)
                if ($validated['type'] == AdminPayType::Refund->value) {

                    $userPay = UserPay::find($validated['user_pay_id']);

                    if ($validated['amount'] > $userPay->amount) {
                        throw new Exception('مبلغ پرداختی بازگشتی نمیتواند بیشتر از پرداختی کاربر باشد.');
                    }

                    $sumUserPay = AdminPay::query()->where('user_pay_id', $validated['user_pay_id'])
                        ->where('type', AdminPayType::Refund->value)
                        ->where('status', AdminPayStatus::Paid->value)
                        ->sum('amount');

                    if ($userPay->amount < $validated['amount'] + $sumUserPay) {
                        throw new Exception('برای این تراکنش کاربر قبلا تراکنش های بازگشتی ثبت شده و مبلغ وارد شده به همراه مبالغ پرداختی قبلی به کاربر بیشتر از پرداختی خود کاربر میباشد.');
                    }

                    PlatformRevenue::create([
                        'type' => PlatformRevenueType::Refund, // بازگشت وجه / Refund
                        'direction' => PlatformRevenueDirection::Debit, // کسر / Debit
                        'amount' => $validated['amount'],
                        'description' => 'بازگشت وجه تراکنش کاربر با کد رهگیری: '.$adminPay->track_number,
                        'referenceable_type' => AdminPay::class,
                        'referenceable_id' => $adminPay->id,
                    ]);
                }
            });

            return redirect()->route('admin.pay.index')->with('success', 'پرداخت با موفقیت ثبت شد و اثرات مالی اعمال گردید.');

        } catch (Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(AdminPay $adminPay)
    {
        $adminPay->load(['user', 'userPay']);

        return view('admin.pay.show', compact('adminPay'));
    }

    // ایجکس جستجوی ماماها به همراه موجودی آزاد کیف‌پول
    public function searchMamas(Request $request): JsonResponse
    {
        $q = trim($request->input('q', ''));

        if (mb_strlen($q) < 2) {
            return response()->json([]);
        }

        $mamas = User::role('mama')
            ->where(function ($query) use ($q) {
                $query->where('mobile', 'like', "%{$q}%")
                    ->orWhere('first_name', 'like', "%{$q}%")
                    ->orWhere('last_name', 'like', "%{$q}%")
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$q}%"]);
            })
            ->with('wallet')
            ->limit(15)
            ->get();

        $data = $mamas->map(function ($mama) {
            $fullName = trim("{$mama->first_name} {$mama->last_name}");
            $balance = $mama->wallet ? (int) $mama->wallet->available_balance : 0;

            return [
                'value' => (string) $mama->id,
                'label' => "{$fullName} ({$mama->mobile}) - موجودی آزاد: ".number_format($balance).' تومان',
                'customProperties' => [
                    'user_id' => $mama->id,
                    'balance' => $balance,
                ],
            ];
        });

        return response()->json($data);
    }

    public function searchUserPays(Request $request): JsonResponse
    {
        $q = trim($request->input('q', ''));

        if (mb_strlen($q) < 2) {
            return response()->json([]);
        }

        $pays = UserPay::where('track_number', 'like', "%{$q}%")
            ->where('is_paid', true)
            ->with('user')
            ->limit(15)
            ->get();

        $data = $pays->map(function ($p) {
            $userName = trim(($p->user?->first_name ?? '').' '.($p->user?->last_name ?? '').' '.$p->user->mobile);

            return [
                'value' => (string) $p->id,
                'label' => "کد: {$p->track_number} | کاربر: {$userName} | مبلغ: ".number_format($p->amount).' تومان',
                'customProperties' => [
                    'user_id' => $p->user_id,
                    'amount' => $p->amount,
                ],
            ];
        });

        return response()->json($data);
    }

    public function editStatus(AdminPay $adminPay)
    {
        // پرداخت‌های تایید و پرداخت‌شده غیرقابل تغییر وضعیت هستند
        if ($adminPay->status !== AdminPayStatus::Waiting) {
            return redirect()->route('admin.pay.show', $adminPay)
                ->with('error', 'این پرداخت قبلاً تعیین وضعیت شده و وضعیت آن غیرقابل تغییر است.');
        }

        return view('admin.pay.edit-status', [
            'adminPay' => $adminPay,
            'statuses' => AdminPayStatus::cases(),
        ]);
    }

    public function updateStatus(Request $request, AdminPay $adminPay)
    {
        if ($adminPay->status !== AdminPayStatus::Waiting) {
            return back()->with('error', 'این پرداخت قبلاً تعیین وضعیت شده و وضعیت آن غیرقابل تغییر است.');
        }

        $validated = $request->validate([
            'status' => ['required', 'integer', 'in:'.implode(',', array_column(AdminPayStatus::cases(), 'value'))],
            'paid_at' => ['required_if:status,'.AdminPayStatus::Paid->value, 'nullable', 'string'],
        ], [
            'status.required' => 'انتخاب وضعیت الزامی است.',
            'paid_at.required_if' => 'در صورت انتخاب وضعیت پرداخت شده، وارد کردن تاریخ پرداخت الزامی است.',
        ]);

        $newStatus = AdminPayStatus::from((int) $validated['status']);

        try {
            DB::transaction(function () use ($adminPay, $newStatus, $validated) {
                // قفل کردن رکورد پرداختی جهت جلوگیری از ریس کاندیشن
                $adminPay = AdminPay::where('id', $adminPay->id)->lockForUpdate()->first();

                if ($adminPay->status !== AdminPayStatus::Waiting) {
                    throw new Exception('این پرداخت قبلاً تعیین وضعیت شده و وضعیت آن غیرقابل تغییر است.');
                }

                // اگر تغییری در وضعیت ایجاد نشده، کاری انجام ندهد
                if ($adminPay->status === $newStatus) {
                    return;
                }

                // ==========================================
                // ۱. وضعیت تغییر به کنسل (برگشت وجه به کیف پول)
                // ==========================================
                if ($newStatus === AdminPayStatus::Canceled) {
                    if ($adminPay->type === AdminPayType::MamaShare) {
                        $wallet = Wallet::where('user_id', $adminPay->user_id)->lockForUpdate()->first();

                        if (! $wallet) {
                            throw new Exception('کیف پول کاربر مورد نظر یافت نشد.');
                        }

                        // برگشت مبلغ به کل موجودی و موجودی آزاد
                        $wallet->deposit($adminPay->amount);
                        $wallet->refresh();

                        // ثبت سند تراکنش واریزی / بازگشت وجه
                        Transaction::create([
                            'wallet_id' => $wallet->id,
                            'amount' => $adminPay->amount,
                            'direction' => TransactionDirection::Credit,
                            'type' => TransactionType::Deposit,
                            'total_balance_after' => $wallet->total_balance,
                            'available_balance_after' => $wallet->available_balance,
                            'blocked_balance_after' => $wallet->blocked_balance,
                            'referenceable_type' => AdminPay::class,
                            'referenceable_id' => $adminPay->id,
                            'description' => 'بازگشت وجه سهم ماما به کیف پول به دلیل عدم تایید/لغو پرداخت با پیگیری: '.$adminPay->track_number,
                        ]);
                    }

                    $adminPay->update([
                        'status' => AdminPayStatus::Canceled,
                        'paid_at' => null,
                    ]);
                }

                // ==========================================
                // ۲. وضعیت تغییر به پرداخت شده (نهایی کردن تسویه)
                // ==========================================
                elseif ($newStatus === AdminPayStatus::Paid) {

                    $adminPay->update([
                        'status' => AdminPayStatus::Paid,
                        'paid_at' => Jalalian::fromFormat('Y/n/j', $validated['paid_at'])->toCarbon(),
                    ]);
                }

                // ==========================================
                // ۳. برگشت به در انتظار پرداخت (Waiting)
                // ==========================================
                elseif ($newStatus === AdminPayStatus::Waiting) {
                    $adminPay->update([
                        'status' => AdminPayStatus::Waiting,
                        'paid_at' => null,
                    ]);
                }
            });

            return redirect()->route('admin.pay.index')->with('success', 'وضعیت پرداخت با موفقیت بروزرسانی شد.');

        } catch (Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}
