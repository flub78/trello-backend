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

        // Create an element
        $elt1 = Board::factory()->create();
        $this->assertNotNull($elt1);
        $elt2 = Board::factory()->create();
        $this->assertNotNull($elt2);
        $elt3 = Board::factory()->create();
        $this->assertNotNull($elt3);

        $new_count = Board::count();
        $this->assertTrue($new_count == $initial_count + 3);

        // Read the element
        $found = Board::find($elt2->name);
        var_dump($found);

    }
}
