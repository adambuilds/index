<?php

namespace Database\Factories;

use App\Models\SubjectLink;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubjectLinkFactory extends Factory
{
    protected $model = SubjectLink::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->words(2, true),
            'url' => $this->faker->url,
        ];
    }
}
