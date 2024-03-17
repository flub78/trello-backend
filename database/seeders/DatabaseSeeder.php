<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Board;
use App\Models\TagColor;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Create some elements
        TagColor::factory(4)->create();

        Board::factory()->create(["name" => "webapp"]);
        Board::factory()->create(["name" => "gvv"]);
        Board::factory()->create(["name" => "forest"]);

        $all_boards = Board::all();
        echo "Board: " . $all_boards[0]->name . "\n";
        echo "Board: " . $all_boards[1]->name . "\n";
        echo "Board: " . $all_boards[2]->name . "\n";

    }
}
