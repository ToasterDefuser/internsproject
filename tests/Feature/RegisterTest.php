<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
 
class RegisterTest extends TestCase
{
    use RefreshDatabase;
 
    public function testRegisterView(): void
    {
        $response = $this->get('/register');
 
        $response->assertStatus(200);
    }
 
    public function testNewUserCanRegister(): void
    {
        $response = $this->post('/RegisterController', [
            'name' => 'TestUserrr',
            'pwd' => 'testPwdrrr'
        ]);
 
        $this->assertAuthenticated($guard = null);
        $response->assertRedirect('/import');
    }
}