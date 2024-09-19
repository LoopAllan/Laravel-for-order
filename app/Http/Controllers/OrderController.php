<?php
namespace App\Http\Controllers;

use App\Events\OrderCreated;
use App\Http\Requests\StoreOrderRequest;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request)
    {
        event(new OrderCreated($request->validated()));
        return response()->json(['message' => 'Order received'], Response::HTTP_OK);
    }

    public function show($id)
    {
        // 根據不同的貨幣查詢對應的資料表
        // 此處省略具體實作
    }
}
