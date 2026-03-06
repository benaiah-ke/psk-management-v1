<?php

namespace Database\Seeders;

use App\Models\CpdCategory;
use Illuminate\Database\Seeder;

class CpdCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Conference/Seminar Attendance', 'description' => 'Attendance at professional conferences, seminars, and symposia.', 'max_points_per_year' => 20],
            ['name' => 'Workshop/Training', 'description' => 'Participation in hands-on workshops and training programs.', 'max_points_per_year' => 15],
            ['name' => 'Webinar/Online Course', 'description' => 'Completion of accredited online courses and webinars.', 'max_points_per_year' => 10],
            ['name' => 'Publication', 'description' => 'Authoring or co-authoring peer-reviewed articles or publications.', 'max_points_per_year' => 15],
            ['name' => 'Presentation/Lecture', 'description' => 'Delivering presentations or lectures at professional events.', 'max_points_per_year' => 15],
            ['name' => 'Self-Study', 'description' => 'Documented self-directed learning activities.', 'max_points_per_year' => 10],
            ['name' => 'Mentorship', 'description' => 'Serving as a mentor to interns or students.', 'max_points_per_year' => 10],
            ['name' => 'Community Service', 'description' => 'Pharmacy-related community outreach and health campaigns.', 'max_points_per_year' => 10],
            ['name' => 'Committee Service', 'description' => 'Active participation in PSK committees or working groups.', 'max_points_per_year' => 10],
            ['name' => 'Research', 'description' => 'Involvement in pharmacy-related research projects.', 'max_points_per_year' => 15],
        ];

        foreach ($categories as $category) {
            CpdCategory::create(array_merge($category, ['is_active' => true]));
        }
    }
}
