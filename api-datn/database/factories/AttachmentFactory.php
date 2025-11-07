<?php

namespace Database\Factories;
use App\Models\Attachment;
use App\Models\ProgressLog;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attachment>
 */
class AttachmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'file_name' => $this->faker->words(2, true) . '.' . $this->faker->fileExtension(),
            'file_url' => $this->faker->url(),
            'file_type' => $this->faker->randomElement(['pdf', 'image', 'doc', 'xlsx', 'ppt']),
            'upload_time' => $this->faker->unixTime(),
        ];
    }
}
