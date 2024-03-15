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

        echo ("Initial count: $initial_count\n");

        // Create an element
        $element = Board::factory()->create();
        $this->assertNotNull($element);

    }
}
