<?php

namespace Database\Factories;

use App\Models\Stop;
use Illuminate\Database\Eloquent\Factories\Factory;

enum TimePrefix: string
{
    case MORE = 'more than';
    case LESS = 'less';
}

/**
 * @extends Factory<Stop>
 */
class StopFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startMinutes = fake()->numberBetween(0, (24 * 60) - 1);
        $totalMinutes = fake()->numberBetween(15, 12 * 60);

        return [
            'start_time' => $this->formatMinutesAsTime($startMinutes),
            'end_time' => $this->formatMinutesAsTime(($startMinutes + $totalMinutes) % (24 * 60)),
            'total_time' => $this->formatMinutesAsTime($totalMinutes),
            'estimated_time' => $this->assignEstimatedTime($totalMinutes),
            'deliveries_count' => fake()->numberBetween(0, 20),
            'pickups_count' => fake()->numberBetween(0, 20),
        ];
    }

    protected function formatMinutesAsTime(int $minutes): string
    {
        return sprintf('%02d:%02d', intdiv($minutes, 60), $minutes % 60);
    }

    private static function assignMoreOrLessPrefix(): TimePrefix
    {
        return rand(0, 1) === 0 ? TimePrefix::MORE : TimePrefix::LESS;
    }

    public static function assignEstimatedTime(int $totalMinutes): string
    {
        $prefix = self::assignMoreOrLessPrefix();

        $hours = intdiv($totalMinutes, 60);
        $remainder = $totalMinutes % 60;

        if ($hours > 0 && $remainder > 0 && $prefix === TimePrefix::MORE) {
            return "+$hours";
        } elseif ($hours > 0 && $remainder > 0 && $prefix === TimePrefix::LESS) {
            return '- '.($hours + 1);
        } elseif ($hours > 0 && $remainder === 0) {
            return $hours;
        } else {
            return '-1';
        }
    }

    public function invalidateEstimatedTime(): static
    {
        return $this->state(fn (): array => [
            'estimated_time' => null,
        ]);
    }
}
