<?php

namespace Database\Seeders;

use App\Models\HomeSection;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HomeSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'image' => 'mom.png',
            'image_alt' => 'ماماسیتا',
            'image_title' => 'ماماسیتا',
            'meta_description' => 'ماماسیتا',
            'meta_title' => 'ماماسیتا',
            'h1_text' => 'آماده‌ی یک زایمان آگاهانه باش',
            'h2_text' => 'با همراهی ماماهای متخصص و دوره‌های آموزشی معتبر',
            'h3_text' => 'در ماماسیتا می‌توانید دوره‌های آموزشی زایمان، مشاوره و ویزیت آنلاین ماماها و اشتراک مامای همراه را به‌راحتی تهیه کنید.',
            'btn_text' => 'شروع کنید',
            'btn_link' => '#',
        ];

        $first = HomeSection::orderBy('id')->first();

        if ($first) {
            HomeSection::where('id', '!=', $first->id)->delete();
        } else {
            HomeSection::create($data);
        }
    }
}
