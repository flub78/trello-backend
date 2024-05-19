<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwritting.
 */

namespace Tests\Feature\Api;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\{{class}};
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class {{class}}ApiControllerTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user, ['api-access']);
    }

    public function tearDown(): void
    {
        $this->user->delete();
        parent::tearDown();
    }

    /**
     * A basic test example.
     */
    public function test_basic_api_access(): void
    {
        $this->base_url = '/api/{{url}}';
        $response = $this->get($this->base_url);
        $response->assertStatus(200);

        // $response->dump();

        $json = $response->json();
        $this->assertTrue(is_array($json), 'Response is an array');
        $count = {{class}}::count();
        $this->assertEquals($count, count($json), 'Response count matches database count');

        $response = $this->get('/api/unknown');
        $response->assertStatus(404);
    }

    public function test_api_crud(): void
    {
        // Read the initial state
        $this->assertTrue(true);
        $this->base_url = '/api/{{url}}';
        $response = $this->get($this->base_url);
        $response->assertStatus(200);
        $json = $response->json();

        $initial_count = count($json);
        Log::info("{{class}}ControllerTest.test_api_crud initial_count: $initial_count");

        // Create some elements
        $elt1 = {{class}}::factory()->make();
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
        if ('{{primary_key}}' == 'id') {
            $latest = {{class}}::latest()->first();
            $elt1_key = $latest->id;
        }

        $elt2 = {{class}}::factory()->make();

        // fetch back the created element
        $response = $this->get($this->base_url . '/' . $elt1_key);
        $response->assertStatus(200);
        $json = $response->json();
        $this->assertNotNull($json, "the element 1 can be fetched by its key: " . $elt1_key);

        // update the created element
        $diff = 0;
        $high_variability_fields = [{{#cg}} csv_high_variability_fields {{/cg}}];

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
