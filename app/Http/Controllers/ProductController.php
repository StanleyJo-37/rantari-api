<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    //
    public function getAllProducts(Request $request)
    {
        try {
            $request->validate([
                'category' => 'string',
            ]);

            $products = DB::table('products', 'p')
                            ->select([
                                '*'
                            ])
                            ->when($request->input('category'), function ($query) use ($request) {
                                $query->where($query, $request->input('category'));
                            });

            if ($request->input('per_page')) {
                return response()->json($products->paginate($request->input('per_page')));
            } else {
                return response()->json($products->get());
            }
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

    public function editProduct(Request $request, int $product_id)
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
