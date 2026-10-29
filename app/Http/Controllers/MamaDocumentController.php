<?php

namespace App\Http\Controllers;

use App\Models\MamaInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MamaDocumentController extends Controller
{
    public function viewDocument(Request $request, MamaInformation $mamaInformation, string $type)
    {
//        // بررسی دسترسی: فقط خود صاحب حساب یا کاربر با نقش admin / super-admin
//        $user = $request->user();
//        $isOwner = ($user->id === $mamaInformation->user_id);
//        $isAdmin = $user->hasAnyRole(['admin', 'super-admin']);
//
//        if (! $isOwner && ! $isAdmin) {
//            abort(403, 'شما اجازه دسترسی به این مدرک را ندارید.');
//        }
//
//        // بررسی نوع فیلد درخواستی
//        if (! in_array($type, ['national_card_file', 'education_file'])) {
//            abort(404);
//        }

        $fileName = $mamaInformation->$type;

//        if (! $fileName || ! Storage::disk('local')->exists('mamas/documents/'.$fileName)) {
//            abort(404, 'فایل مورد نظر یافت نشد.');
//        }

        // باز کردن و نمایش فایل به صورت امن (Inline در مرورگر)
        return Storage::disk('local')->response('mamas/documents/'.$fileName);
    }
}
