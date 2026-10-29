<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Province;
use Illuminate\Http\JsonResponse;

class LocationController extends Controller
{
    /**
     * دریافت لیست تمام استان‌ها
     */
    public function provinces(): JsonResponse
    {
        $provinces = Province::select('id', 'name')->orderBy('name')->get();

        return response()->json([
            'status' => true,
            'data' => $provinces,
        ]);
    }

    /**
     * دریافت شهرهای یک استان خاص
     */
    public function getCitiesByProvince(Province $province): JsonResponse
    {
        $cities = City::where('province_id', $province->id)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $cities,
        ]);
    }
}
