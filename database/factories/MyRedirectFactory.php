<?php

namespace Database\Factories;

use App\Models\MyRedirect;
use Illuminate\Database\Eloquent\Factories\Factory;

class MyRedirectFactory extends Factory
{
    protected $model = MyRedirect::class;

    public function definition()
    {
        return [
            'url' => $this->faker->url,
            'active' => $this->faker->boolean,
        ];
    }

    public function inactive()
    {
        return $this->state(function (array $attributes) {
            return [
                'active' => false,
            ];
        });
    }
}
