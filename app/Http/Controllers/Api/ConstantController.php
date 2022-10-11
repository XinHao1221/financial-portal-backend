<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Category;
use Illuminate\Http\Request;

class ConstantController extends Controller
{
    public function __invoke(Request $request)
    {
        return response()->json([
            'data' => [
                'accounts' => $this->getAccounts(),
                'categories' => $this->getCategories()
            ]
        ]);
    }

    public function getAccounts()
    {
        return Account::where('user_id', auth()->user()->id)->get(['id', 'name']);
    }

    public function getCategories()
    {
        return Category::where('user_id', auth()->user()->id)->get(['id', 'name']);
    }
}
