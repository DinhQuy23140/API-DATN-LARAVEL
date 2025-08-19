<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UsersSeeder::class,
        ]);

        $this->call([
            StudentSeeder::class,
        ]);

        $this->call([
            TeacherSeeder::class,
        ]);

        $this->call([
            SupervisorSeeder::class,
        ]);

        $this->call([
            ProjectSeeder::class,
        ]);

        $this->call([
            ProgressLogSeeder::class,
        ]);

        $this->call([
            AssignmentSeeder::class,
        ]);
    }
}
