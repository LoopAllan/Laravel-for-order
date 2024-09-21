<?php
namespace App\Services;

class CurrencyService implements CurrencyServiceInterface
{
    protected $currencies;

    public function __construct()
    {
        $this->currencies = config('currencies.supported', []);
    }

    public function getSupportedCurrencies(): array
    {
        return $this->currencies;
    }

    public function isSupportedCurrency(string $currency): bool
    {
        return in_array($currency, $this->currencies);
    }
}
