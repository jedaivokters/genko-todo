<?php

namespace App\GraphQL\Mutations;

use App\Models\Todo;

class AddTodo
{
    public function __invoke($_, array $args)
    {
        return Todo::create([
            'text' => $args['text'],
            'completed' => false,
        ]);
    }
}
