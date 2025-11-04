<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CowType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CowTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $cowTypes = CowType::orderBy('name')->get();
            
            return response()->json([
                'success' => true,
                'data' => $cowTypes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch cow types',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:cow_types,name',
                'description' => 'nullable|string',
                'is_active' => 'boolean'
            ]);

            $cowType = CowType::create([
                'name' => $request->name,
                'description' => $request->description,
                'is_active' => $request->is_active ?? true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cow type created successfully',
                'data' => $cowType
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create cow type',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $cowType = CowType::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $cowType
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cow type not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $cowType = CowType::findOrFail($id);
            
            $request->validate([
                'name' => 'required|string|max:255|unique:cow_types,name,' . $id,
                'description' => 'nullable|string',
                'is_active' => 'boolean'
            ]);

            $cowType->update([
                'name' => $request->name,
                'description' => $request->description,
                'is_active' => $request->is_active ?? $cowType->is_active
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cow type updated successfully',
                'data' => $cowType
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update cow type',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $cowType = CowType::findOrFail($id);
            $cowType->delete();

            return response()->json([
                'success' => true,
                'message' => 'Cow type deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete cow type',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get active cow types for public use (e.g., checkout page)
     */
    public function active(): JsonResponse
    {
        try {
            $cowTypes = CowType::active()->orderBy('name')->get();
            
            return response()->json([
                'success' => true,
                'data' => $cowTypes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch active cow types',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
