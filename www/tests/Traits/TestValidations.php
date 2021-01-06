<?php

namespace Tests\Traits;

use Illuminate\Testing\TestResponse;

trait TestValidations
{
    protected function assertInvalidationFields(
        TestResponse $response, 
        array $fields, 
        string $rule, 
        array $rule_params = [])
    {
        $response->assertStatus(422)
            ->assertJsonValidationErrors($fields);

        foreach ($fields as $field) {
            $field_name = str_replace('_', ' ', $field);
            $response->assertJsonFragment([
                \Lang::get("validation.{$rule}", ['attribute' => $field] + $rule_params)
            ]);
        }
    }

    protected function assertInvalidationMax()
    {
        #code...
    }
    
    
}
