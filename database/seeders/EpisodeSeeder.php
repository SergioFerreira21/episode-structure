<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Episodes;
use App\Models\Parts;
use App\Models\Items;
use App\Models\Blocks;

class EpisodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Episodes::factory(2)->has(
            Parts::factory(3)->has(
                Items::factory(5)->has(
                    Blocks::factory(10)
                )
            )
        )->create();
    }
}
