<?php
namespace App\Http\Controllers\api;

use App\Events\OrderCreated;
use App\Http\Requests\OrderRequest;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    public function store(OrderRequest $request)
    {
        event(new OrderCreated($request->validated()));
        return response()->json(['message' => 'Order received'], Response::HTTP_OK);
    }

    public function show($id)
    {
        // 根據不同的貨幣查詢對應的資料表
        // 此處省略具體實作
        echo $id;
    }
}
