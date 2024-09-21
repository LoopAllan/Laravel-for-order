<?php
namespace App\Http\Controllers\api;

use App\Events\OrderCreated;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\OrderFactoryInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected $orderFactory;

    public function __construct(OrderFactoryInterface $orderFactory) 
    {
        $this->orderFactory = $orderFactory;
    }

    public function store(OrderRequest $request)
    {
        $validated = $request->validated();
        event(new OrderCreated($validated), ["asd"]);
        return response()->noContent(Response::HTTP_OK);
    }

    public function show($id)
    {
        try {
            $model = $this->orderFactory->create_by_id($id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Order not found'], Response::HTTP_NOT_FOUND);
        }

        $orderDetails = $model->where('order_id', $id)->first();

        if (!$orderDetails) {
            return response()->json(['message' => 'Order details not found'], Response::HTTP_NOT_FOUND);
        }

        $currency = strtoupper(explode("_", $model->getTable())[1]);
        $orderDetails->currency = $currency;

        return new OrderResource($orderDetails, Response::HTTP_OK);
    }
}
