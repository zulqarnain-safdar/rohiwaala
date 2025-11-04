<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Breed;
use App\Models\BullSemen;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function breeds(Request $request)
    {
        $query = Breed::with('bullSemen');
        
        if ($request->has('active_only')) {
            $query->where('is_active', true);
        }

        $breeds = $query->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'breeds' => $breeds
        ]);
    }

    public function semen(Request $request)
    {
        $query = BullSemen::with('breed');
        
        if ($request->has('breed_id')) {
            $query->where('breed_id', $request->breed_id);
        }
        
        if ($request->has('active_only')) {
            $query->where('is_active', true);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $perPage = $request->get('per_page', 15);
        $semen = $query->orderBy('name')->paginate($perPage);

        // Debug logging
        \Log::info('Semen query executed', [
            'total_count' => $semen->total(),
            'per_page' => $perPage,
            'current_page' => $semen->currentPage(),
            'items_count' => $semen->count()
        ]);

        // Transform the data to include quantity_available field
        $semen->getCollection()->transform(function ($item) {
            $item->quantity_available = $item->stock_quantity;
            return $item;
        });

        return response()->json([
            'success' => true,
            'semen' => $semen
        ]);
    }

    public function showSemen(BullSemen $semen)
    {
        $semen->load('breed');
        
        // Add quantity_available field for frontend compatibility
        $semen->quantity_available = $semen->stock_quantity;

        return response()->json([
            'success' => true,
            'semen' => $semen
        ]);
    }
}