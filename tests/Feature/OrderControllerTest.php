<?php
namespace Tests\Feature;

use \App\Models\OrderUSD;
use Mockery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();
        Mockery::close();
    }

    public function test_successful_order_creation()
    {
        $payload = [
            'id' => 'A0000001',
            'name' => 'Melody Holiday Inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road'
            ],
            'price' => 2050,
            'currency' => 'TWD'
        ];

        $response = $this->postJson('/orders', $payload);

        $response->assertStatus(200);
        
        $this->assertDatabaseHas('orders_twd', [
            'order_id' => 'A0000001',
            'name' => 'Melody Holiday Inn'
        ]);

        $this->assertDatabaseHas('orders_currency', [
            'order_id' => 'A0000001',
            'currency' => 'TWD'
        ]);
    }

    public function test_order_creation_with_invalid_currency()
    {
        $payload = [
            'id' => 'A0000002',
            'name' => 'Test Hotel',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road'
            ],
            'price' => '2050',
            'currency' => 'AAA'
        ];

        $response = $this->postJson('/orders', $payload);

        $response->assertStatus(422);
    }

    public function test_successful_order_query()
    {
        // 創建訂單
        $payload = [
            'id' => 'A0000003',
            'name' => 'Test Hotel',
            'address' => [
                'city' => 'kaohsiung-city',
                'district' => 'sanmin-district',
                'street' => 'minzu-1st-road'
            ],
            'price' => '3000',
            'currency' => 'USD'
        ];

        $this->postJson('/orders', $payload);

        // 查詢訂單
        $response = $this->getJson('/orders/A0000003');

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => 'A0000003',
                     'name' => 'Test Hotel',
                     'address' => [
                         'city' => 'kaohsiung-city',
                         'district' => 'sanmin-district',
                         'street' => 'minzu-1st-road'
                     ],
                     'price' => '3000',
                     'currency' => 'USD'
                 ]);
    }

    public function test_order_query_with_detail_not_found()
    {
        // 創建訂單
        $payload = [
            'id' => 'A0000003',
            'name' => 'Test Hotel',
            'address' => [
                'city' => 'kaohsiung-city',
                'district' => 'sanmin-district',
                'street' => 'minzu-1st-road'
            ],
            'price' => '3000',
            'currency' => 'USD'
        ];

        $this->postJson('/orders', $payload);

        OrderUSD::where('order_id', 'A0000003')->delete();

        // 查詢訂單
        $response = $this->getJson('/orders/A0000003');

        $response->assertStatus(404)
                 ->assertJson(['message' => 'Order details not found']);
    }

    public function test_order_query_with_invalid_id()
    {
        $response = $this->getJson('/orders/INVALID_ID');

        $response->assertStatus(404)
                 ->assertJson(['message' => 'Order not found']);
    }
}