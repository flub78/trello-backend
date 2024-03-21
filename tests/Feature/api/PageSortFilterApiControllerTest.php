<?php

namespace Tests\Feature\Api;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Task;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

/**
 * Test pagination, sorting and filtering of the Task API
 *
 * As these features are implemented in the controller template
 * and basic CRUD is tested for all the tables,
 * we just need to test that they are working for one table.
 */
class PageSortFilterApiControllerTest extends TestCase
{

    public function test_api_sort(): void
    {
        // Pre-assertion: tasks are ordered by created_at and task name

        $this->base_url = '/api/tasks';
        $response = $this->get($this->base_url);
        $response->assertStatus(200);
        $initial_json = $response->json();

        $initial_count = count($initial_json);
        Log::info("TaskControllerTest.test_api_crud initial_count: $initial_count");

        var_dump($initial_json);
        foreach ($initial_json as $task) {
            echo "Task: " . $task["name"] . ", created at:" . $task["created_at"] . "\n";
        }

        // Get the tasks ordered descending names
        $response = $this->get($this->base_url . '?sort=-created_at');

    }
}
