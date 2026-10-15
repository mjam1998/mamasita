<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProvinceCitySeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $provinceMap = [];

        $provincesFile = database_path('data/provinces.json');
        if (File::exists($provincesFile)) {
            $provinces = json_decode(File::get($provincesFile), true);

            foreach ($provinces as $item) {
                $newId = DB::table('provinces')->insertGetId([
                    'name' => $item['name'],
                    'is_active' => in_array($item['is_active'] ?? 't', ['t', 'true', 1, true], true),
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                $provinceMap[$item['id']] = $newId;
            }
        }


        $citiesFile = database_path('data/cities.json');
        if (File::exists($citiesFile)) {
            $cities = json_decode(File::get($citiesFile), true);
            $cleanCities = [];

            foreach ($cities as $city) {
                $oldProvinceId = $city['province_id'] ?? null;

                if ($oldProvinceId && isset($provinceMap[$oldProvinceId])) {
                    $cleanCities[] = [
                        'province_id' => $provinceMap[$oldProvinceId],
                        'name' => $city['name'],
                        'is_active' => in_array($city['is_active'] ?? 't', ['t', 'true', 1, true], true),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            foreach (array_chunk($cleanCities, 500) as $chunk) {
                DB::table('cities')->insert($chunk);
            }
        }
    }
}
