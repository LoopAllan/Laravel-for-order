<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public static $wrap = null;
    public $statusCode;

    public function __construct($resource, $statusCode = 200)
    {
        parent::__construct($resource);
        $this->statusCode = $statusCode;
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return  [
            'id' => $this->order_id,
            'name' => $this->name,
            'address' => [
                'city' => $this->city,
                'district' => $this->district,
                'street' => $this->street,
            ],
            'price' => $this->price,
            'currency' => $this->currency
        ];
    }

    public function withResponse($request, $response)
    {
        $response->setStatusCode($this->statusCode);
    }
}
