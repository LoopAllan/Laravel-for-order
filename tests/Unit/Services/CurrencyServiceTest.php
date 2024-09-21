<?php
namespace Tests\Unit;

use Tests\TestCase;
use App\Services\CurrencyService;
use Illuminate\Support\Facades\Config;

class CurrencyServiceTest extends TestCase
{
    public function test_get_supported_currencies_returns_configured_currencies()
    {
        // 準備：設定配置中的幣別列表
        $currencies = ['TWD', 'USD', 'JPY', 'RMB', 'MYR'];
        Config::set('currencies.supported', $currencies);

        // 執行
        $service = new CurrencyService();
        $result = $service->getSupportedCurrencies();

        // 斷言
        $this->assertEquals($currencies, $result);
    }

    public function test_is_supported_currency_returns_true_for_supported_currency()
    {
        // 準備
        $currencies = ['TWD', 'USD', 'JPY', 'RMB', 'MYR'];
        Config::set('currencies.supported', $currencies);
        $service = new CurrencyService();

        // 執行並斷言
        foreach ($currencies as $currency) {
            $this->assertTrue($service->isSupportedCurrency($currency));
        }
    }

    public function test_is_supported_currency_returns_false_for_unsupported_currency()
    {
        // 準備
        $currencies = ['TWD', 'USD', 'JPY', 'RMB', 'MYR'];
        Config::set('currencies.supported', $currencies);
        $service = new CurrencyService();

        // 執行
        $result = $service->isSupportedCurrency('EUR');

        // 斷言
        $this->assertFalse($result);
    }
}
