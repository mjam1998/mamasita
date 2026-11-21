<?php

namespace App\Http\Controllers\Admin;

use App\Enums\VideoType;
use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminVideoStreamController extends Controller
{
    public function stream(Request $request, Video $video): StreamedResponse
    {

        abort_if($video->type !== VideoType::Subscription, 404);

        $path = 'videos/subscriptions/'.$video->file_path;

        abort_unless(Storage::disk('local')->exists($path), 404);

        return Storage::disk('local')->response($path);
    }
}
