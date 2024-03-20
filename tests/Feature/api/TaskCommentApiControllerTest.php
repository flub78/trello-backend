<?php

namespace Tests\Feature\Api;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\TaskComment;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class TaskCommentApiControllerTest extends TestCase
{

    /**
     * A basic test example.
     */
    public function test_basic_api_access(): void
    {
        $this->base_url = '/api/task_comments';
        $response = $this->get($this->base_url);
        $response->assertStatus(200);

        // $response->dump();

        $json = $response->json();
        $this->assertTrue(is_array($json), 'Response is an array');
        $count = TaskComment::count();
        $this->assertEquals($count, count($json), 'Response count matches database count');

        $response = $this->get('/api/unknown');
        $response->assertStatus(404);
    }

    public function test_api_crud(): void
    {
        // Read the initial state
        $this->assertTrue(true);
        $this->base_url = '/api/task_comments';
        $response = $this->get($this->base_url);
        $response->assertStatus(200);
        $json = $response->json();

        $initial_count = count($json);
        Log::info("TaskCommentControllerTest.test_api_crud initial_count: $initial_count");

        // Create some elements
        $elt1 = TaskComment::factory()->make();
        $this->assertNotNull($elt1, "the element 1 has been created");
        $response = $this->post($this->base_url, $elt1->toArray());
        $response->assertStatus(201);
        $json = $response->json();
        $this->assertNotNull($json, "the element 1 has been saved in database");

        // count the new number of elements
        $elt1_key = $elt1['name'];
        $response = $this->get($this->base_url);
        $json = $response->json();
        $new_count = count($json);
        $this->assertTrue($new_count == $initial_count + 1, "1 element added to the database");
        if ('id' == 'id') {
            $latest = TaskComment::latest()->first();
            $elt1_key = $latest->id;
        }

        $elt2 = TaskComment::factory()->make();

        // fetch back the created element
        $response = $this->get($this->base_url . '/' . $elt1_key);
        $response->assertStatus(200);
        $json = $response->json();
        $this->assertNotNull($json, "the element 1 can be fetched by its key: " . $elt1_key);

        // update the created element
        $diff = 0;
        $high_variability_fields = ["text"];

        foreach ($high_variability_fields as $key) {
            if ($elt2->$key != $elt1->$key) {
                $diff++;
                $elt1->$key = $elt2->$key;
            }
        }

        if (count($high_variability_fields)) {
            $this->assertTrue($diff > 0, "at least 1 differences between elt2 and latest");
        }

        // Update the element
        if ($diff > 0) {
            $response = $this->put($this->base_url . '/' . $elt1_key, $elt1->toArray());
            $response->assertStatus(200);
            $json = $response->json();
            $this->assertNotNull($json, "updated element has been saved");

            // Read back the element
            $response = $this->get($this->base_url . '/' . $elt1_key);
            $response->assertStatus(200);
            $json = $response->json();
            $this->assertNotNull($json, "and it can be read back from database");

            foreach ($high_variability_fields as $key) {
                $this->assertEquals($elt1->$key, $json[$key], "updated field $key matches");
            }
        }

        // delete the created element
        $response = $this->delete($this->base_url . '/' . $elt1_key);
        $response->assertStatus(200);
        $json = $response->json();
        $this->assertNotNull($json, "the element 1 has been deleted");

        // count the new number of elements
        $response = $this->get($this->base_url);
        $json = $response->json();
        $final_count = count($json);
        $this->assertTrue($final_count == $initial_count, "back to the initial number of elements");

    }
}
