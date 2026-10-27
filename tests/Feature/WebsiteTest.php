<?php

namespace Tests\Feature;

use App\Models\Enquiry;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class WebsiteTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * 1. Public Homepage & Localization
     */
    public function test_homepage_renders_in_english()
    {
        $response = $this->get('/en');
        $response->assertStatus(200);
        $response->assertSee('Chauhan Agro Industries');
        $response->assertSee('Machinery & Implements');
        $response->assertSee('About Us');
    }

    public function test_homepage_renders_in_hindi()
    {
        $response = $this->get('/hi');
        $response->assertStatus(200);
        $response->assertSee('चौहान एग्रो');
    }

    /**
     * 2. Product Catalogue & Details
     */
    public function test_product_catalogue_renders()
    {
        $response = $this->get('/en/products');
        $response->assertStatus(200);
        $response->assertSee('Tractor Trollies');
    }

    public function test_product_category_filtering()
    {
        $response = $this->get('/en/products?category=rotary-tillers');
        $response->assertStatus(200);
        $response->assertSee('Heavy Duty Hydraulic Tipping Trolley');
    }

    public function test_product_detail_page_renders_with_specifications()
    {
        $product = Product::where('slug', 'agrovision-gold-rotary-tiller-rotavator')->first();
        $this->assertNotNull($product, 'Test product should exist from seeder');

        $response = $this->get('/en/products/' . $product->slug);
        $response->assertStatus(200);
        $response->assertSee('Technical Specifications');
        $response->assertSee('Download Brochure');
        $response->assertSee('Request Quote');
    }

    /**
     * 3. CMS Dynamic Pages
     */
    public function test_cms_dynamic_page_renders()
    {
        $response = $this->get('/en/about-us');
        $response->assertStatus(200);
        $response->assertSee('About Chauhan Agro Industries');
    }

    public function test_contact_page_renders()
    {
        $response = $this->get('/en/contact');
        $response->assertStatus(200);
        $response->assertSee('Contact Chauhan Agro Industries');
    }

    /**
     * 4. Contact / Enquiry Submission
     */
    public function test_enquiry_form_submission_saves_to_database()
    {
        $data = [
            'name' => 'Kisan Kumar',
            'email' => 'kisan@example.com',
            'phone' => '+91 98765 00000',
            'city' => 'Ludhiana, Punjab',
            'subject' => 'Quote for Rotary Tiller 7 Feet',
            'message' => 'Interested in purchasing 5 units for our cooperative farm.',
            'website_url' => '', // Honeypot must be empty
        ];

        $response = $this->post('/en/contact', $data);
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('enquiries', [
            'email' => 'kisan@example.com',
            'name' => 'Kisan Kumar',
            'subject' => 'Quote for Rotary Tiller 7 Feet',
        ]);
    }

    public function test_enquiry_honeypot_silently_rejects_bots()
    {
        $botData = [
            'name' => 'Spam Bot',
            'email' => 'bot@spammer.org',
            'phone' => '1234567890',
            'subject' => 'Cheap SEO Services',
            'message' => 'Visit my spam site',
            'website_url' => 'https://spam-link-bot.com', // Filled honeypot
        ];

        $response = $this->post('/en/contact', $botData);
        $response->assertRedirect();

        $this->assertDatabaseMissing('enquiries', [
            'email' => 'bot@spammer.org',
        ]);
    }

    /**
     * 5. Admin Authentication & Role-Based Access Control
     */
    public function test_guest_is_redirected_to_admin_login()
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_login_successfully()
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@agrovision.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticated();
    }

    public function test_super_admin_can_access_users_management()
    {
        $admin = User::where('email', 'admin@agrovision.com')->first();
        $this->actingAs($admin);

        $response = $this->get('/admin/users');
        $response->assertStatus(200);
        $response->assertSee('Admin Users');
    }

    public function test_content_manager_forbidden_from_users_management()
    {
        $contentManager = User::where('email', 'content@agrovision.com')->first();
        $this->actingAs($contentManager);

        $response = $this->get('/admin/users');
        $response->assertStatus(403);
    }

    public function test_product_manager_can_access_products_management()
    {
        $productManager = User::where('email', 'product@agrovision.com')->first();
        $this->actingAs($productManager);

        $response = $this->get('/admin/products');
        $response->assertStatus(200);
        $response->assertSee('Add Machinery Item');
    }
}
