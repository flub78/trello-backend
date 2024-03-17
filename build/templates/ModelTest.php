<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwritting.
 */

namespace Tests\Unit;

use App\Models\{{class}};
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;


/**
 * Test the {{class}} model
 */
class {{class}}ModelTest extends TestCase
{
    /**
     * Test element creation, read, update and delete
     * Given the database server is on
     * Given the schema exists in database
     * When creating an element
     * Then it is stored in database, it can be read, updated and deleted
     */
    public function testCRUD(): void
    {
        $this->assertTrue(true);
        $initial_count = {{class}}::count();

        // Create some elements
        $elt1 = {{class}}::factory()->make();
        $this->assertNotNull($elt1, "the element 1 has been created");
        $this->assertTrue($elt1->save(), "the element 1 has been saved in database");

        $elt2 = {{class}}::factory()->make();
        $this->assertNotNull($elt2, "the element 2 has been created");
        $this->assertTrue($elt2->save(), "the element 2 has been saved in database");

        $elt3 = {{class}}::factory()->make();
        $elt3_key = $elt3->{{primary_key}};  // if the primary key is provided by the factory
        $this->assertNotNull($elt3, "the element 3 has been created");
        $this->assertTrue($elt3->save(), "the element 3 has been saved in database");
        if ('{{primary_key}}' == 'id') $elt3_key = $elt3->id;   // if the primary key is auto incremented


        $new_count = {{class}}::count();
        $this->assertTrue($new_count == $initial_count + 3, "3 elements added to the database");

        // Read back the elements
        $latest = {{class}}::find($elt3_key);
        $this->assertNotNull($latest, "an element can be fetched by its key: " . $elt3_key);

        // for csv_high_variability_fields
        // fields with low variability cannot be compared as they could be identical between two elements
        $diff = 0;
        $high_variability_fields = [{{#cg}} csv_high_variability_fields {{/cg}}];
        foreach ($high_variability_fields as $key) {
            if ($elt2->$key != $latest->$key) {
                $diff++;
                $latest->$key = $elt2->$key;
            }
        }

        $this->assertTrue($diff > 0, "at least 1 differences between elt2 and latest");

        // Update the element
        if ($diff > 0) {
            $this->assertTrue($latest->save(), "updated element has been saved");

            // Read back the element
            $found = {{class}}::find($elt3_key);
            $this->assertNotNull($found, "and it can be read back from database");

            foreach ($high_variability_fields as $key) {
                $this->assertEquals($elt2->$key, $found->$key, "$key updated");
            }
        }

        // Delete the elements
        $this->assertTrue($elt3->delete(), "the element 3 has been deleted from database");
        $this->assertTrue($elt1->delete(), "the element 1 has been deleted from database");
        $this->assertTrue($elt2->delete(), "the element 2 has been deleted from database");

        $final_count = {{class}}::count();
        $this->assertEquals($initial_count, $final_count, "\$initial_count:$initial_count == \$final_count: $final_count");
    }
}
