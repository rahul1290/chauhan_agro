<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SiteSettingTest extends TestCase
{
    use DatabaseTransactions;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::whereHas('role', function ($q) {
            $q->where('slug', 'super-admin');
        })->first() ?? User::first();
    }

    public function test_admin_settings_update_preserves_active_tab_on_redirect()
    {
        $this->actingAs($this->adminUser);

        $response = $this->post('/admin/settings', [
            'active_tab' => '#tab-contact',
            'toll_free_number' => '1800-555-0199',
            'primary_phone' => '+91-98765-11111',
            'sales_email' => 'sales@chauhanagroindustries.com',
        ]);

        $response->assertRedirect('/admin/settings#tab-contact');
        $response->assertSessionHas('active_tab', '#tab-contact');
        $response->assertSessionHas('success');
    }

    public function test_updated_toll_free_number_is_reflected_on_public_website()
    {
        $this->actingAs($this->adminUser);

        $testTollFree = '1800-777-3333';
        $testPrimaryPhone = '+91-99887-66554';

        $this->post('/admin/settings', [
            'active_tab' => '#tab-contact',
            'toll_free_number' => $testTollFree,
            'primary_phone' => $testPrimaryPhone,
        ]);

        // Verify in database
        $this->assertEquals($testTollFree, Setting::get('toll_free_number'));
        $this->assertEquals($testTollFree, Setting::get('phone_primary'));

        // Verify on public topbar / homepage
        $homeResponse = $this->get('/en');
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee($testTollFree);
        $homeResponse->assertSee($testPrimaryPhone);

        // Verify on public contact page
        $contactResponse = $this->get('/en/contact');
        $contactResponse->assertStatus(200);
        $contactResponse->assertSee($testTollFree);
        $contactResponse->assertSee($testPrimaryPhone);
    }
}
