<?php
namespace Tests\Unit;

use Tests\TestCase;
use App\Listeners\ProcessOrder;
use App\Events\OrderCreated;
use App\Models\OrderFactory;
use Illuminate\Support\Facades\Log;
use Mockery;

class ProcessOrderTest extends TestCase
{
    
    public function test_handle_processes_order_successfully()
    {
        $orderData = [
            'id' => 'A0001',
            'name' => 'Test Hotel',
            'address' => [
                'city' => 'Taipei',
                'district' => 'Da-an',
                'street' => 'Fuxing South Road',
            ],
            'price' => 1000,
            'currency' => 'TWD',
        ];
        $event = new OrderCreated($orderData);

        $orderModelMock = Mockery::mock();
        $orderModelMock->shouldReceive('create')->once();

        $orderCurrencyModelMock = Mockery::mock();
        $orderCurrencyModelMock->shouldReceive('create')->once();

        $orderFactoryMock = Mockery::mock(OrderFactory::class);
        $orderFactoryMock->shouldReceive('create')
            ->with('TWD')
            ->andReturn($orderModelMock);
        $orderFactoryMock->shouldReceive('create_currency')
            ->andReturn($orderCurrencyModelMock);

        $listener = new ProcessOrder($orderFactoryMock);
        $listener->handle($event);

    }

    public function test_handle_logs_error_when_exception_occurs()
    {
        $orderData = [
            'id' => 'A0001',
            'name' => 'Test Hotel',
            'address' => [
                'city' => 'Taipei',
                'district' => 'Da-an',
                'street' => 'Fuxing South Road',
            ],
            'price' => 1000,
            'currency' => 'TWD',
        ];
        $event = new OrderCreated($orderData);

        $orderFactoryMock = Mockery::mock(OrderFactory::class);
        $orderFactoryMock->shouldReceive('create')
            ->andThrow(new \Exception('Database error'));

        Log::shouldReceive('error')
            ->once()
            ->with('Failed to process order', Mockery::on(function ($data) use ($orderData) {
                return $data['order_id'] === $orderData['id'] &&
                       $data['currency'] === $orderData['currency'] &&
                       $data['error'] === 'Database error';
            }));

        $listener = new ProcessOrder($orderFactoryMock);
        $listener->handle($event);

    }
}
