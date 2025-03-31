<?php

namespace Tests\Unit;

use App\Models\Company;
use App\Models\User;
use App\Services\PhoneVerificationService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery;
class SubmitTest extends TestCase
{
  use RefreshDatabase;

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


    public function test_user_can_submit_successfully()
    {

        //mimic that new company uploaded :
        $company = Company::create([
            'name' => 'Test Company',
            'email' => 'test@example.com',
            'phone' => '+963-533111234',
            'country' => 'Syria',
            'industry' => 'Technology',
        ]);

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
            'name' => 'Test Submit',
            'email' => 'test@example.com',
            'phone' => '+963-933111234',
            'companyId' => $company->id,
            'message' => 'message here ',
        ];

        // Step 3: simulate submitting an inquiry
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept'=>'json'
        ])->post('api/inquiries',
            $data
        );



        //Step 4: assert that the status is 201
        $response->assertStatus(201);

        // Assert that the Inquiry record was created in the database
        $this->assertDatabaseHas('inquiries', [
            'name' => 'Test Submit',
            'email' => 'test@example.com',
            'phone' => '+963-933111234',
            'company_id' => $company->id,
            'message' => 'message here ',
        ]);
    }
    public function test_user_submission_fails_due_to_wrong_credentials()

    {

        //mimic that new company uploaded :
        $company = Company::create([
            'name' => 'Test Company',
            'email' => 'test@example.com',
            'phone' => '+963-933111234',
            'country' => 'Syria',
            'industry' => 'Technology',
        ]);

        $data= $this->mimicRegistrationAndLoggingIn();
        $token=$data['token'];


        // Create a mock phone verification service
        $mockPhoneVerificationService = Mockery::mock(PhoneVerificationService::class);
        // Define the behavior of the mock service
        $mockPhoneVerificationService->shouldReceive('verifyPhoneNumber')
            ->with('+963-033111234') // Phone number to verify
            ->andReturn(['valid' => false]); // Return  invalid response

        // Bind the mock service to the IoC container
        $this->app->instance(PhoneVerificationService::class, $mockPhoneVerificationService);


        // Define the data for the new company
        $data = [
            'name' => 'Test Submit',
            'email' => 'test@example.com',
            'phone' => '+963-033111234',
            'companyId' => $company->id,
            'message' => 'message here ',
        ];

        // Step 3: simulate submitting an inquiry
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept'=>'json'
        ])->post('api/inquiries',
            $data
        );



        //Step 4: assert that the status is 422
        $response->assertStatus(422);

        // Assert that the Inquiry record was created in the database
        $this->assertDatabaseMissing('inquiries', [
            'name' => 'Test Submit',
            'email' => 'test@example.com',
            'phone' => '+963-933111234',
            'company_id' => $company->id,
            'message' => 'message here ',
        ]);
    }

}
