<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;

class CartTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRequiredFieldsForCart()
    {
        $user   = User::where('email', config('account.email'))->first();
        $token  = JWTAuth::fromUser($user);
        $this->withHeader('Authorization', "Bearer {$token}");

        $this->json('POST', 'api/cart', ['Accept' => 'application/json`'])
            ->assertStatus(422);
    }
}
