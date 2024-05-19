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
class PageSortFilterApiControllerTest extends TestCase {

    public function test_api_sort_and_filter(): void {

        $this->base_url = '/api/tasks';

        // get the tasks ordered by creation date
        $response = $this->get($this->base_url . '?sort=created_at');
        $response->assertStatus(200);
        $initial_json = $response->json();

        $initial_count = count($initial_json);
        Log::info("TaskControllerTest.test_api_sort initial_count: $initial_count");

        // Get the tasks ordered inverse creation date
        $response = $this->get($this->base_url . '?sort=-created_at');
        $response->assertStatus(200);
        $inverse_date_json = $response->json();

        // Get the tasks ordered inverse name
        $response = $this->get($this->base_url . '?sort=-name');
        $response->assertStatus(200);
        $inverse_name_json = $response->json();

        // Check the order of the lists
        $cnt = 0;
        $incerse_cnt = 5;
        foreach ($initial_json as $task) {
            $this->assertEquals($task["name"], $inverse_name_json[$incerse_cnt]["name"]);
            $this->assertEquals($task["created_at"], $inverse_name_json[$incerse_cnt]["created_at"]);
            $this->assertEquals($task["name"], $inverse_date_json[$incerse_cnt]["name"]);
            $this->assertEquals($task["created_at"], $inverse_date_json[$incerse_cnt]["created_at"]);
            $cnt++;
            $incerse_cnt--;
        }

        // Get the tasks ordered by description
        $response = $this->get($this->base_url . '?sort=description');
        $response->assertStatus(200);
        $description_json = $response->json();

        // Check the order of the lists
        $ref = "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA";
        foreach ($description_json as $task) {
            $description = $task["description"];
            $this->assertGreaterThanOrEqual($ref, $description);
            $ref = $description;
        }

        // Get a range of the tasks
        $response = $this->get($this->base_url . '?filter=tasks.name:>Task 2');
        $head_truncated_json = $response->json();
        $this->assertEquals(4, count($head_truncated_json), 'Expected 4 tasks after head truncation');
        $this->assertEquals('task 3', $head_truncated_json[0]["name"], 'Expected Task 3 to be the first task after head truncation');

        // Truncate at both side
        $response = $this->get($this->base_url . '?filter=tasks.name:>Task 2&filter=tasks.name:<=Task 5');
        $truncated_json = $response->json();
        $this->assertEquals(3, count($truncated_json), 'Expected 4 tasks after truncation');
        $this->assertEquals('task 3', $truncated_json[0]["name"], 'Expected Task 3 to be the first task after truncation');
        $this->assertEquals('task 5', $truncated_json[2]["name"], 'Expected Task 5 to be the last task after truncation');

        // extract a word from the description of task 3
        $description_3 = $initial_json[2]["description"];
        $word = explode(' ', $description_3)[0];
        // echo "word: $word\n"; // Deserunt
        $response = $this->get($this->base_url . "?filter=description:~=$word");
        $filtered_json = $response->json();
        $this->assertGreaterThan(0, count($filtered_json), 'Expected at least one task after filtering');
        foreach ($filtered_json as $task) {
            $description = $task["description"];
            $this->assertStringContainsStringIgnoringCase($word, $description);
        }
    }

    public function test_api_pagination(): void {

        $this->base_url = '/api/tasks';

        // get the first page
        $response = $this->get($this->base_url . '?per_page=2');
        $response->assertStatus(200);
        $json = $response->json();
        $this->assertEquals(2, count($json['data']), 'Expected 2 tasks after pagination');
        $this->assertEquals(2, $json['per_page'], 'per page = 2');
        $this->assertEquals(6, $json['total'], 'total = 6');
        $this->assertNotNull($json['next_page_url'], 'next_page not null');
        $this->assertNull($json['prev_page_url'], 'next_page not null');

        // get the first page
        $response = $this->get($this->base_url . '?per_page=2&page=1');
        $response->assertStatus(200);
        $json = $response->json();
        $this->assertEquals(2, count($json['data']), 'Expected 2 tasks after pagination');
        $this->assertEquals(2, $json['per_page'], 'per page = 2');
        $this->assertEquals(6, $json['total'], 'total = 6');
        $this->assertNotNull($json['next_page_url'], 'next_page not null');
        $this->assertNull($json['prev_page_url'], 'next_page not null');

        // get the last page
        $response = $this->get($this->base_url . '?per_page=2&page=3');
        $response->assertStatus(200);
        $json = $response->json();
        $this->assertEquals(2, count($json['data']), 'Expected 2 tasks after pagination');
        $this->assertEquals(2, $json['per_page'], 'per page = 2');
        $this->assertEquals(6, $json['total'], 'total = 6');
        $this->assertNull($json['next_page_url'], 'next_page not null');
        $this->assertNotNull($json['prev_page_url'], 'next_page not null');

        // get a non existing page
        $response = $this->get($this->base_url . '?per_page=2&page=99');
        $response->assertStatus(200);
        $json = $response->json();
        $this->assertEquals(0, count($json['data']), 'Expected 2 tasks after pagination');
        $this->assertEquals(2, $json['per_page'], 'per page = 2');
        $this->assertEquals(6, $json['total'], 'total = 6');
        $this->assertNull($json['next_page_url'], 'next_page not null');
        $this->assertNotNull($json['prev_page_url'], 'next_page not null'); // the system returns another non existing page decrementedby 1 :-)

    }
}
