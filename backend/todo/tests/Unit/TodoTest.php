<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TodoTest extends TestCase
{
    use RefreshDatabase;

    public function testTodoAttributes()
    {
        // Create a todo
        $todo = Todo::factory()->create([
            'text' => 'Test Todo',
            'completed' => false,
        ]);

        // Assert correct attributes
        $this->assertEquals('Test Todo', $todo->text);
        $this->assertFalse($todo->completed);
    }
}
