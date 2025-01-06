<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="Test Iliad",
 *     version="1.0.0",
 *     description="API for managing orders and products.",
 *     @OA\Contact(
 *         email="andrea.castellini5@gmail.com"
 *     )
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8080/api/v1",
 *     description="Local development server"
 * )
 * 
 * @OA\Schema(
 *   schema="Order",
 *   title="Sample schema for orders",
 *   @OA\Property(property="id", type="integer", example=7),
 *     @OA\Property(property="name", type="string", example="Ione Amato"),
 *     @OA\Property(property="description", type="string", example="Maxime id illo est nobis..."),
 *     @OA\Property(property="date", type="string", format="date-time", example="2010-03-26 12:09:40"),
 *     @OA\Property(property="products_sum_price", type="string", example="182800"),
 *     @OA\Property(
 *         property="products",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="id", type="integer", example=3),
 *             @OA\Property(property="name", type="string", example="accusantium"),
 *             @OA\Property(property="price", type="integer", example=1828),
 *             @OA\Property(
 *                 property="pivot",
 *                 type="object",
 *                 @OA\Property(property="order_id", type="integer", example=7),
 *                 @OA\Property(property="product_id", type="integer", example=3)
 *             )
 *         )
 *     )
 * )
 */
abstract class Controller
{
    //
}
