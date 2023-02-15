<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Faker\Generator as Faker;

class AuthenticationTest extends TestCase
{
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRequiredFieldsForRegistration()
    {
        $this->json('POST', 'api/register', ['Accept' => 'application/json'])
            ->assertStatus(422);
            // ->assertJson([
            //     "message" => "The given data was invalid.",
            //     "errors" => [
            //         "name" => ["The name field is required."],
            //         "email" => ["The email field is required."],
            //         "password" => ["The password field is required."],
            //     ]
            // ]);
    }

    public function testSuccessfulRegistration()
    {
        $userData = [
            'email'     => $this->faker()->email,
            'password'  => 'qweasd123',
            'name'      => $this->faker()->name,
            'address'   => $this->faker()->city,
            'phone'     => '081237412',
            'gender'    => 'male',
            'birthday'  => '1999-03-21'
        ];

        $this->json('POST', 'api/register', $userData, ['Accept' => 'application/json'])
            ->assertStatus(201);
            // ->assertJsonStructure([
            //     "user" => [
            //         'id',
            //         'name',
            //         'email',
            //         'created_at',
            //         'updated_at',
            //     ],
            //     "access_token",
            //     "message"
            // ]);
    }

    public function testMustEnterEmailAndPassword()
    {
        $this->json('POST', 'api/login')
            ->assertStatus(422);
            // ->assertJson([
            //     "message" => "The given data was invalid.",
            //     "errors" => [
            //         'email' => ["The email field is required."],
            //         'password' => ["The password field is required."],
            //     ]
            // ]);
    }

    public function testSuccessfulLogin()
    {
        $loginData = ['email' => 'user@email.com', 'password' => 'qweasd123'];

        $this->json('POST', 'api/login', $loginData, ['Accept' => 'application/json'])
            ->assertStatus(200);
            // ->assertJsonStructure([
            //    "user" => [
            //        'id',
            //        'name',
            //        'email',
            //        'email_verified_at',
            //        'created_at',
            //        'updated_at',
            //    ],
            //     "access_token",
            //     "message"
            // ]);

        $this->assertAuthenticated();
    }
}
