<?php

namespace App\GraphQL\Mutations;

use App\Models\Todo;

class DestroyTodo
{
    public function __invoke($_, array $args)
    {
        $todo = Todo::findOrFail($args['id']);
        $todo->delete();
        return true;
    }
}