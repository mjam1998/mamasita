<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminCourseController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $q = trim($request->input('q', ''));

        if (mb_strlen($q) < 2) {
            return response()->json([]);
        }

        $courses = Course::query()
            ->where(function ($query) use ($q) {
                $query->where('slug', 'like', "%{$q}%")
                    ->orWhere('name', 'like', "%{$q}%");
            })
            ->select(['id', 'name', 'slug'])
            ->limit(15)
            ->get()
            ->map(fn ($u) => [
                'value' => $u->id,
                'label' => "{$u->name} ({$u->slug})",
            ]);

        return response()->json($courses);
    }
}
