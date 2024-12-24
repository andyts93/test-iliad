<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

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
}
