<?php

namespace App\GraphQL\Queries;

use App\Models\Todo;

final class Todos
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        return Todo::all();
    }
}
