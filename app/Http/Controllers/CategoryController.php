<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::query()
            ->withCount('products')
            ->latest()
            ->paginate(10);

        return ApiResponse::paginated(
            CategoryResource::collection($categories),
            'Categories retrieved successfully'
        );
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);
        $validated['is_active'] = $validated['is_active'] ?? true;

        $category = Category::create($validated);

        return ApiResponse::success(
            new CategoryResource($category),
            'Category created successfully',
            201
        );
    }

    public function show(Category $category): JsonResponse
    {
        $category->load(['products']);

        return ApiResponse::success(
            new CategoryResource($category),
            'Category retrieved successfully'
        );
    }

    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        $validated = $request->validated();

        if (isset($validated['name']) && empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $category->update($validated);

        return ApiResponse::success(
            new CategoryResource($category),
            'Category updated successfully'
        );
    }

    public function destroy(Category $category): JsonResponse
    {
        $category->delete();

        return ApiResponse::success(
            null,
            'Category deleted successfully'
        );
    }
}
