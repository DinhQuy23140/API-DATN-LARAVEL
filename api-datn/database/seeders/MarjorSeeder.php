<?php

namespace Database\Seeders;

use App\Models\Marjor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MarjorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
                $majors = [
            ['code' => 'KHMT', 'name' => 'Khoa học máy tính', 'description' => 'Ngành Khoa học máy tính'],
            ['code' => 'KTPM', 'name' => 'Kỹ thuật phần mềm', 'description' => 'Ngành Kỹ thuật phần mềm'],
            ['code' => 'HTTT', 'name' => 'Hệ thống thông tin', 'description' => 'Ngành Hệ thống thông tin'],
            ['code' => 'MMTT', 'name' => 'Mạng máy tính & Truyền thông', 'description' => 'Ngành Mạng máy tính & Truyền thông'],
            ['code' => 'TTNT', 'name' => 'Trí tuệ nhân tạo', 'description' => 'Ngành Trí tuệ nhân tạo'],
            ['code' => 'ATTT', 'name' => 'An toàn thông tin', 'description' => 'Ngành An toàn thông tin'],
            ['code' => 'TTĐT', 'name' => 'Tin học ứng dụng', 'description' => 'Ngành Tin học ứng dụng'],
            ['code' => 'CNTT', 'name' => 'Công nghệ thông tin', 'description' => 'Ngành Công nghệ thông tin'],
        ];

        foreach ($majors as $major) {
            Marjor::create([
                'code' => $major['code'],
                'name' => $major['name'],
                'description' => $major['description'],
                'faculty_id' => 1, // Giá trị cố định
            ]);
        }
    }
}
