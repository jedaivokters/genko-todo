<?php

namespace App\GraphQL\Mutations;

use App\Models\Todo;

class DestroyAllTodos
{
    public function __invoke($_, array $args)
    {
        Todo::truncate();

        return true;
    }
}