<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $qb = Product::query();

        if ($request->query->has('q')) {
            $qb->where('name', 'like', '%' . $request->input('q') . '%')
                ->limit(10);
        }

        return response()->json($qb->get());
    }
}
