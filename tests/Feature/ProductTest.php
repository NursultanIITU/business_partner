<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Tests\TestCase;

class ProductTest extends TestCase
{
    public function test_get_all_products_successfully(): void
    {
        Product::factory(5)->create();
        $response = $this->get('api/v1/products');

        $response
            ->assertStatus(200)
            ->assertJsonStructure(['data' => [
                '*' => [
                    'id',
                    'name',
                    'description',
                    'image',
                    'category' => [
                        'id',
                        'name'
                    ]
                ]
            ]]);
    }

    public function test_product_by_id()
    {
        $product = Product::factory()->create();
        $response = $this->get('api/v1/products/product/'. $product->id);

        $response
            ->assertStatus(200)
            ->assertJsonStructure(['data' => [
                'id',
                'name',
                'description',
                'image',
                'category' => [
                    'id',
                    'name'
                ]
            ]]);
    }

    public function test_get_all_my_carts()
    {
        $user = User::factory()->create();
        Cart::factory(5)->create([
            'user_id' => $user->id
        ]);
        $response = $this->actingAs($user)->get('api/v1/products/my-cart');

        $response
            ->assertStatus(200)
            ->assertJsonStructure(['data' => [
                '*' => [
                    'id',
                    'quantity',
                    'amount',
                    'product' => [
                        'id',
                        'name',
                        'description',
                        'image'
                    ]
                ]
            ]]);
    }

    public function test_add_to_cart_successfully()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $response = $this->actingAs($user)->post('api/v1/products/product/'.$product->id.'/add-to-cart', [
            'quantity' => 1
        ]);

        $response->assertStatus(200);
    }

    public function test_remove_from_cart()
    {
        $user = User::factory()->create();
        $cart = Cart::factory(['user_id' => $user->id])->create();
        $response = $this->actingAs($user)->delete('api/v1/products/my-cart/'.$cart->id);

        $response->assertStatus(200);
    }
}
