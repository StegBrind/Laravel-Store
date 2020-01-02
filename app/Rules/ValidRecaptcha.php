<?php

namespace App\Rules;

use GuzzleHttp\Client;
use Illuminate\Contracts\Validation\Rule;

class ValidRecaptcha implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $client = new Client;
        $response = json_decode($client->post('https://www.google.com/recaptcha/api/siteverify', [
            'form_params' => [
                'secret' => '6Ldwz7EUAAAAAIwBMFI-LncYQvhyK4hmmQjoX0xv',
                'response' => $value
        ]])->getBody(), true);
        return $response['success'];
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Не удалось выполнить reCAPTCHA.';
    }
}
