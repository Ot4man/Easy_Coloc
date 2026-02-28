<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Colocation;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Store a new category for a colocation.
     */
    public function store(Request $request, Colocation $colocation)
    {
        // Simple owner check
        $isOwner = $colocation->users()
            ->where('user_id', auth()->id())
            ->wherePivot('internal_role', 'owner')
            ->wherePivotNull('left_at')
            ->exists();

        if (!$isOwner) {
            abort(403, 'Seul le propriétaire peut ajouter des catégories.');
        }

        $request->validate([
            'name' => 'required|string|max:30',
        ]);

        // Prevent duplicates in same colocation
        $exists = $colocation->categories()
            ->where('name', $request->name)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Cette catégorie existe déjà.');
        }

        $colocation->categories()->create([
            'name' => $request->name,
        ]);

        return back()->with('status', 'Catégorie ajoutée avec succès.');
    }

    /**
     * Remove the category.
     */
    public function destroy(Category $category)
    {
        $colocation = $category->colocation;

        // Owner check
        $isOwner = $colocation->users()
            ->where('user_id', auth()->id())
            ->wherePivot('internal_role', 'owner')
            ->wherePivotNull('left_at')
            ->exists();

        if (!$isOwner) {
            abort(403);
        }

        $category->delete();

        return back()->with('status', 'Catégorie supprimée.');
    }
}
