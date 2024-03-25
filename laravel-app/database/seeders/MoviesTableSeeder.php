<?php
declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Movie;
use Illuminate\Database\Seeder;

class MoviesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Movie::query()->delete();

        $faker = \Faker\Factory::create();

        $ageRestr = [6, 12, 18];
        // And now, let's create a few articles in our database:
        for ($i = 0; $i < 25; $i++) {
            Movie::query()->create([
                'title' => $faker->sentence(3),
                'desc' => $faker->text,
                'lang' => $faker->languageCode,
                'age_restrict' => $ageRestr[mt_rand(0, 2)],
                'cover_img' => $faker->imageUrl,
            ]);
        }
    }
}
