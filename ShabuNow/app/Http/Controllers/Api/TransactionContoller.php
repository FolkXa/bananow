<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TransactionContoller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public static function add(string $order_id, string $user_id, string $before, string $after)
    {
        Transaction::create([
            'order_id' => $order_id,
            'user_id' => $user_id? $user_id: null,
            'before_status' => $before,
            'after_status' => $after,
            'change_date' => Carbon::now()->timezone('Asia/Bangkok')->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
