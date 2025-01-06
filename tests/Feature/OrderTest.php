<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testIndexReturnsPaginatedOrders()
    {
        Order::factory()->count(20)->create();

        $response = $this->getJson('/api/v1/orders');

        $response->assertOk();
        $response->assertJsonStructure([
            'data',
            'links',
        ]);
    }

    public function testShowReturnsOrder()
    {
        $order = Order::factory()->create();
        
        $response = $this->getJson('/api/v1/orders/' . $order->id);

        $response->assertOk();
        $response->assertJson([
            'id' => $order->id,
        ]);
    }

    public function testCreateOrder()
    {
        $products = Product::factory(3)->create();

        $response = $this->postJson('/api/v1/orders', [
            'name' => 'Test',
            'description' => 'test',
            'products' => $products,
        ]);

        $response->assertCreated();
        $response->assertJson([
            'message' => 'Order created successfully',
        ]);

        $this->assertDatabaseHas('orders', [
            'name' => 'Test',
            'description' => 'test',
        ]);
    }

    public function testCreateOrderNonExistingProducts()
    {
        $response = $this->postJson('/api/v1/orders', [
            'name' => 'Test',
            'description' => 'test',
            'products' => [['id' => 123456], ['id' => 234567]]
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['products.0.id', 'products.1.id']);
    }

    public function testCreateOrderNonMissingProducts()
    {
        $response = $this->postJson('/api/v1/orders', [
            'name' => 'Test',
            'description' => 'test',
            'products' => []
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['products']);
    }

    public function testCreateOrderNonMissingFields()
    {
        $response = $this->postJson('/api/v1/orders', [
            'name' => '',
            'description' => '',
            'products' => []
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'description', 'products']);
    }

    public function testDestroyDeletesOrder()
    {
        $order = Order::factory()->create();

        $response = $this->deleteJson('/api/v1/orders/' . $order->id);

        $response->assertNoContent();
        $this->assertDatabaseMissing('orders', [
            'id' => $order->id,
        ]);
    }

    public function testAddProductToOrder()
    {
        $order = Order::factory()->create();
        $product = Product::factory()->create();

        $response = $this->postJson('/api/v1/orders/' . $order->id . '/products/' . $product->id);

        $response->assertOk();
        $response->assertJson([
            'message' => 'Product added successfully',
        ]);
        $this->assertDatabaseHas('order_product', [
            'order_id' => $order->id,
            'product_id' => $product->id,
        ]);
    }

    public function testCannotAddDuplicateProductToOrder()
    {
        $order = Order::factory()->create();
        $product = Product::factory()->create();
        $order->products()->attach($product->id);

        $response = $this->postJson('/api/v1/orders/' . $order->id . '/products/' . $product->id);

        $response->assertStatus(400);
        $response->assertJson([
            'error' => "You can't add the same product twice",
        ]);
    }

    public function testRemoveProductFromOrder()
    {
        $order = Order::factory()->create();
        $product = Product::factory()->create();
        $product2 = Product::factory()->create();
        $order->products()->attach([$product->id, $product2->id]);

        $response = $this->deleteJson('/api/v1/orders/' . $order->id . '/products/' . $product->id);

        $response->assertOk();
        $response->assertJson([
            'message' => 'Product removed successfully',
        ]);
        $this->assertDatabaseMissing('order_product', [
            'order_id' => $order->id,
            'product_id' => $product->id,
        ]);
    }

    public function testCannotRemoveLastProductFromOrder()
    {
        $order = Order::factory()->create();
        $product = Product::factory()->create();
        $order->products()->attach($product->id);

        $response = $this->deleteJson('/api/v1/orders/' . $order->id . '/products/' . $product->id);

        $response->assertStatus(400);
        $response->assertJson([
            'error' => 'An order must have at least 1 product',
        ]);
    }

    public function testUpdateOrder()
    {
        $order = Order::factory()->create([
            'name' => 'Old Name',
            'description' => 'Old Description',
        ]);

        $response = $this->putJson('/api/v1/orders/' . $order->id, [
            'name' => 'New Name',
            'description' => 'New Description',
        ]);

        $response->assertOk();
        $response->assertJson([
            'message' => 'Order updated',
        ]);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'name' => 'New Name',
            'description' => 'New Description',
        ]);
    }

    public function testUpdateOrderValidationFails()
    {
        $order = Order::factory()->create([
            'name' => 'Valid Name',
            'description' => 'Valid Description',
        ]);

        $response = $this->putJson('/api/v1/orders/' . $order->id, [
            'name' => '',
            'description' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'description']);
    }

}