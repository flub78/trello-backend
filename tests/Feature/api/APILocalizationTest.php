<?php

/**
 * APILocalizationTest
 * 
 * Test the localization of the API
 */

namespace Tests\Feature\Api;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Board;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class APILocalizationTest extends TestCase {

    public function setUp(): void {
        parent::setUp();
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user, ['api-access']);
    }

    public function tearDown(): void {
        $this->user->delete();
        parent::tearDown();
    }

    /**
     * A basic test example.
     */
    public function test_lang_parameter_is_accepted(): void {

        // default locale
        $this->base_url = '/api/boards';
        $response = $this->get($this->base_url);
        $response->assertStatus(200);

        $json = $response->json();
        $this->assertTrue(is_array($json), 'Response is an array');
        $count = Board::count();
        $this->assertEquals($count, count($json), 'Response count matches database count');

        // unknown locale
        $response = $this->get('/api/boards?lang=xx');
        // TODO: check the log message
        $response->assertStatus(500);

        // known locale
        $this->base_url = '/api/boards?lang=fr';
        $response = $this->get($this->base_url);
        $response->assertStatus(200);

        // $response->dump();

        $json = $response->json();
        $this->assertTrue(is_array($json), 'Response is an array');
        $count = Board::count();
        $this->assertEquals($count, count($json), 'Response count matches database count');
    }

    /**
     * Test error messages in French
     */
    public function test_french_error_messages(): void {

        $locale = 'fr';

        $url = '/api/boards?lang=' . $locale;

        // Create an element with a missing field
        $elt1 = Board::factory()->make();
        $elt1->name = '';
        $response = $this->post($url, $elt1->toArray());

        $response->assertStatus(422);
        $json = $response->json();

        $this->assertTrue(isset($json['errors']), 'Errors are present in the response');
        $this->assertTrue(isset($json['errors']['name']), 'Name is missing');
        $this->assertEquals('Le champ nom est obligatoire.', $json['errors']['name'][0], 'Name is missing in French');
        $this->assertEquals('Echec de la validation', $json['message'], 'Validation failed in French');

        // fetch un unknown element
        $key = "unknown";
        $url = '/api/boards/' . $key . '?lang=' . $locale;

        $response = $this->get($url);
        $response->assertStatus(404);
        $json = $response->json();
        $this->assertEquals('Element: unknown introuvable', $json['message'], 'Element not found in French');

        // delete an unknown element
        $key = "unknown";
        $url = '/api/boards/' . $key . '?lang=' . $locale;

        $response = $this->delete($url);
        $response->assertStatus(404);
        $json = $response->json();
        $this->assertEquals('Element: unknown introuvable', $json['message'], 'Element not found in French');
    }

    /**
     * Test error messages in French
     */
    public function test_english_error_messages(): void {

        $locale = 'en';

        $url = '/api/boards?lang=' . $locale;

        // Create an element with a missing field
        $elt1 = Board::factory()->make();
        $elt1->name = '';
        $response = $this->post($url, $elt1->toArray());

        $response->assertStatus(422);
        $json = $response->json();

        $this->assertTrue(isset($json['errors']), 'Errors are present in the response');
        $this->assertTrue(isset($json['errors']['name']), 'Name is missing');
        $this->assertEquals('The name field is required.', $json['errors']['name'][0], 'Name is missing in French');
        $this->assertEquals('Validation failed', $json['message'], 'Validation failed in French');

        // fetch un unknown element
        $key = "unknown";
        $url = '/api/boards/' . $key . '?lang=' . $locale;

        $response = $this->get($url);
        $response->assertStatus(404);
        $json = $response->json();
        $this->assertEquals('Element: unknown not found', $json['message'], 'Element not found in French');

        // delete an unknown element
        $key = "unknown";
        $url = '/api/boards/' . $key . '?lang=' . $locale;

        $response = $this->delete($url);
        $response->assertStatus(404);
        $json = $response->json();
        $this->assertEquals('Element: unknown not found', $json['message'], 'Element not found in French');
    }


    /**
     * As a non regression test, we check that the API can be used with a long parameter
     * and that all messages are translated
     */
    public function test_localized_crud_api(): void {

        foreach (['en', 'fr'] as $lang) {

            // Read the initial state
            $this->base_url = '/api/boards/';
            $response = $this->get($this->base_url . '?lang=' . $lang);
            $response->assertStatus(200);
            $json = $response->json();

            $initial_count = count($json);
            Log::info("BoardControllerTest.test_api_crud initial_count: $initial_count");

            // Create some elements
            $elt1 = Board::factory()->make();
            $this->assertNotNull($elt1, "the element 1 has been created");
            $response = $this->post($this->base_url . '?lang=' . $lang, $elt1->toArray());
            $response->assertStatus(201);
            $json = $response->json();
            $this->assertNotNull($json, "the element 1 has been saved in database");

            // count the new number of elements
            $elt1_key = $elt1['name'];
            $response = $this->get($this->base_url . '?lang=' . $lang);
            $json = $response->json();
            $new_count = count($json);
            $this->assertTrue($new_count == $initial_count + 1, "1 element added to the database");
            if ('name' == 'id') {
                $latest = Board::latest()->first();
                $elt1_key = $latest->id;
            }

            $elt2 = Board::factory()->make();

            // fetch back the created element
            $url = $this->base_url .  $elt1_key . '?lang=' . $lang;
            $response = $this->get($url);
            $response->assertStatus(200);
            $json = $response->json();
            $this->assertNotNull($json, "the element 1 can be fetched by its key: " . $elt1_key);

            // update the created element
            $diff = 0;
            $high_variability_fields = ["description", "href", "image", "lists"];

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
                $response = $this->put($this->base_url . $elt1_key . '?lang=' . $lang, $elt1->toArray());
                $response->assertStatus(200);
                $json = $response->json();
                $this->assertNotNull($json, "updated element has been saved");

                // Read back the element
                $response = $this->get($this->base_url . $elt1_key . '?lang=' . $lang);
                $response->assertStatus(200);
                $json = $response->json();
                $this->assertNotNull($json, "and it can be read back from database");

                foreach ($high_variability_fields as $key) {
                    $this->assertEquals($elt1->$key, $json[$key], "updated field $key matches");
                }
            }

            // delete the created element
            $response = $this->delete($this->base_url . $elt1_key . '?lang=' . $lang);
            $response->assertStatus(200);
            $json = $response->json();
            $this->assertNotNull($json, "the element 1 has been deleted");

            // count the new number of elements
            $response = $this->get($this->base_url . '?lang=' . $lang);
            $json = $response->json();
            $final_count = count($json);
            $this->assertTrue($final_count == $initial_count, "back to the initial number of elements");
        }
    }
}
