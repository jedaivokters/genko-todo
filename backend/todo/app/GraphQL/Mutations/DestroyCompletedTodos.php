<?php

namespace App\GraphQL\Mutations;

use App\Models\Todo;

class DestroyCompletedTodos
{
    public function __invoke($_, array $args)
    {
        Todo::where('completed', true)->delete();
        return true;
    }
}