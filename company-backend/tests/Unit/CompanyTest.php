<?php

namespace Tests\Unit;

use App\Models\Company;
use App\Models\User;
use App\Services\PhoneVerificationService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Mockery;
class CompanyTest extends TestCase
{

    use RefreshDatabase; // This will reset the database after each test

    protected function tearDown(): void
    {
        Mockery::close(); // Close Mockery after each test
        parent::tearDown();
    }
    protected function mimicRegistrationAndLoggingIn():array
    {
        // Step 1: Create user record
       $user = User::create([
           'name' => 'Markus',
           'email' => 'emailRRR@gmail.com',
            'password' => bcrypt('password'),
        ]);

        // Step 2: Simulate login
        $response = $this->post('api/auth/login', [
           'email' => $user->email,
           'password' => 'password',
        ]);

       // Check if the login was successful and a token was returned
       $token = $response->json('token');
       if (!$token) {
           throw new Exception('Login failed, no token returned.');
        }

        return ['token' => $token, 'user' => $user];
    }

    public function test_it_can_register_a_company()
    {
        $data= $this->mimicRegistrationAndLoggingIn();
        $token=$data['token'];

        // Create a mock phone verification service
        $mockPhoneVerificationService = Mockery::mock(PhoneVerificationService::class);

        // Define the behavior of the mock service
        $mockPhoneVerificationService->shouldReceive('verifyPhoneNumber')
            ->with('+963-933111234') // Phone number to verify
            ->andReturn(['valid' => true]); // Return a valid response

        // Bind the mock service to the IoC container
        $this->app->instance(PhoneVerificationService::class, $mockPhoneVerificationService);

       // Define the data for the new company
       $data = [
            'name' => 'Test Company',
            'email' => 'test@example.com',
            'phone' => '+963-933111234',
            'country' => 'Syria',
            'industry' => 'Technology',
        ];
        // Step 3: simulate registering a company
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
           'Accept'=>'json'
        ])->post('api/companies',
           $data
        );


       //Step 4: assert that the status is 201
       $response->assertStatus(201);
       // Assert that the company was created in the database
      $this->assertDatabaseHas('companies', [
          'name' => 'Test Company',
           'email' => 'test@example.com',
            'phone' => '+963-933111234',
           'country' => 'Syria',
           'industry' => 'Technology',
        ]);

   }

    public  function test_it_fails_to_register_new_company_with_invalid_phone_number()
    {

        $data= $this->mimicRegistrationAndLoggingIn();
        $token=$data['token'];

        // Define the data for the new company
        $data = [
            'name' => 'Test Company',
            'email' => 'test@example.com',
            'phone' => '+963-133111234', //invalid phone number
            'country' => 'Syria',
            'industry' => 'Technology',
        ];

        // Create a mock phone verification service
        $mockPhoneVerificationService = Mockery::mock(PhoneVerificationService::class);

        // Define the behavior of the mock service
        $mockPhoneVerificationService->shouldReceive('verifyPhoneNumber')
            ->with('+963-133111234') // Phone number to verify
            ->andReturn(['valid' => false]); // Return an invalid response

        // Bind the mock service to the IoC container
        $this->app->instance(PhoneVerificationService::class, $mockPhoneVerificationService);

        // Bind the mock service to the IoC container
        $this->app->instance(PhoneVerificationService::class, $mockPhoneVerificationService);

        // Step 3: simulate uploading profile_photo
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept'=>'json'
        ])->post('api/companies',
            $data
        );


        //Step 4: assert that the status is 422
        $response->assertStatus(422);
       $response->assertJson(["error" => "your phone number is not valid"]);



    }


    public function test_it_fails_to_access_protected_endpoint()
    {

        $token="bad-token";

     $response = $this->withHeaders([
    'Authorization' => 'Bearer ' . $token,
         'Accept' => 'application/json'
   ])->get('api/companies/'
     );


        $response->assertStatus(401);
        $response->assertJson(["message"=> "Unauthenticated."]);
    }

    public function test_it_can_update_company_credential()
    {
       $data= $this->mimicRegistrationAndLoggingIn();
        $token= $data['token'];


        //mimic that new company uploaded :
       $company = Company::create([
            'name' => 'Test Company',
            'email' => 'test@example.com',
            'phone' => '+963-533111234',
            'country' => 'Syria',
            'industry' => 'Technology',
        ]);

        // Define the updated data for the company
        $updatedData = [
            'name' => 'Updated Company Name',
            'email' => 'updated@example.com',
            'phone' => '+963-533111234',
            'country' => 'Syria',
            'industry' => 'Technology',
        ];

       //mimic calling update function
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->put("api/companies/$company->id",$updatedData
        );


        // Assert response status .. 204 (no content )
        $response->assertStatus(204);
        // Assert that the company was updated in the database
        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'name' => 'Updated Company Name',
            'email' => 'updated@example.com',
            'phone' => '+963-533111234',
            'country' => 'Syria',
            'industry' => 'Technology',
        ]);


    }


    public function test_user_cannot_update_company_with_invalid_data()
    {

        $data = $this->mimicRegistrationAndLoggingIn();
        $token = $data['token'];

        // Create a company to update
        $company = Company::create([
            'name' => 'Old Company Name',
            'email' => 'old@example.com',
            'phone' => '+963-933111234',
            'country' => 'Syria',
            'industry' => 'Technology',
        ]);

        $invalidData = [
            'name' => '', // Invalid: name cannot be empty
            'email' => 'invalid-email', // Invalid: email format
            'phone' => '', // Invalid: phone cannot be empty
            'country' => 'Syria',
            'industry' => 'Technology',
        ];

        //mimic calling update function
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->put("api/companies/$company->id",$invalidData
        );

        // Assert that the response status is 422 (Unprocessable Entity)
        $response->assertStatus(422);

        // Assert that the company was not updated in the database
        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'name' => 'Old Company Name', // Ensure the old name is still present
            'email' => 'old@example.com', // Ensure the old email is still present
            'phone' => '+963-933111234', // Ensure the old phone is still present
            'country' => 'Syria',
            'industry' => 'Technology',
        ]);
    }
}
