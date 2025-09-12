<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $departments = [
            ['code' => 'BM001', 'name' => 'Khoa học máy tính', 'description' => 'Bộ môn Khoa học máy tính'],
            ['code' => 'BM002', 'name' => 'Kỹ thuật phần mềm', 'description' => 'Bộ môn Kỹ thuật phần mềm'],
            ['code' => 'BM003', 'name' => 'Hệ thống thông tin', 'description' => 'Bộ môn Hệ thống thông tin'],
            ['code' => 'BM004', 'name' => 'Mạng máy tính & Truyền thông', 'description' => 'Bộ môn Mạng máy tính & Truyền thông'],
            ['code' => 'BM005', 'name' => 'Trí tuệ nhân tạo', 'description' => 'Bộ môn Trí tuệ nhân tạo'],
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }
    }
}
