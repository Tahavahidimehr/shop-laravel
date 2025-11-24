<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTransactionRequest;
use App\Http\Requests\Admin\UpdateTransactionRequest;
use App\Models\Transaction;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $transactions = Transaction::latest()->paginate(10);
            return $this->successResponse($transactions, 'لیست تراکنش‌ها با موفقیت دریافت شد');
        } catch (\Exception $e) {
            Log::error('Error fetching transactions: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت لیست تراکنش‌ها');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request): JsonResponse
    {
        try {
            $transaction = Transaction::create($request->validated());
            return $this->successResponse($transaction, 'تراکنش با موفقیت ثبت شد', 201);
        } catch (\Exception $e) {
            Log::error('Error creating transaction: ' . $e->getMessage());
            return $this->errorResponse('خطا در ثبت تراکنش');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction): JsonResponse
    {
        try {
            return $this->successResponse($transaction, 'تراکنش با موفقیت دریافت شد');
        } catch (\Exception $e) {
            Log::error('Error showing transaction: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت اطلاعات تراکنش');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction): JsonResponse
    {
        try {
            $transaction->update($request->validated());
            return $this->successResponse($transaction, 'تراکنش با موفقیت بروزرسانی شد');
        } catch (\Exception $e) {
            Log::error('Error updating transaction: ' . $e->getMessage());
            return $this->errorResponse('خطا در بروزرسانی تراکنش');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction): JsonResponse
    {
        try {
            $transaction->delete();
            return $this->successResponse(null, 'تراکنش با موفقیت حذف شد');
        } catch (\Exception $e) {
            Log::error('Error deleting transaction: ' . $e->getMessage());
            return $this->errorResponse('خطا در حذف تراکنش');
        }
    }
}
