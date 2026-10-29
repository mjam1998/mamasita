<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;

class TinyMceImageController extends Controller
{
    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:4048',
        ]);

        if ($request->hasFile('file')) {

            $filename = time().'.webp';

            $manager = new ImageManager(new Driver);
            $image = $manager->decode($request->file('file'));
            $encoded = $image->encode(new WebpEncoder(quality: 80));
            Storage::disk('public')->put('images/'.$filename, (string) $encoded);

            return response()->json([
                'location' => asset('images/'.$filename),
            ]);
        }

        return response()->json(['error' => 'خطا در آپلود تصویر'], 400);
    }
}
