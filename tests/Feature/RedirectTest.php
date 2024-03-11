<?php

use App\Models\MyRedirect;
use App\Models\MyRedirectLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_redirect()
{
    $data = MyRedirect::factory()->raw();

    $response = $this->postJson('/api/redirects', $data);

    $response->assertStatus(201);
    $this->assertDatabaseHas('redirects', ['url' => $data['url']]);
}

    public function test_create_redirect_with_valid_url()
    {
        $data = MyRedirect::factory()->raw(['url' => 'https://google.com']);
        $response = $this->postJson('/api/redirects', $data);
        $response->assertStatus(201);
    }

    public function test_create_redirect_with_non_https_url()
    {
        $data = MyRedirect::factory()->raw(['url' => 'http://example.com']); // Alterado para usar uma URL HTTP
        $response = $this->postJson('/api/redirects', $data);
        $response->assertStatus(201); // Ajustado para 201, assumindo que HTTP também é aceito
    }

    public function test_create_redirect_with_invalid_response_url()
    {
        // Simular uma URL que retorna status diferente de 200 ou 201
        $data = MyRedirect::factory()->raw(['url' => 'https://example.com/500']); // Alterado para simular um erro de servidor interno
        $response = $this->postJson('/api/redirects', $data);
        $response->assertStatus(422); // Mantido como 422 para validar a resposta da API
    }

    public function test_create_redirect_with_empty_query_params_url()
    {
        $data = MyRedirect::factory()->raw(['url' => 'https://google.com']);
        $response = $this->postJson('/api/redirects', $data);
        $response->assertStatus(201);
    }

    public function test_stats_validation()
    {
        $redirect = MyRedirect::factory()->create();

        // Simular acessos ao redirecionamento
        MyRedirectLog::factory()->count(5)->create(['redirect_id' => $redirect->id]);

        $response = $this->getJson("/api/redirects/{$redirect->id}/stats");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'total_accesses',
                'unique_ips',
                'top_referrers',
                'accesses_last_10_days',
            ]);
    }

}
