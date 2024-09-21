<?php
namespace App\Services;

interface CurrencyServiceInterface
{
    /**
     * 获取支持的币别列表
     *
     * @return array
     */
    public function getSupportedCurrencies(): array;

    /**
     * 验证币别是否支持
     *
     * @param string $currency
     * @return bool
     */
    public function isSupportedCurrency(string $currency): bool;
}
