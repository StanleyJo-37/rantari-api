<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    //
    public function getAllOrders(Request $request)
    {
        try {
            // $user = $request->user();
            $user_id = $request->user_id;

            // if (!isset($user)) {
            //     return response()->json("Silakan login kembali.", 403);
            // }

            $orders = [];

            
            if ($user->role === "seller") {
                
                
            } else {
                $orders = DB::table('orders as o')
                            ->select([
                                'o.order_code',
                                DB::raw("JSON_AGG(
                                    JSON_BUILD_OBJECT(
                                        'name', p.name,
                                        'scientific_name', p.scientific_name,
                                        'type', p.type,
                                        'weight_bought', p.weight_per_unit_g * o.unit_bought,
                                        'total_price', l.price_per_unit * o.unit_bought,
                                        'thumbnail', a.path
                                    )
                                ) AS order_details")
                            ])
                            ->join('users as u', 'u.id', '=', 'o.buyer_id')
                            ->join('listings as l', function ($join) {
                                $join->on('l.id', '=', 'o.listing_id');
                            })
                            ->leftJoin('products as p', 'p.id', '=', 'l.product_id')
                            ->leftJoin('asset_relations as ar', function ($join) {
                                $join->on('ar.model_id', '=', 'p.id')
                                    ->where('ar.model_type', '=', Product::class);
                            })
                            ->leftJoin('assets as a', 'a.id', '=', 'ar.asset_id')
                            ->groupBy('o.order_code')
                            ->where('o.buyer_id', '=', $user_id)
                            ->get();

                            
            }
            foreach ($orders as &$o) {
                $o->order_details = json_decode($o->order_details);
            }

            return response()->json($orders);

        } catch (Exception $e) {
            return response()->json(
                [
                    'message' => 'Error fetching products. Please try again later.',
                    'error' => $e->getMessage(),
                ],
                500
            );
        }
    }

    public function getProductDetail(int $product_id)
    {
        try {

            $product = Product::find($product_id)->first();

            return response()->json($product);
        } catch (Exception $e) {
            return response()->json(
                [
                    'message' => 'Error fetching product detail. Please try again later.',
                    'error' => $e->getMessage(),
                ],
                500
            );
        }
    }

    public function createProduct(Request $request)
    {
        try {
            $request->validate([
                'name' => 'string|requied',
                'scientific_name' => 'string',
                'description' => 'string',
                'type' => 'string',
                'weight_per_unit' => 'integer|required',
            ]);

            DB::beginTransaction();

            $product = Product::create([
                'name' => $request->input('name'),
                'scientific_name' => $request->input('scientific_name'),
                'description' => $request->input('description'),
                'type' => $request->input('type'),
                'weight_per_unit_g' => $request->input('weight_per_unit_g'),
            ]);

            DB::commit();

            return response()->json($product);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(
                [
                    'message' => 'Error creating product. Please try again later.',
                    'error' => $e->getMessage(),
                ],
                500
            );
        }
    }

    public function editTodo(Request $request, int $product_id)
    {
        try {
            $request->validate([
                'name' => 'string|requied',
                'scientific_name' => 'string',
                'description' => 'string',
                'type' => 'string',
                'weight_per_unit' => 'integer|required',
            ]);

            DB::beginTransaction();

            $product = Product::firstOrFail($product_id)->update([
                'name' => $request->input('name'),
                'scientific_name' => $request->input('scientific_name'),
                'description' => $request->input('description'),
                'type' => $request->input('type'),
                'weight_per_unit_g' => $request->input('weight_per_unit_g'),
            ]);

            DB::commit();

            return response()->json($product);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(
                [
                    'message' => 'Error creating product. Please try again later.',
                    'error' => $e->getMessage(),
                ],
                500
            );
        }
    }

    public function deleteTodo(int $product_id)
    {
        try {
            DB::beginTransaction();

            $product = Product::firstOrFail($product_id);
            $product->delete();

            DB::commit();

            return response()->json($product);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(
                [
                    'message' => 'Error deleting product. Please try again later.',
                    'error' => $e->getMessage(),
                ],
                500
            );
        }
    }
}
