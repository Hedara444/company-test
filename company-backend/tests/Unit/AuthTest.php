<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{

   use RefreshDatabase; // This will reset the database after each test

    /**
     * A basic feature test example.
     */
   private function generateDummyUser()
   {
       $user = User::create([
           'name'=>'User1',
           'email'=>'test.Aloha.222@example.com',
           'password'=>bcrypt('password'),
       ]);
       $user->save();
       return $user;
   }

    public function test_it_can_register_successfully()
    {
        $userData = [
            'name'=>'John due',
            'email'=>'temo@gmail.com',
            'password'=>'12345AAA',
            'password_confirmation'=>'12345AAA',
        ];

        $response = $this->post('api/auth/register' , $userData);

        $this->assertDatabaseHas('users',
            ['email'=>'temo@gmail.com',
            ]);

        $response->assertStatus(201);




    }

    public function test_it_fails_in_register_when_the_name_is_missing(){
        $userData = [
            'email'=>'temo@gmail.com',
            'password'=>'12345AAA',
            'password_confirmation'=>'12345AAA',
        ];

        $response = $this->post('api/auth/register' , $userData);


        $response->assertStatus(422);
        $response->assertJsonMissingValidationErrors(['name']); // Check for 'name' in validation errors
    }

    public  function test_it_fails_registration_when_email_is_duplicate()
    {
        // Step 1: generate dummy user and insert it in the database
        $this->generateDummyUser();

        $userData = [
            'name' => 'New User',
            'email' => 'test.Aloha.222@example.com', // Duplicate email
            'password' => 'password',
            'password_confirmation' => 'password',
        ];


        // Step 2: Send a POST request to the registration endpoint
        $response = $this->post('api/auth/register' , $userData);


        // Step 3: Assert that the response has validation errors
        $response->assertStatus(422); // Unprocessable Entity


        // Step 4: Assert that the response has validation errors
        $response->assertJson([
            'email' => ['The email has already been taken.'],
        ]);

    }

    public  function  test_it_can_login_successfully()
    {
        $this->generateDummyUser();

        $inputData =
            [
             'email'=>'test.Aloha.222@example.com',
             'password'=>'password'
            ];

        $response= $this->post('api/auth/login',$inputData);

        $response->assertStatus(200);

        $response->assertJsonStructure(['token']);
    }

    public  function  test_it_can_generate_token_successfully()
    {


        $this->generateDummyUser();

        $inputData =
            ['email'=>'test.Aloha.222@example.com',
                'password'=>'password'
            ];

        $response= $this->post('api/auth/login',$inputData);



        $response->assertJsonStructure(['token']);
    }

    public  function  test_it_fails_to_login_with_wrong_credentials()
    {
       $this->generateDummyUser();

        $loginCredentials = [
            'email'=>'test.Aloha.222@example.com',
            'password'=>'passCCC11##'
        ];

        $response= $this->post('api/auth/login',$loginCredentials);

        $response->assertStatus(422);
        $response->assertJson([
            "message"=>["The provided credentials are incorrect."] ,
        ]);

    }

    public  function test_it_logout_successfully()
    {
        $this->generateDummyUser();

        //step 2 : simulate logging in by generating a token
        $response = $this->post('api/auth/login',[
            'email'=>'test.Aloha.222@example.com',
            'password'=>'password'
        ]);

        //step 3 : get the token from the response
        $token = $response->json('token');

        //step 4 : Send the Logout request
        $response = $this->withHeaders([
            'Authorization'=> 'Bearer ' .  $token,
        ])->post('api/auth/logout');


        //step 5 : check assertions
        $response->assertStatus(200);
        $response->assertJson(['message'=>"Logged out successfully"]);
    }
}
