<?php

namespace Tests\Unit;

use App\Models\Board;
use Tests\TestCase;

class BoardModelTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
        $this->assertTrue(true);
        $initial_count = Board::count();

        echo ("Initial count: $initial_count\n");
    }
}
