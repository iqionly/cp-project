<?php

namespace Database\Factories\Contribution;

use App\Models\Contribution\Invoice;
use App\Models\MasterData\Member;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Invoice>
 */
#[UseModel(Invoice::class)]
class InvoiceFactory extends Factory
{
    private static int $sequence = 1;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $periodStart = Carbon::create(
            $this->faker->numberBetween(2025, 2026),
            $this->faker->numberBetween(1, 12),
            1
        );

        $seq = str_pad(self::$sequence++, 5, '0', STR_PAD_LEFT);

        return [
            'member_id' => Member::factory(),
            'invoice_number' => 'INV-'.$periodStart->format('Ym').'-'.$seq,
            'period_start' => $periodStart->toDateString(),
            'issued_at' => $periodStart->copy()->addDays(3)->toDateString(),
            'due_at' => $periodStart->copy()->addMonth()->toDateString(),
            'status' => 'issued',
            'notes' => $this->faker->optional(0.4)->sentence(),
        ];
    }

    /** State: invoice for a specific member + period, avoiding the unique constraint. */
    public function forMemberPeriod(Member $member, Carbon $period): static
    {
        $seq = str_pad(self::$sequence++, 5, '0', STR_PAD_LEFT);

        return $this->state([
            'member_id' => $member->id,
            'invoice_number' => 'INV-'.$period->format('Ym').'-'.$seq,
            'period_start' => $period->toDateString(),
            'issued_at' => $period->copy()->addDays(3)->toDateString(),
            'due_at' => $period->copy()->addMonth()->toDateString(),
        ]);
    }
}
