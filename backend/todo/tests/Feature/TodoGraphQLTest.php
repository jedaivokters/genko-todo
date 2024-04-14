<?php

namespace Tests\Feature;

use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use App\Models\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

    public function testUpdateTodo()
    {
        // Create a todo to update
        $todo = Todo::factory()->createOne();

        //Request data
        $requestData = [
            'id' => $todo->id,
            'text' => 'Updated Todo',
            'completed' => true,
        ];

        // Make a GraphQL mutation to update a todo
        $response = $this->graphQL('
            mutation UpdateTodo($id: ID!, $text: String!, $completed: Boolean!) {
                updateTodo(id: $id, text: $text, completed: $completed) {
                    id
                    text
                    completed
                }
            }
        ', $requestData);

        $response->assertStatus(200);

        // Assert that the updated todo matches the request data
        $response->assertJson([
            'data' => [
                'updateTodo' => [
                    'id' => $requestData['id'],
                    'text' => $requestData['text'],
                    'completed' => $requestData['completed'],
                ]
            ]
        ]);
    }

    public function testDestroyTodo()
    {
        // Create a todo to delete
        $todo = Todo::factory()->createOne();

        // Make a GraphQL mutation to delete a todo
        $response = $this->graphQL('
            mutation DestroyTodo($id: ID!) {
                destroyTodo(id: $id)
            }
        ', ['id' => $todo->id]);

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                'destroyTodo' => true
            ]
        ]);

        // Assert that data is not existing anymore
        $this->assertDatabaseMissing('todos',[
            'id' => $todo->id
        ]);
    }

    public function testDestroyAllTodos()
    {
        // Create a todo to delete
        $todos = Todo::factory()->count(5)->create();
        $firstTodoId = $todos->first()->id;

        // Make a GraphQL mutation to delete all todos
        $response = $this->graphQL('
            mutation DestroyAllTodos {
                destroyAllTodos
            }
        ');

        $response->assertStatus(200);
        
        $response->assertJson([
            'data' => [
                'destroyAllTodos' => true
            ]
        ]);

        // Assert that data is not existing anymore
        $this->assertDatabaseMissing('todos',[
            'id' => $firstTodoId
        ]);
    }

    public function testDestroyAllCompletedTodos()
    {
        Todo::factory()->count(2)->create(); //generate not completed
        Todo::factory()->count(5)->create(['completed' => true]); //generate completed

        // Make a GraphQL mutation to delete all completed todos
        $response = $this->graphQL('
            mutation DestroyCompletedTodos {
                destroyCompletedTodos
            }
        ');

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                'destroyCompletedTodos' => true
            ]
        ]);

        // Assert that data is not existing anymore
        $this->assertDatabaseMissing('todos',[
            'completed' => true
        ]);

        //Assert that the data has 2 not completed task record
        $this->assertDatabaseCount('todos',2);
    }

}
