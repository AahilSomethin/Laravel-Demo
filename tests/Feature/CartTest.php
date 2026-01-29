<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Clear cart session before each test
        session()->forget('cart');
    }

    public function test_can_add_item_to_cart(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
            'price' => 29.99,
        ]);

        $response = $this->post(route('cart.add', $product));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        // Verify cart contains the product
        $cart = session('cart');
        $this->assertArrayHasKey($product->id, $cart);
        $this->assertEquals(1, $cart[$product->id]);
    }

    public function test_can_update_cart_quantity(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
            'price' => 29.99,
        ]);

        // First add item to cart
        $this->post(route('cart.add', $product));

        // Update quantity
        $response = $this->patch(route('cart.update', $product), [
            'quantity' => 5,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        // Verify quantity was updated
        $cart = session('cart');
        $this->assertEquals(5, $cart[$product->id]);
    }

    public function test_can_remove_item_from_cart(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
            'price' => 29.99,
        ]);

        // First add item to cart
        $this->post(route('cart.add', $product));
        
        // Verify it's in the cart
        $this->assertArrayHasKey($product->id, session('cart'));

        // Remove item
        $response = $this->delete(route('cart.remove', $product));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        // Verify cart no longer contains the product
        $cart = session('cart', []);
        $this->assertArrayNotHasKey($product->id, $cart);
    }

    public function test_cart_totals_are_calculated_correctly(): void
    {
        $category = Category::factory()->create();
        
        $product1 = Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
            'price' => 10.00,
        ]);
        
        $product2 = Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
            'price' => 25.50,
        ]);

        // Add products to cart
        $this->post(route('cart.add', $product1));
        $this->post(route('cart.add', $product1)); // Add twice (qty: 2)
        $this->post(route('cart.add', $product2));

        // Get cart details via service
        $cartService = app(CartService::class);
        $cartDetails = $cartService->getCartWithDetails();

        // Expected total: (10.00 * 2) + (25.50 * 1) = 45.50
        $this->assertEquals(45.50, $cartDetails['total']);
        $this->assertCount(2, $cartDetails['items']);
    }

    public function test_cannot_add_inactive_product_to_cart(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => false,
            'price' => 29.99,
        ]);

        $response = $this->post(route('cart.add', $product));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'This product is not available.');
        
        // Verify cart is empty
        $cart = session('cart', []);
        $this->assertEmpty($cart);
    }

    public function test_cart_quantity_cannot_exceed_maximum(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
            'price' => 29.99,
        ]);

        // Add item to cart
        $this->post(route('cart.add', $product));

        // Try to update to quantity above max (99)
        $response = $this->patch(route('cart.update', $product), [
            'quantity' => 150,
        ]);

        // Should fail validation
        $response->assertSessionHasErrors('quantity');
    }

    public function test_cart_page_displays_items(): void
    {
        // Skip Vite assets for testing
        $this->withoutVite();

        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
            'name' => 'Test Product',
            'price' => 29.99,
        ]);

        // Add item to cart
        $this->post(route('cart.add', $product));

        // Visit cart page
        $response = $this->get(route('cart.index'));

        $response->assertStatus(200);
        $response->assertSee('Test Product');
        $response->assertSee('29.99');
    }
}
