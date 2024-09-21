<?php
namespace Tests\Unit;

use Tests\TestCase;
use App\Models\OrderFactory;
use App\Services\CurrencyServiceInterface;
use Illuminate\Support\Facades\App;
use Mockery;

class OrderFactoryTest extends TestCase
{
    protected $currencyServiceMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->currencyServiceMock = Mockery::mock(CurrencyServiceInterface::class);
        $this->currencyServiceMock->shouldReceive('isSupportedCurrency')
            ->andReturnUsing(function ($currency) {
                return in_array($currency, ['TWD', 'USD', 'JPY', 'RMB', 'MYR']);
            });
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_create_returns_correct_model_instance()
    {
        $factory = new OrderFactory($this->currencyServiceMock);
        $currencies = ['TWD', 'USD', 'JPY', 'RMB', 'MYR'];
        $expectedClasses = [
            'TWD' => 'App\Models\OrderTWD',
            'USD' => 'App\Models\OrderUSD',
            'JPY' => 'App\Models\OrderJPY',
            'RMB' => 'App\Models\OrderRMB',
            'MYR' => 'App\Models\OrderMYR',
        ];

        foreach ($currencies as $currency) {
            $model = $factory->create($currency);
            $this->assertInstanceOf($expectedClasses[$currency], $model);
        }
    }

    public function test_create_throws_exception_for_unsupported_currency()
    {
        $this->currencyServiceMock->shouldReceive('isSupportedCurrency')
            ->with('EUR')
            ->andReturn(false);
        $factory = new OrderFactory($this->currencyServiceMock);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported currency');

        $factory->create('EUR');
    }

    public function test_create_by_id_returns_correct_model_instance()
    {
        $factory = new OrderFactory($this->currencyServiceMock);
        $orderId = 'A0001';
        $currency = 'USD';

        $orderCurrencyMock = Mockery::mock('App\Models\OrderCurrency');
        $orderCurrencyMock->shouldReceive('where')
            ->with('order_id', $orderId)
            ->andReturnSelf();
        $orderCurrencyMock->shouldReceive('first')
            ->andReturn((object)['order_id' => $orderId, 'currency' => $currency]);

        $this->app->instance('App\Models\OrderCurrency', $orderCurrencyMock);

        $model = $factory->create_by_id($orderId);

        $this->assertInstanceOf('App\Models\OrderUSD', $model);
    }

    public function test_create_by_id_throws_exception_when_order_not_found()
    {
        $factory = new OrderFactory($this->currencyServiceMock);
        $orderId = 'INVALID_ID';

        $orderCurrencyMock = Mockery::mock('App\Models\OrderCurrency');
        $orderCurrencyMock->shouldReceive('where')
            ->with('order_id', $orderId)
            ->andReturnSelf();
        $orderCurrencyMock->shouldReceive('first')
            ->andReturn(null);

        $this->app->instance('App\Models\OrderCurrency', $orderCurrencyMock);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Order ' . $orderId . ' not found');

        $factory->create_by_id($orderId);
    }

    public function test_create_currency_returns_correct_model_instance()
    {
        $factory = new OrderFactory($this->currencyServiceMock);

        $model = $factory->create_currency();

        $this->assertInstanceOf('App\Models\OrderCurrency', $model);
    }
}
