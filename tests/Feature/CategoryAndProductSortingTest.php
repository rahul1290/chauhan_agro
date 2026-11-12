<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CategoryAndProductSortingTest extends TestCase
{
    use DatabaseTransactions;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::whereHas('role', function ($q) {
            $q->whereIn('slug', ['super-admin', 'product-manager']);
        })->first() ?? User::first();
    }

    public function test_admin_can_view_category_list_with_different_sorting_options()
    {
        $this->actingAs($this->adminUser);

        // Default order
        $response = $this->get('/admin/categories?sort=order&direction=asc');
        $response->assertStatus(200);
        $response->assertSee('# Order');
        $response->assertSee('Drag rows or use arrows to change sequence');

        // Sort by name
        $response = $this->get('/admin/categories?sort=name&direction=asc');
        $response->assertStatus(200);

        // Sort by products count
        $response = $this->get('/admin/categories?sort=products_count&direction=desc');
        $response->assertStatus(200);

        // Sort by status
        $response = $this->get('/admin/categories?sort=status&direction=desc');
        $response->assertStatus(200);
    }

    public function test_admin_can_reorder_category_up_and_down()
    {
        $this->actingAs($this->adminUser);

        $categories = ProductCategory::orderBy('order', 'asc')->take(2)->get();
        if ($categories->count() >= 2) {
            $cat1 = $categories[0];
            $cat2 = $categories[1];

            $cat1OriginalOrder = $cat1->order;
            $cat2OriginalOrder = $cat2->order;

            // Move cat1 down
            $response = $this->post("/admin/categories/{$cat1->id}/reorder/down");
            $response->assertRedirect();

            $cat1->refresh();
            $cat2->refresh();

            // Orders should have swapped
            $this->assertEquals($cat2OriginalOrder, $cat1->order);
            $this->assertEquals($cat1OriginalOrder, $cat2->order);

            // Move cat1 back up
            $response = $this->post("/admin/categories/{$cat1->id}/reorder/up");
            $response->assertRedirect();

            $cat1->refresh();
            $this->assertEquals($cat1OriginalOrder, $cat1->order);
        }
    }

    public function test_admin_can_update_category_order_via_ajax()
    {
        $this->actingAs($this->adminUser);

        $categories = ProductCategory::orderBy('order', 'asc')->take(3)->get();
        if ($categories->count() >= 3) {
            $reversedIds = [$categories[2]->id, $categories[0]->id, $categories[1]->id];

            $response = $this->postJson('/admin/categories/update-order', [
                'ids' => $reversedIds
            ]);

            $response->assertStatus(200);
            $response->assertJson([
                'success' => true
            ]);

            $this->assertEquals(1, $categories[2]->fresh()->order);
            $this->assertEquals(2, $categories[0]->fresh()->order);
            $this->assertEquals(3, $categories[1]->fresh()->order);
        }
    }

    public function test_admin_can_view_product_list_with_different_sorting_options()
    {
        $this->actingAs($this->adminUser);

        // Default order
        $response = $this->get('/admin/products?sort=order&direction=asc');
        $response->assertStatus(200);
        $response->assertSee('# Order');
        $response->assertSee('Drag rows or use arrows to change sequence');

        // Sort by model name
        $response = $this->get('/admin/products?sort=name&direction=asc');
        $response->assertStatus(200);

        // Sort by tractor hp
        $response = $this->get('/admin/products?sort=hp&direction=desc');
        $response->assertStatus(200);

        // Sort by category
        $response = $this->get('/admin/products?sort=category&direction=asc');
        $response->assertStatus(200);

        // Sort by newest
        $response = $this->get('/admin/products?sort=newest');
        $response->assertStatus(200);
    }

    public function test_admin_can_reorder_product_up_and_down()
    {
        $this->actingAs($this->adminUser);

        $products = Product::orderBy('order', 'asc')->take(2)->get();
        if ($products->count() >= 2) {
            $p1 = $products[0];
            $p2 = $products[1];

            $p1OriginalOrder = $p1->order;
            $p2OriginalOrder = $p2->order;

            // Move p1 down
            $response = $this->post("/admin/products/{$p1->id}/reorder/down");
            $response->assertRedirect();

            $p1->refresh();
            $p2->refresh();

            $this->assertEquals($p2OriginalOrder, $p1->order);
            $this->assertEquals($p1OriginalOrder, $p2->order);

            // Move p1 back up
            $response = $this->post("/admin/products/{$p1->id}/reorder/up");
            $response->assertRedirect();

            $p1->refresh();
            $this->assertEquals($p1OriginalOrder, $p1->order);
        }
    }

    public function test_admin_can_update_product_order_via_ajax()
    {
        $this->actingAs($this->adminUser);

        $products = Product::orderBy('order', 'asc')->take(3)->get();
        if ($products->count() >= 3) {
            $reversedIds = [$products[2]->id, $products[0]->id, $products[1]->id];

            $response = $this->postJson('/admin/products/update-order', [
                'ids' => $reversedIds
            ]);

            $response->assertStatus(200);
            $response->assertJson([
                'success' => true
            ]);

            $this->assertEquals(1, $products[2]->fresh()->order);
            $this->assertEquals(2, $products[0]->fresh()->order);
            $this->assertEquals(3, $products[1]->fresh()->order);
        }
    }
}

