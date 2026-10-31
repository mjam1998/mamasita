<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TransactionDirection;
use App\Enums\TransactionType;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\ValidationException;

class AdminWalletController extends Controller
{
    public function showUserWallet(User $user)
    {
        $wallet = Wallet::firstOrCreate(['user_id' => $user->id]);

        return view('admin.wallets.show', [
            'user' => $user,
            'wallet' => $wallet,
        ]);
    }

    public function showTransaction(Transaction $transaction)
    {
        $transaction->load(['wallet.user', 'referenceable']);

        return view('admin.wallets.transactions.show', [
            'transaction' => $transaction,
            'user' => $transaction->wallet->user,
        ]);
    }

    public function createTransaction(User $user)
    {
        $wallet = Wallet::firstOrCreate(['user_id' => $user->id]);

        return view('admin.wallets.transactions.create', [
            'user' => $user,
            'wallet' => $wallet,
            'types' => TransactionType::cases(),
            'directions' => TransactionDirection::cases(),
        ]);
    }

    public function storeTransaction(Request $request, User $user)
    {
        $validated = $request->validate([
            'action' => ['required', Rule::in(['block', 'unblock'])],
            'amount' => ['required', 'integer', 'min:1000'],
            'description' => ['required', 'string', 'max:2000'],
        ], [
            'action.required' => 'انتخاب نوع عملیات مسدودی یا آزادسازی الزامی است.',
            'action.in' => 'نوع عملیات انتخاب شده نامعتبر است.',
            'amount.required' => 'وارد کردن مبلغ الزامی است.',
            'amount.integer' => 'مبلغ باید به صورت عددی وارد شود.',
            'amount.min' => 'حداقل مبلغ باید ۱,۰۰۰ تومان باشد.',
            'description.required' => 'ثبت توضیحات یا علت عملیات الزامی است.',
            'description.string' => 'فرمت توضیحات نامعتبر است.',
            'description.max' => 'توضیحات نباید بیش از ۲۰۰۰ کاراکتر باشد.',
        ]);

        $amount = (int) $validated['amount'];
        $action = $validated['action'];

        try {
            DB::transaction(function () use ($user, $amount, $action, $validated) {
                // قفل کردن رکورد کیف پول در سطح دیتابیس برای جلوگیری از تراکنش‌های هم‌روند
                $wallet = Wallet::where('user_id', $user->id)->lockForUpdate()->firstOrCreate([
                    'user_id' => $user->id,
                ]);

                if ($action === 'block') {
                    // بررسی و کسر از آزاد، افزودن به مسدودی
                    $wallet->blockFunds($amount);

                    $direction = TransactionDirection::Debit;
                    $type = TransactionType::Block; // یا نوع متناظر در Enum شما
                } else {
                    // بررسی و کسر از مسدودی، افزودن به آزاد
                    $wallet->unblockFunds($amount);

                    $direction = TransactionDirection::Credit;
                    $type = TransactionType::Unblock; // یا نوع متناظر در Enum شما
                }

                $wallet->refresh();
                // تولید کد پیگیری منحصربه‌فرد ۶ رقمی با حروف بزرگ انگلیسی
                do {
                    $trackNumber = strtoupper(Str::random(6));
                } while (Transaction::where('track_number', $trackNumber)->exists());

                // ثبت تاریخچه تراکنش
                Transaction::create([
                    'wallet_id' => $wallet->id,
                    'track_number' => $trackNumber,
                    'direction' => $direction,
                    'type' => $type,
                    'amount' => $amount,
                    'available_balance_after' => $wallet->available_balance,
                    'total_balance_after' => $wallet->total_balance,
                    'blocked_balance_after' => $wallet->blocked_balance,
                    'description' => $validated['description'],
                    'referenceable_id' => auth()->id(),
                    'referenceable_type' => User::class,
                ]);
            });
        } catch (Exception $e) {
            throw ValidationException::withMessages([
                'amount' => $e->getMessage(),
            ]);

        }

        return redirect()
            ->route('admin.wallets.user', $user->id)
            ->with('success', 'عملیات مالی با موفقیت ثبت شد.');
    }
}
