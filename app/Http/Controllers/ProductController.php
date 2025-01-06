<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * @OA\Get(
     *     path="/products",
     *     tags={"Products"},
     *     summary="Get list of products",
     *     @OA\Parameter(
     *       name="q",
     *       in="query",
     *       description="Name filter",
     *       @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="List of products", @OA\JsonContent(
     *       type="array",
     *       @OA\Items(ref="#/components/schemas/Product")
     *     )),
     * )
     */
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
