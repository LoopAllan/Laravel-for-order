<?php
namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Http\Requests\OrderRequest;
use App\Services\CurrencyServiceInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\App;
use Mockery;

class OrderRequestTest extends TestCase
{
    use RefreshDatabase;
    
    protected $currencyServiceMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->currencyServiceMock = Mockery::mock(CurrencyServiceInterface::class);
        $this->app->instance(CurrencyServiceInterface::class, $this->currencyServiceMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_validation_passes_with_valid_data()
    {
        $this->currencyServiceMock->shouldReceive('isSupportedCurrency')
            ->andReturn(true);

        $request = new OrderRequest($this->currencyServiceMock);

        $data = [
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

        $validator = Validator::make($data, $request->rules());

        $this->assertTrue($validator->passes());
    }

    public function test_validation_fails_with_invalid_currency()
    {
        $this->currencyServiceMock->shouldReceive('isSupportedCurrency')
            ->andReturn(false);

        $request = new OrderRequest($this->currencyServiceMock);

        $data = [
            'id' => 'A0001',
            'name' => 'Test Hotel',
            'address' => [
                'city' => 'Taipei',
                'district' => 'Da-an',
                'street' => 'Fuxing South Road',
            ],
            'price' => 1000,
            'currency' => 'EUR',
        ];

        $validator = Validator::make($data, $request->rules(), $request->messages());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('currency', $validator->errors()->messages());
    }

    public function test_validation_fails_with_missing_fields()
    {
        $this->currencyServiceMock->shouldReceive('isSupportedCurrency')
            ->andReturn(true);

        $request = new OrderRequest($this->currencyServiceMock);

        $data = [
            'name' => 'Test Hotel',
            'address' => [
                'city' => 'Taipei',
                'district' => 'Da-an',
            ],
            'price' => 1000,
            'currency' => 'TWD',
        ];

        $validator = Validator::make($data, $request->rules(), $request->messages());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('id', $validator->errors()->messages());
        $this->assertArrayHasKey('address.street', $validator->errors()->messages());
    }
}
