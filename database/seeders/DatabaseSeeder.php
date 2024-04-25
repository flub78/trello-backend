<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Board;
use App\Models\Checklist;
use App\Models\Column;
use App\Models\TagColor;
use App\Models\Task;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     */
    public function run(): void {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Create some elements

        // Populate Boards
        TagColor::factory(4)->create();

        $webapp = Board::factory()->create(["name" => "webapp", "href" => "board/webapp", "image" => "code_editor.jpg"]);
        $gvv = Board::factory()->create(["name" => "gvv", "href" => "board/gvv", "image" => "20230903_151040.jpg"]);
        $forest = Board::factory()->create(["name" => "forest", "href" => "board/forest", "image" => "IMG_20210425_145446.jpg"]);

        // factory-create returns an object but the primary key is not set

        // Populate columns
        $webapp_c1 = Column::factory()->create(["name" => "todo", "board_id" => "webapp"])->id;
        $webapp_c2 = Column::factory()->create(["name" => "In progress", "board_id" => "webapp"])->id;
        $webapp_c3 = Column::factory()->create(["name" => "done", "board_id" => "webapp"])->id;

        $webapp_list = implode(", ", ['"' . $webapp_c1 . '"', '"' . $webapp_c2 . '"', '"' . $webapp_c3 . '"']);

        $webapp = Board::find("webapp");
        $webapp->lists = $webapp_list;
        $webapp->save();

        $cl = [];
        for ($i = 0; $i < 10; $i++) {
            $cl[] = Column::factory()->create(["name" => "col " . $i, "board_id" => "gvv"])->id;
            $this->call(MetadataTableSeeder::class);
    }

        $gvv = Board::find("gvv");
        $gvv->lists = '"' . implode('", "', $cl) . '"';
        $gvv->save();

        // populate tasks

        Task::factory()->create(["name" => "task 1", "column_id" => $webapp_c1]);
        Task::factory()->create(["name" => "task 2", "column_id" => $webapp_c1]);
        Task::factory()->create(["name" => "task 3", "column_id" => $webapp_c1]);

        Task::factory()->create(["name" => "task 4", "column_id" => $webapp_c2]);

        Task::factory()->create(["name" => "task 5", "column_id" => $webapp_c3]);
        Task::factory()->create(["name" => "task 6", "column_id" => $webapp_c3]);

        // Populate checklists

        $task = Task::where('name', 'task 1')->first();

        Checklist::factory()->create(["name" => "steps", "description" => "checklist 1", "task_id" => $task->id]);
    }
}
