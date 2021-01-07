<?php
declare(strict_types=1);

namespace Tests\Traits;

use Illuminate\Testing\TestResponse;

trait TestValidations
{

    protected function assertInvalidationStoreAction(
        array $data,
        string $rule,
        $rule_params = []
    ): void
    {
        $response = $this->json('POST', $this->routeStore(), $data);
        $fields = array_keys($data);
        $this->assertInvalidationFields($response, $fields, $rule, $rule_params);
    }

    protected function assertInvalidationUpdateAction(
        array $data,
        string $rule,
        $rule_params = []
    ): void
    {
        $response = $this->json('PUT', $this->routeUpdate(), $data);
        $fields = array_keys($data);
        $this->assertInvalidationFields($response, $fields, $rule, $rule_params);
    }

    protected function assertInvalidationFields(
        TestResponse $response, 
        array $fields, 
        string $rule, 
        array $rule_params = []): void
    {
        $response->assertStatus(422)
            ->assertJsonValidationErrors($fields);

        foreach ($fields as $field) {
            $field_name = str_replace('_', ' ', $field);
            $response->assertJsonFragment([
                \Lang::get("validation.{$rule}", ['attribute' => $field_name] + $rule_params)
            ]);
        }
    }
    
}
