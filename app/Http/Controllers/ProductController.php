<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $products = Product::query()
            ->with('category')
            ->when($request->category_id, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when($request->search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('short_description', 'like', "%{$search}%");
                });
            })
            ->when($request->is_featured !== null, function ($query) use ($request) {
                $query->where(
                    'is_featured',
                    filter_var($request->is_featured, FILTER_VALIDATE_BOOLEAN)
                );
            })
            ->when($request->is_active !== null, function ($query) use ($request) {
                $query->where(
                    'is_active',
                    filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN)
                );
            })
            ->latest()
            ->paginate($request->integer('per_page', 10));

        return ApiResponse::paginated(
            ProductResource::collection($products),
            'Products retrieved successfully'
        );
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);
        $validated['is_active'] = $validated['is_active'] ?? true;
        $validated['is_featured'] = $validated['is_featured'] ?? false;

        $product = Product::create($validated);

        return ApiResponse::success(
            new ProductResource($product->load('category')),
            'Product created successfully',
            201
        );
    }

    public function show(Product $product): JsonResponse
    {
        $product->load('category');

        return ApiResponse::success(
            new ProductResource($product),
            'Product retrieved successfully'
        );
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $validated = $request->validated();

        if (isset($validated['name']) && empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $product->update($validated);

        return ApiResponse::success(
            new ProductResource($product->load('category')),
            'Product updated successfully'
        );
    }

    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return ApiResponse::success(
            null,
            'Product deleted successfully'
        );
    }
}
