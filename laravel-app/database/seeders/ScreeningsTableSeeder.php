<?php
declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Movie;
use App\Models\Screening;
use Illuminate\Database\Seeder;

class ScreeningsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Let's truncate our existing records to start from scratch.
        Screening::query()->truncate();

        $faker = \Faker\Factory::create();
        $movies = Movie::query()->pluck('id')->toArray();
        // And now, let's create a few articles in our database:
        for ($i = 0; $i < 50; $i++) {
            Screening::query()->create([
                'datetime' => $faker->dateTimeInInterval('0 days', '90 days'),
                'available_seats' => mt_rand(0, 100),
                'movie_id' => $movies[array_rand($movies)],
            ]);
        }
    }
}
