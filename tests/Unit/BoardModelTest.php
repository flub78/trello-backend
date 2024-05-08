<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwritting.
 */

namespace Tests\Unit;

use App\Models\Board;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;


/**
 * Test the Board model
 */
class BoardModelTest extends TestCase
{
    protected $log = true;

    /**
     * Test element creation, read, update and delete
     * Given the database server is on
     * Given the schema exists in database
     * When creating an element
     * Then it is stored in database, it can be read, updated and deleted
     */
    public function testCRUD(): void
    {
        $initial_count = Board::count();
        if ($this->log) {
            Log::info("BoardModelTest.testCRUD initial_count: $initial_count");
        }

        // Create some elements
        $elt1 = Board::factory()->make();
        $elt1_key = $elt1->name;  // if the primary key is provided by the factory
        $this->assertNotNull($elt1, "the element 1 has been created");
        $this->assertTrue($elt1->save(), "the element 1 has been saved in database");
        if ('name' == 'id')  {$elt1_key = $elt1->id;} // if the primary key is auto incremented

        $elt2 = Board::factory()->make();
        $elt2_key = $elt2->name;  // if the primary key is provided by the factory
        $this->assertNotNull($elt2, "the element 2 has been created");
        $this->assertTrue($elt2->save(), "the element 2 has been saved in database");
        if ('name' == 'id') $elt2_key = $elt2->id;   // if the primary key is auto incremented

        $elt3 = Board::factory()->make();
        $elt3_key = $elt3->name;  // if the primary key is provided by the factory
        $this->assertNotNull($elt3, "the element 3 has been created");
        $this->assertTrue($elt3->save(), "the element 3 has been saved in database");
        if ('name' == 'id') $elt3_key = $elt3->id;   // if the primary key is auto incremented

        $new_count = Board::count();
        $this->assertTrue($new_count == $initial_count + 3, "3 elements added to the database");

        // Read back the elements
        $relt1 = Board::find($elt1_key);
        $this->assertNotNull($relt1, "element 1 can be fetched by its key: " . $elt1_key);
        $relt2 = Board::find($elt2_key);
        $this->assertNotNull($relt2, "element 2 can be fetched by its key: " . $elt2_key);
        $relt3 = Board::find($elt3_key);
        $this->assertNotNull($relt3, "element 3 can be fetched by its key: " . $elt3_key);

        // for csv_high_variability_fields
        // fields with low variability cannot be compared as they could be identical between two elements
        $diff = 0;
        $high_variability_fields = ["description", "href", "picture"];
        foreach ($high_variability_fields as $key) {
            if ($relt2->$key != $relt3->$key) {
                $diff++;
                $relt3->$key = $relt2->$key;
            }
        }

        if (count($high_variability_fields)) {
            $this->assertTrue($diff > 0, "at least 1 differences between elt2 and latest");
        }

        // Update the element
        if ($diff > 0) {
            $this->assertTrue($relt3->save(), "updated element has been saved");

            // Read back the element
            $found = Board::find($elt3_key);
            $this->assertNotNull($found, "and it can be read back from database");

            foreach ($high_variability_fields as $key) {
                $this->assertEquals($relt2->$key, $found->$key, "$key updated");
            }
        }

        // Delete the elements
        $this->assertTrue($relt3->delete(), "the element 3 has been deleted from database");
        $this->assertTrue($relt1->delete(), "the element 1 has been deleted from database");
        $this->assertTrue($relt2->delete(), "the element 2 has been deleted from database");

        $final_count = Board::count();
        $this->assertEquals($initial_count, $final_count, "\$initial_count:$initial_count == \$final_count: $final_count");
        if ($this->log) {
            Log::info("BoardModelTest.testCRUD final_count: $final_count");
        }

    }
}
