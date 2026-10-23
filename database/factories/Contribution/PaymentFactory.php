<?php

namespace Database\Factories\Contribution;

use App\Models\Contribution\Payment;
use App\Models\MasterData\Member;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @extends Factory<Payment>
 */
#[UseModel(Payment::class)]
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $paidAt = Carbon::instance($this->faker->dateTimeBetween('-2 years', 'now'));

        return [
            'member_id' => Member::factory(),
            'payment_number' => 'PAY-'.$paidAt->format('Ymd').'-'.Str::upper(Str::random(8)),
            'paid_at' => $paidAt,
            'amount' => $this->faker->randomElement([25000, 50000, 75000, 100000]),
            'method' => $this->faker->randomElement(['transfer', 'cash', 'qris']),
            'reference_number' => $this->faker->optional(0.7)->numerify('REF-########'),
            'status' => 'APPROVED',
            'notes' => $this->faker->optional(0.3)->sentence(),
            'evidence_path' => null,
        ];
    }

    /** State: payment is awaiting review. */
    public function pending(): static
    {
        return $this->state(['status' => 'PENDING']);
    }

    /** State: payment has been reviewed. */
    public function reviewed(): static
    {
        return $this->state(['status' => 'REVIEWED']);
    }

    /** State: payment was rejected. */
    public function rejected(): static
    {
        return $this->state(['status' => 'REJECTED']);
    }
}
