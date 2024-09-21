<?php
namespace App\Http\Requests;

use App\Services\CurrencyServiceInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

class OrderRequest extends FormRequest
{
    protected $redirect = '';
    protected $currencyService;

    public function __construct(CurrencyServiceInterface $currencyService)
    {
        parent::__construct();
        $this->currencyService = $currencyService;
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => 'required|string|unique:orders_currency,order_id',
            'name' => 'required|string',
            'address.city' => 'required|string',
            'address.district' => 'required|string',
            'address.street' => 'required|string',
            'price' => 'required|numeric',
            'currency' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!$this->currencyService->isSupportedCurrency($value)) {
                        $fail('The selected currency is invalid.');
                    }
                },
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'id.required' => 'The field id is must.',
            'name.required' => 'The field name id must.',
            'address.city.required' => 'The field address.city id must.',
            'address.district.required' => 'The field address.district id must.',
            'address.street.required' => 'The field address.street id must.',
            'price.required' => 'The field price id must.',
            'currency.required' => 'The field currency id must.',
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        // 自訂 JSON 錯誤回應
        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422));
    }

}
