<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * @OA\Get(
     *     path="/orders",
     *     tags={"Orders"},
     *     summary="Get list of orders",
     *     @OA\Response(response="200", description="List of orders", @OA\JsonContent(
     *         @OA\Property(property="current_page", type="integer", example=1),
     *         @OA\Property(property="total", type="integer", example=100),
     *         @OA\Property(property="per_page", type="integer", example=15),
     *         @OA\Property(property="last_page", type="integer", example=7),
     *         @OA\Property(property="from", type="integer", example=1),
     *         @OA\Property(property="to", type="integer", example=15),
     *         @OA\Property(
     *             property="data",
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Order"))
     *         ),
     *         
     *     )),
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $qb = Order::query()
            ->with('products')
            ->withSum('products', 'price');

        if ($request->query->has('date')) {
            $qb->whereDate('date', $request->input('date'));
        }
        if ($request->query->has('name')) {
            $qb->where('name', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->query->has('description')) {
            $qb->where('description', 'like', '%' . $request->input('description') . '%');
        }
        
        return response()->json($qb->paginate(15));
    }
    

    /**
     * @OA\Get(
     *   path="/order/{order}",
     *   tags={"Orders"},
     *   summary="Get a single order",
     *   @OA\Parameter(
     *     name="order",
     *     in="path",
     *     description="Order ID",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Order details retrieved successfuly",
     *     @OA\JsonContent(ref="#/components/schemas/Order")
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="Order not found",
     *     @OA\JsonContent(ref="#/components/schemas/NotFoundError")
     *   )
     * )
     */
    public function show(Order $order): JsonResponse
    {
        $order->load(['products']);
        
        return response()->json($order);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'products' => 'required|array',
            'products.*.id' => 'exists:products,id',
        ]);

        try {
            $order = Order::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'date' => Carbon::now(),
            ]);

            $order->products()->attach(
                array_map(function($el) {
                    return $el['id'];
                }, $validated['products'])
            );

            return response()->json([
                'message' => 'Order created successfully',
                'order' => $order,
            ], 201);
        }
        catch (Exception $exception) {
            Log::critical('Unexpected error during order creation', [
                'exception' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ]);
            return response()->json([
                'error' => 'An error occured',
            ], 500);
        }
    }

     /**
     * @OA\Put(
     *     path="/orders/{order}",
     *     summary="Update order",
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         name="order",
     *         in="path",
     *         description="Order ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="New name", maxLength=255),
     *             @OA\Property(property="description", type="string", example="New description")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Order updated")
     *         )
     *     ),
     *     @OA\Response(
     *       response=404,
     *       description="Order not found",
     *       @OA\JsonContent(ref="#/components/schemas/NotFoundError")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="array", @OA\Items(type="string", example="Field name is required"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="An error occured")
     *         )
     *     )
     * )
     */
    public function update(Request $request, Order $order): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
        ]);

        try {
            $order->update($validated);
            return response()->json([
                'message' => 'Order updated',
            ]);
        } catch (\Exception $e) {
            Log::critical('Unexpected error during order update.', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'error' => 'An error occured',
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *   path="/order/{order}",
     *   tags={"Orders"},
     *   summary="Delete a single order",
     *   @OA\Parameter(
     *     name="order",
     *     in="path",
     *     description="Order ID",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Response(
     *     response=204,
     *     description="Order deleted successfuly",
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="Order not found",
     *     @OA\JsonContent(ref="#/components/schemas/NotFoundError")
     *   )
     * )
     */
    public function destroy(Order $order): JsonResponse
    {
        $order->delete();

        return response()->json(null, 204);
    }

    /**
     * @OA\Put(
     *   path="/order/{order}/products/{product}",
     *   tags={"Orders"},
     *   summary="Add a product to an order",
     *   @OA\Parameter(
     *     name="order",
     *     in="path",
     *     description="Order ID",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Parameter(
     *     name="product",
     *     in="path",
     *     description="Product ID",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Product added successfuly",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="message", type="string", example="Product added successfully")
     *     )
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="Order or product not found",
     *     @OA\JsonContent(ref="#/components/schemas/NotFoundError")
     *   ),
     *   @OA\Response(
     *     response=400,
     *     description="Duplicated product",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="error", type="string", example="You can't add the same product twice")
     *     )
     *   )
     * )
     */
    public function addProduct(Order $order, Product $product): JsonResponse
    {
        if ($order->products()->find($product->id)) {
            return response()->json([
                'error' => 'You can\'t add the same product twice',
            ], 400);
        }

        $order->products()->attach($product->id);

        return response()->json([
            'message' => 'Product added successfully'
        ]); 
    }

    /**
     * @OA\Delete(
     *   path="/order/{order}/products/{product}",
     *   tags={"Orders"},
     *   summary="Remove a product from an order",
     *   @OA\Parameter(
     *     name="order",
     *     in="path",
     *     description="Order ID",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Parameter(
     *     name="product",
     *     in="path",
     *     description="Product ID",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Product removed successfuly",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="message", type="string", example="Product removed successfully")
     *     )
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="Order or product not found",
     *     @OA\JsonContent(ref="#/components/schemas/NotFoundError")
     *   ),
     *   @OA\Response(
     *     response=400,
     *     description="Productless order",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="error", type="string", example="An order must have at least 1 product")
     *     )
     *   )
     * )
     */
    public function removeProduct(Order $order, Product $product): JsonResponse
    {
        if ($order->products()->count() <= 1) {
            return response()->json([
                'error' => 'An order must have at least 1 product',
            ], 400);
        }
        $order->products()->detach($product->id);

        return response()->json([
            'message' => 'Product removed successfully'
        ]);
    }
}
