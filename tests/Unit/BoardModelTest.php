<?php

namespace Tests\Unit;

use App\Models\Board;
use Tests\TestCase;

class BoardModelTest extends TestCase
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
        $initial_count = Board::count();

        // Create some elements
        $elt1 = Board::factory()->make();
        $this->assertNotNull($elt1, "the element 1 has been created");
        $this->assertTrue($elt1->save(), "the element 1 has been saved in database");

        $elt2 = Board::factory()->make();
        $this->assertNotNull($elt2, "the element 2 has been created");
        $this->assertTrue($elt2->save(), "the element 2 has been saved in database");

        $elt3 = Board::factory()->make();
        $elt3_key = $elt3->name;
        $this->assertNotNull($elt3, "the element 3 has been created");
        $this->assertTrue($elt3->save(), "the element 3 has been saved in database");

        $new_count = Board::count();
        $this->assertTrue($new_count == $initial_count + 3, "3 elements added to the database");

        $latest = Board::latest()->first();

        // for non_unique_fillable_fields
        // count the differences between elt2 and elt3
        // and copy $elt2 values to $elt3
        $diff = 0;
        foreach (["description", "favorite", "href", "image", "theme", "lists"] as $key) {
            if ($elt2->$key != $latest->$key) {
                $diff++;
                $latest->$key = $elt2->$key;
            }
        }

        echo "diff: $diff\n";

        // Update the element
        if ($diff > 0) {
            $this->assertTrue($latest->save(), "updated element has been saved");

            // Read back the element
            $found = Board::find($elt3_key);
            $this->assertNotNull($found, "and it can be read back from database");

            $diff = 0;
            foreach (["description", "favorite", "href", "image", "theme", "lists"] as $key) {
                if ($elt2->$key != $found->$key) {
                    $diff++;
                }
            }

            $this->assertTrue($diff == 0, "the updated element is correct");
        }

        // Delete the element
        $this->assertTrue($elt3->delete(), "the element 3 has been deleted from database");
        $this->assertTrue($elt1->delete(), "the element 1 has been deleted from database");
        $this->assertTrue($elt2->delete(), "the element 2 has been deleted from database");

        $final_count = Board::count();
        $this->assertEquals($initial_count, $final_count, "the database has the same number of elements as before");
    }
}
