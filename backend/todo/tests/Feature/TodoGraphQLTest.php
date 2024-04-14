<?php

namespace Tests\Feature;

use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use App\Models\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TodoGraphQLTest extends TestCase
{
    use RefreshDatabase;
    use MakesGraphQLRequests;

    public function testFetchTodos()
    {
        Todo::factory()->count(1)->create();

        // Make a GraphQL query to fetch todos
        $response = $this->graphQL('
            {
                todos {
                    id
                    text
                    completed
                }
            }
        ');

        // Assert response status 200
        $response->assertStatus(200);

        // Assert if json response structure
        $response->assertJsonStructure([
            'data' => [
                'todos' => [
                    '*' => ['id', 'text', 'completed']
                ]
            ]
        ]);
    }

    public function testAddTodos()
    {
        $todo = Todo::factory()->makeOne()->toArray();

        // Make a GraphQL mutation to add a todo
        $response = $this->graphQL('
            mutation AddTodo($text: String!) {
                addTodo(text: $text) {
                    id
                    text
                    completed
                }
            }
        ', $todo);

        // Assert response status 200
        $response->assertStatus(200);

        // Assert if json response structure
        $response->assertJsonStructure([
            'data' => [
                'addTodo' => ['id', 'text', 'completed']
            ]
        ]);

        // Assert that the todo was added successfully
        $response->assertJson([
            'data' => [
                'addTodo' => [
                    'text' => $todo['text'],
                    'completed' => $todo['completed'],
                ]
            ]
        ]);
    }
}
