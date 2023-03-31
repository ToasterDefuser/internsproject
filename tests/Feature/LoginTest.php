<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function LoginView(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function UserCanLogin(): void
    {   
        $response = $this->post('/RegisterController', [
            'name' => 'TestUser',
            'pwd' => 'testPwd'
        ]);

        $response = $this->post('/LoginController', [
            'name' => 'TestUser',
            'pwd' => 'testPwd'
        ]);
        $this->assertAuthenticated();
        $response->assertRedirect('/import');
    }
}
