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
        // $this->call([
        //     UsersSeeder::class,
        // ]);
        $this->call([
            FacultiesSeeder::class,
        ]);

        $this->call([
            MarjorSeeder::class,
        ]);

        $this->call([
            DepartmentSeeder::class,
        ]);
        
        $this->call([
            TeacherSeeder::class,
        ]);

        $this->call([
            StudentSeeder::class,
        ]);

        $this->call([
            AcademyYearSeeder::class,
        ]);

        $this->call([
            ProjectTermsSeeder::class,
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

        $this->call([
            AssignmentSupervisorSeeder::class,
        ]);

        $this->call([
            StageTimelineSeeder::class,
        ]);

        $this->call([
            CohortSeeder::class,
        ]);

        $this->call([
            ClassRoomSeeder::class,
        ]);

        $this->call([
            ReportFilesSeeder::class,
        ]);

        $this->call([
            CouncilSeeder::class,
        ]);

        $this->call([
            CouncilMembersSeeder::class,
        ]);

        $this->call([
            CouncilProjectDefencesSeeder::class,
        ]);
    }
}
