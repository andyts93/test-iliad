<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index(Request $request)
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

    public function show(Order $order)
    {
        $order->load(['products']);
        
        return response()->json($order);
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return response()->json(null);
    }

    public function addProduct(Order $order, Product $product)
    {
        if ($order->products()->find($product->id)) {
            return response()->json([
                'error' => 'You can\'t add the same product twice',
            ]);
        }

        $order->products()->attach($product->id);

        return response()->json([
            'message' => 'Product added successfully'
        ]); 
    }

    public function removeProduct(Order $order, Product $product)
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

    public function update(Request $request, Order $order)
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
}
