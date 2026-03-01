<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Category;
use App\Models\Colocation;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;

class ExpenseController extends Controller
{
    /**
     * Helper to validate user authorization rules.
     */
    protected function getAuthorizedUserAndColocation()
    {
        $user = auth()->user();

        // Check if user is authenticated and not banned
        if (!$user || $user->is_banned) {
            abort(403);
        }

        // Check if user has an active colocation
        $activeColocation = $user->colocations()
            ->wherePivotNull('left_at')
            ->where('colocations.status', 'active')
            ->latest('colocations.created_at')
            ->first();

        if (!$activeColocation) {
            abort(403);
        }

        return [$user, $activeColocation];
    }

    public function index()
    {
        [$user, $activeColocation] = $this->getAuthorizedUserAndColocation();

        $expenses = Expense::with(['category'])
            ->where('colocation_id', $activeColocation->id)
            ->latest('date')
            ->get();

        return view('expenses.index', compact('expenses', 'activeColocation'));
    }

    public function create()
    {
        [$user, $activeColocation] = $this->getAuthorizedUserAndColocation();

        $categories = Category::where('colocation_id', $activeColocation->id)->get();

        return view('expenses.create', compact('categories', 'activeColocation'));
    }

    public function store(StoreExpenseRequest $request)
    {
        [$user, $activeColocation] = $this->getAuthorizedUserAndColocation();

        $category = Category::find($request->category_id);

        // Category must belong to user's colocation
        if (!$category || $category->colocation_id !== $activeColocation->id) {
            abort(403);
        }

       $expense = Expense::create([
            'title' => $request->title,
            'amount' => $request->amount,
            'date' => $request->date,
            'category_id' => $request->category_id,
            'colocation_id' => $activeColocation->id,
            'user_id' => $user->id,
        ]);

        $this->recalculateBalances($activeColocation);

        return redirect()->route('expenses.index')->with('success', 'Expense created successfully.');
    }

    public function edit(Expense $expense)
    {
        [$user, $activeColocation] = $this->getAuthorizedUserAndColocation();

        // Expense must belong to user's colocation
        if ($expense->colocation_id !== $activeColocation->id) {
            abort(403);
        }

        $categories = Category::where('colocation_id', $activeColocation->id)->get();

        return view('expenses.edit', compact('expense', 'categories', 'activeColocation'));
    }

    public function update(UpdateExpenseRequest $request, Expense $expense)
    {
        [$user, $activeColocation] = $this->getAuthorizedUserAndColocation();

        // Expense must belong to user's colocation
        if ($expense->colocation_id !== $activeColocation->id) {
            abort(403);
        }

        $category = Category::find($request->category_id);

        // Category must belong to user's colocation
        if (!$category || $category->colocation_id !== $activeColocation->id) {
            abort(403);
        }

        $expense->update([
            'title' => $request->title,
            'amount' => $request->amount,
            'date' => $request->date,
            'category_id' => $request->category_id,
        ]);

        $this->recalculateBalances($activeColocation);

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        [$user, $activeColocation] = $this->getAuthorizedUserAndColocation();

        // Expense must belong to user's colocation
        if ($expense->colocation_id !== $activeColocation->id) {
            abort(403);
        }

        $expense->delete();

        $this->recalculateBalances($activeColocation);

        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }

    protected function recalculateBalances(Colocation $colocation)
    {
        // Placeholder for balance recalculation logic
    }
}
