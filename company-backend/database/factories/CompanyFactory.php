<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
   {
       // Define a list of country codes
       $countryCodes = [
           'US' => '+1',
           'UK' => '+44',
           'CA' => '+1',
           'AU' => '+61',
           'DE' => '+49',
           'FR' => '+33',
           'IN' => '+91',
           'SY' => '+963',
       ];

       // Randomly select a country code
       $countryKey = $this->faker->randomElement(array_keys($countryCodes));
       $countryCode = $countryCodes[$countryKey];

       // Generate a random phone number
       $phoneNumber = $this->faker->numerify('##########'); // Generates a 10-digit number

       // Format the phone number in the desired international format
       $formattedPhoneNumber = "{$countryCode}-{$phoneNumber}";

    return [
        'name' => $this->faker->company,
        'email' => $this->faker->unique()->safeEmail,
        'country' => $this->faker->country,
        'industry' => $this->faker->word,
        'phone' => $formattedPhoneNumber,
    ];
}
}
