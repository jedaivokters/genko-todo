<?php

namespace App\GraphQL\Mutations;

use App\Models\Todo;

class UpdateTodo
{
    public function __invoke($_, array $args)
    {
        $todo = Todo::findOrFail($args['id']);
        
        $todo->update([
            'text' => $args['text'] ?? $todo->text,
            'completed' => $args['completed'] ?? $todo->completed,
        ]);

        return $todo;
    }
}