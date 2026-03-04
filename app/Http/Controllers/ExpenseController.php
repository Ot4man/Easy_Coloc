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
            abort(403, 'You are banned');
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

        $expenses = Expense::with(['category', 'user'])
            ->where('colocation_id', $activeColocation->id)
            ->where('is_paid', false)
            ->latest('date')
            ->get();

        return view('expenses.index', compact('expenses', 'activeColocation'));
    }

    public function settlement()
    {
        [$user, $activeColocation] = $this->getAuthorizedUserAndColocation();

        $members = $activeColocation->users()->wherePivotNull('left_at')->get();
        $unpaidExpenses = Expense::with(['user'])
            ->where('colocation_id', $activeColocation->id)
            ->where('is_paid', false)
            ->get();

        $settlements = [];
        $memberCount = $members->count();

        if ($memberCount > 1) {
            foreach ($unpaidExpenses as $expense) {
                // Skip expenses if the creator no longer exists
                if (!$expense->user) {
                    continue;
                }

                $share = $expense->amount / $memberCount;
                foreach ($members as $member) {
                    if ($member->id !== $expense->user_id) {
                        $settlements[] = (object) [
                            'expense' => $expense,
                            'from' => $member->name,
                            'to' => $expense->user->name,
                            'amount' => $share,
                            'can_mark_paid' => $user->id === $expense->user_id
                        ];
                    }
                }
            }
        }

        return view('expenses.settlement', compact('settlements', 'activeColocation'));
    }

    public function markAsPaid(Expense $expense)
    {
        [$user, $activeColocation] = $this->getAuthorizedUserAndColocation();

        if ($expense->colocation_id !== $activeColocation->id || $expense->user_id !== $user->id) {
            abort(403);
        }

        $expense->update(['is_paid' => true]);

        return redirect()->back()->with('success', 'La dépense a été marquée comme payée.');
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
    }
}
