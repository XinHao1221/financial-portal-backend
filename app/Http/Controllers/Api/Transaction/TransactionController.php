<?php

namespace App\Http\Controllers\Api\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $validatedData = $request->validate([
            'start_date' => ['required', 'date_format:Y-m-d H:i:s'],
            'end_date' => ['required', 'date_format:Y-m-d H:i:s']
        ]);

        $transactions = Transaction::where('datetime', '>=', $validatedData['start_date'])
            ->where('datetime', '<=', $validatedData['end_date'])->get();

        return $this->commonJsonResponse(TransactionResource::collection($transactions));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'is_income' => ['required', 'boolean'],
            'amount' => ['required', 'numeric'],
            'description' => ['string'],
            'account_id' => 'required', 'exists:accounts,id,user_id,' . auth()->user()->id,
            'category_id' => ['required', 'exists:categories,id,user_id,' . auth()->user()->id],
            'datetime' => ['required', 'date_format:Y-m-d H:i:s']
        ]);

        $transaction = Transaction::create([
            'is_income' => $validatedData['is_income'],
            'amount' => $validatedData['amount'],
            'description' => Arr::get($validatedData, 'description'),
            'account_id' => $validatedData['account_id'],
            'category_id' => $validatedData['category_id'],
            'datetime' => $validatedData['datetime'],
            'user_id' => auth()->user()->id
        ]);

        return $this->commonJsonResponse(new TransactionResource($transaction), 'Record Created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaction = Transaction::where('user_id', auth()->user()->id)->findOrFail($id);

        return $this->commonJsonResponse(new TransactionResource($transaction));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }

    public function transactionSummary(Request $request)
    {

        $validatedData = $request->validate([
            'start_date' => ['required', 'date_format:Y-m-d H:i:s'],
            'end_date' => ['required', 'date_format:Y-m-d H:i:s']
        ]);

        $transactions = Transaction::where('datetime', '>=', $validatedData['start_date'])
            ->where('datetime', '<=', $validatedData['end_date'])->get();

        // Form response
        $data = [
            "income_total" => $transactions->where('is_income', 1)->sum('amount'),
            'expenses_total' => $transactions->where('is_income', 0)->sum('amount'),
            'total_transaction' => $transactions->count(),
        ];

        return $this->commonJsonResponse($data);
    }
}
