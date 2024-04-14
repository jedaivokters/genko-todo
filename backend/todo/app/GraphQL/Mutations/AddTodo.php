<?php

namespace App\GraphQL\Mutations;

use App\Models\Todo;
use Illuminate\Support\Facades\Validator;

class AddTodo
{
    public function __invoke($_, array $args)
    {
        // Validate the input data
        $validator = Validator::make($args, [
            'text' => 'required|string'
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            throw new \InvalidArgumentException($validator->errors()->first());
        }

        return Todo::create([
            'text' => $args['text'],
            'completed' => false,
        ]);
    }
}
