<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;

class RedirectRequest extends FormRequest
{
    public function rules()
    {
        return [
            'url' => ['required', 'url', 'active_url', function ($attribute, $value, $fail) {
                $parsedUrl = new Uri($value);
                $requestUrl = new Uri($this->url());

                if ($parsedUrl->getHost() === $requestUrl->getHost()) {
                    $fail('The :attribute must not point to the application itself.');
                }
            }],
        ];
    }

    public function withValidator($validator)
{
    $validator->after(function ($validator) {
        $client = new Client();
        $response = $client->head($this->url, ['http_errors' => false]);
        $statusCode = $response->getStatusCode();

        if ($statusCode !== 200) {
            $validator->errors()->add('url', 'The :attribute must return status 200.');
        }
    });
}

    public function messages()
    {
        return [
            'url.active_url' => 'The :attribute must be a valid URL.',
        ];
    }
}
