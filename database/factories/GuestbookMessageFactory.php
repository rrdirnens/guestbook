<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\GuestbookMessage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GuestbookMessage>
 */
class GuestbookMessageFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GuestbookMessage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail,
            'message' => $this->faker->text(200),
            'ip_address' => $this->faker->ipv4,
        ];
    }
}
