<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Clear cart session before each test
        session()->forget('cart');
        
        // Setup fake storage for receipts
        Storage::fake('public');
    }

    protected function addProductToCart(Product $product, int $quantity = 1): void
    {
        for ($i = 0; $i < $quantity; $i++) {
            $this->post(route('cart.add', $product));
        }
    }

    public function test_checkout_creates_order_and_order_items(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
            'price' => 29.99,
        ]);

        $this->addProductToCart($product, 2);

        $response = $this->post(route('checkout.store'), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '555-1234',
            'payment_method' => 'cash',
        ]);

        $response->assertRedirect(route('checkout.success'));

        // Verify order was created
        $this->assertDatabaseHas('orders', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '555-1234',
            'payment_method' => 'cash',
            'total_amount' => 59.98, // 29.99 * 2
            'status' => 'pending',
        ]);

        // Verify order items were created
        $order = Order::first();
        $this->assertCount(1, $order->items);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 29.99,
        ]);
    }

    public function test_checkout_clears_cart_session(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
            'price' => 29.99,
        ]);

        $this->addProductToCart($product);

        // Verify cart has items
        $this->assertNotEmpty(session('cart'));

        $this->post(route('checkout.store'), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '555-1234',
            'payment_method' => 'cash',
        ]);

        // Verify cart is cleared
        $this->assertEmpty(session('cart'));
    }

    public function test_checkout_stores_price_at_purchase(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
            'price' => 29.99,
        ]);

        $this->addProductToCart($product);

        $this->post(route('checkout.store'), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '555-1234',
            'payment_method' => 'cash',
        ]);

        // Change product price after purchase
        $product->update(['price' => 49.99]);

        // Verify order item still has original price
        $orderItem = OrderItem::first();
        $this->assertEquals(29.99, $orderItem->price);
    }

    public function test_receipt_required_when_payment_method_is_receipt(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
            'price' => 29.99,
        ]);

        $this->addProductToCart($product);

        // Try checkout with receipt payment method but no receipt file
        $response = $this->post(route('checkout.store'), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '555-1234',
            'payment_method' => 'receipt',
        ]);

        $response->assertSessionHasErrors('receipt');
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_checkout_with_receipt_upload(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
            'price' => 29.99,
        ]);

        $this->addProductToCart($product);

        $receiptFile = UploadedFile::fake()->image('receipt.jpg');

        $response = $this->post(route('checkout.store'), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '555-1234',
            'payment_method' => 'receipt',
            'receipt' => $receiptFile,
        ]);

        $response->assertRedirect(route('checkout.success'));

        // Verify order was created with receipt
        $order = Order::first();
        $this->assertNotNull($order->receipt_path);
        $this->assertEquals('receipt', $order->payment_method);
        
        // Verify file was stored
        Storage::disk('public')->assertExists($order->receipt_path);
    }

    public function test_cannot_checkout_with_empty_cart(): void
    {
        // Ensure cart is empty
        session()->forget('cart');

        $response = $this->post(route('checkout.store'), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '555-1234',
            'payment_method' => 'cash',
        ]);

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('error', 'Your cart is empty.');
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_checkout_page_redirects_when_cart_empty(): void
    {
        // Ensure cart is empty
        session()->forget('cart');

        $response = $this->get(route('checkout.index'));

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('error', 'Your cart is empty.');
    }

    public function test_checkout_validation_requires_all_fields(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
            'price' => 29.99,
        ]);

        $this->addProductToCart($product);

        $response = $this->post(route('checkout.store'), []);

        $response->assertSessionHasErrors(['name', 'email', 'phone', 'payment_method']);
    }

    public function test_checkout_validates_email_format(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
            'price' => 29.99,
        ]);

        $this->addProductToCart($product);

        $response = $this->post(route('checkout.store'), [
            'name' => 'John Doe',
            'email' => 'not-an-email',
            'phone' => '555-1234',
            'payment_method' => 'cash',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_checkout_validates_payment_method(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
            'price' => 29.99,
        ]);

        $this->addProductToCart($product);

        $response = $this->post(route('checkout.store'), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '555-1234',
            'payment_method' => 'invalid_method',
        ]);

        $response->assertSessionHasErrors('payment_method');
    }

    public function test_checkout_with_multiple_products(): void
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
            'price' => 25.00,
        ]);

        $this->addProductToCart($product1, 2); // 20.00
        $this->addProductToCart($product2, 1); // 25.00

        $response = $this->post(route('checkout.store'), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '555-1234',
            'payment_method' => 'cash',
        ]);

        $response->assertRedirect(route('checkout.success'));

        $order = Order::first();
        $this->assertEquals(45.00, $order->total_amount);
        $this->assertCount(2, $order->items);
    }

    public function test_cannot_checkout_with_inactive_product_in_cart(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
            'price' => 29.99,
        ]);

        $this->addProductToCart($product);

        // Deactivate product after adding to cart
        $product->update(['is_active' => false]);

        $response = $this->post(route('checkout.store'), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '555-1234',
            'payment_method' => 'cash',
        ]);

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_idempotent_checkout_returns_existing_order(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
            'price' => 29.99,
        ]);

        $this->addProductToCart($product);

        // Create an existing order with a checkout token
        $checkoutToken = 'test_checkout_token_12345';
        $existingOrder = Order::create([
            'checkout_token' => $checkoutToken,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '555-1234',
            'payment_method' => 'cash',
            'total_amount' => 29.99,
            'status' => 'pending',
        ]);

        // Attempt to checkout with the same token (double-submit)
        $response = $this->post(route('checkout.store'), [
            'checkout_token' => $checkoutToken,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '555-1234',
            'payment_method' => 'cash',
        ]);

        $response->assertRedirect(route('checkout.success'));
        
        // Should still only have one order (the existing one)
        $this->assertDatabaseCount('orders', 1);
    }

    public function test_checkout_stores_checkout_token(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
            'price' => 29.99,
        ]);

        $this->addProductToCart($product);

        $checkoutToken = 'unique_token_123';

        $response = $this->post(route('checkout.store'), [
            'checkout_token' => $checkoutToken,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '555-1234',
            'payment_method' => 'cash',
        ]);

        $response->assertRedirect(route('checkout.success'));

        $this->assertDatabaseHas('orders', [
            'checkout_token' => $checkoutToken,
        ]);
    }
}
