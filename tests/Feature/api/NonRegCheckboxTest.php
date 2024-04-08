<?php

/**
 * Non regression test. It was not possible to unset a boolean through the API.
 */

namespace Tests\Feature\Api;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Board;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class NonRegCheckboxTest extends TestCase {

    public function setUp(): void {
        parent::setUp();
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user, ['api-access']);
    }

    public function tearDown(): void {
        $this->user->delete();
        parent::tearDown();
    }

    public function test_boolean_can_be_set_and_reset_through_the_api(): void {

        // Read the initial state
        $this->base_url = '/api/boards';
        $response = $this->get($this->base_url);
        $response->assertStatus(200);
        $json = $response->json();

        $initial_count = count($json);
        Log::info("Boards initial_count: $initial_count");

        // Create some elements
        $elt1 = Board::factory()->make(["favorite" => false]);
        $this->assertNotNull($elt1, "the element 1 has been created");
        $response = $this->post($this->base_url, $elt1->toArray());
        $response->assertStatus(201);
        $this->assertNotNull($json, "the element to be modified has been saved in database");

        // count the new number of elements
        $elt1_key = $elt1['name'];
        $response = $this->get($this->base_url);
        $json = $response->json();
        $new_count = count($json);
        $this->assertTrue($new_count == $initial_count + 1, "1 element added to the database");

        // fetch back the element from database
        $response = $this->get($this->base_url . '/' . $elt1_key);
        $response->assertStatus(200);
        $json = $response->json();
        $this->assertNotNull($json, "the element 1 can be fetched by its key: " . $elt1_key);
        $json = $response->json();

        // update the created element
        $elt1->favorite = true;
        $elt1->favorite = "1";

        // Update the element
        $response = $this->put($this->base_url . '/' . $elt1_key, $elt1->toArray());
        $response->assertStatus(200);
        $json = $response->json();
        $this->assertNotNull($json, "updated element has been saved");

        // Read back the element
        $response = $this->get($this->base_url . '/' . $elt1_key);
        $response->assertStatus(200);
        $json = $response->json();
        var_dump($json);
        $this->assertNotNull($json, "and it can be read back from database");
        $this->assertEquals($json["favorite"], True, "updated favorite field is true " . $json["favorite"]);

        // update again in the other way
        $elt1->favorite = false;
        $elt1->favorite = "0";

        // Update the element
        $response = $this->put($this->base_url . '/' . $elt1_key, $elt1->toArray());
        $response->assertStatus(200);
        $json = $response->json();
        $this->assertNotNull($json, "updated element has been saved");

        // Read back the element
        $response = $this->get($this->base_url . '/' . $elt1_key);
        $response->assertStatus(200);
        $json = $response->json();
        $this->assertNotNull($json, "and it can be read back from database");
        $this->assertEquals($json["favorite"], False, "updated favorite field is false" . $json["favorite"]);

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
