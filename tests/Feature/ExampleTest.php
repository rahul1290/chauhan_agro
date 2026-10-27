<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Test root route redirects to default locale.
     */
    public function test_the_root_redirects_to_default_locale()
    {
        $response = $this->get('/');
        $response->assertStatus(302);
        $response->assertRedirect('/en');
    }

    /**
     * Test localized home page renders successfully.
     */
    public function test_localized_home_page_returns_successful_response()
    {
        $response = $this->get('/en');
        $response->assertStatus(200);
        $response->assertSee('Chauhan Agro');
    }
}
