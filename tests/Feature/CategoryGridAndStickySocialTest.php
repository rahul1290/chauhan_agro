<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\PageSection;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CategoryGridAndStickySocialTest extends TestCase
{
    use DatabaseTransactions;

    protected $adminUser;
    protected $homePage;
    protected $catSection;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::whereHas('role', function ($q) {
            $q->where('slug', 'super-admin');
        })->first() ?? User::first();

        $this->homePage = Page::where('slug', 'home')->first();
        $this->catSection = PageSection::where('page_id', $this->homePage->id)
            ->where('type', 'category_grid')
            ->first();
    }

    public function test_admin_can_deactivate_and_activate_category_grid_section()
    {
        $this->actingAs($this->adminUser);

        // 1. Ensure category section is deactivated
        $this->catSection->is_active = false;
        $this->catSection->save();

        \Illuminate\Support\Facades\Artisan::call('view:clear');

        // Check homepage does NOT render category grid
        $response = $this->get('/en');
        $response->assertStatus(200);
        $response->assertDontSee('PRODUCT CATEGORIES');
        $response->assertDontSee('category-card');

        // 2. Toggle status to active via route
        $toggleResp = $this->post(route('admin.pages.sections.toggle', [$this->homePage, $this->catSection]));
        $toggleResp->assertRedirect();

        $this->catSection->refresh();
        $this->assertTrue((bool)$this->catSection->is_active);

        \Illuminate\Support\Facades\Artisan::call('view:clear');

        // Check homepage DOES render category grid when active
        $responseActive = $this->get('/en');
        $responseActive->assertStatus(200);
        $responseActive->assertSee('PRODUCT CATEGORIES');
        $responseActive->assertSee('category-card');

        // 3. Deactivate again via section update (unchecking is_active)
        $updateResp = $this->put(route('admin.pages.sections.update', [$this->homePage, $this->catSection]), [
            // is_active omitted -> unchecked
            'translations' => [
                'en' => [
                    'title' => 'Machinery Categories',
                ],
                'hi' => [
                    'title' => 'मशीनरी श्रेणियां',
                ],
            ],
        ]);
        $updateResp->assertRedirect();

        $this->catSection->refresh();
        $this->assertFalse((bool)$this->catSection->is_active);

        \Illuminate\Support\Facades\Artisan::call('view:clear');

        $responseDeactivated = $this->get('/en');
        $responseDeactivated->assertStatus(200);
        $responseDeactivated->assertDontSee('PRODUCT CATEGORIES');
        $responseDeactivated->assertDontSee('category-card');
    }

    public function test_sticky_social_bar_is_rendered_with_genuine_brand_colors()
    {
        Setting::set('floating_social_enabled', '1');
        Setting::set('floating_whatsapp_enabled', '1');
        Setting::set('floating_facebook_enabled', '1');
        Setting::set('floating_instagram_enabled', '1');
        Setting::set('floating_youtube_enabled', '1');
        Setting::set('floating_phone_enabled', '1');

        \Illuminate\Support\Facades\Artisan::call('view:clear');

        $response = $this->get('/en');
        $response->assertStatus(200);

        // Verify sticky bar container
        $response->assertSee('sticky-social-bar');

        // Verify brand color CSS classes
        $response->assertSee('social-whatsapp');
        $response->assertSee('social-facebook');
        $response->assertSee('social-instagram');
        $response->assertSee('social-youtube');
        $response->assertSee('social-phone');

        // Verify official brand icons
        $response->assertSee('bi-whatsapp');
        $response->assertSee('bi-facebook');
        $response->assertSee('bi-instagram');
        $response->assertSee('bi-youtube');
        $response->assertSee('bi-telephone-fill');
    }

    public function test_admin_can_toggle_floating_social_bar_in_settings()
    {
        $this->actingAs($this->adminUser);

        // 1. Disable floating social bar via admin settings
        $response = $this->post('/admin/settings', [
            'active_tab' => '#tab-social',
            'social_settings_present' => '1',
            // floating_social_enabled omitted -> unchecked
        ]);

        $response->assertRedirect('/admin/settings#tab-social');
        $this->assertEquals('0', Setting::get('floating_social_enabled'));

        \Illuminate\Support\Facades\Artisan::call('view:clear');

        // Verify it is not present on the frontend
        $frontendDisabled = $this->get('/en');
        $frontendDisabled->assertStatus(200);
        $frontendDisabled->assertDontSee('sticky-social-bar');

        // 2. Enable floating social bar via admin settings
        $responseOn = $this->post('/admin/settings', [
            'active_tab' => '#tab-social',
            'social_settings_present' => '1',
            'floating_social_enabled' => '1',
            'floating_whatsapp_enabled' => '1',
            'floating_facebook_enabled' => '1',
            'floating_instagram_enabled' => '1',
            'floating_youtube_enabled' => '1',
            'floating_phone_enabled' => '1',
        ]);

        $responseOn->assertRedirect('/admin/settings#tab-social');
        $this->assertEquals('1', Setting::get('floating_social_enabled'));

        \Illuminate\Support\Facades\Artisan::call('view:clear');

        // Verify it is present on the frontend
        $frontendEnabled = $this->get('/en');
        $frontendEnabled->assertStatus(200);
        $frontendEnabled->assertSee('sticky-social-bar');
    }
}
