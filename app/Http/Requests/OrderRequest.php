<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{

    public function rules()
    {
        return [
            'id' => 'required|string|unique:orders_twd,order_id',
            'name' => 'required|string',
            'address.city' => 'required|string',
            'address.district' => 'required|string',
            'address.street' => 'required|string',
            'price' => 'required|numeric',
            'currency' => 'required|string|in:TWD,USD,JPY,RMB,MYR',
        ];
    }
}
