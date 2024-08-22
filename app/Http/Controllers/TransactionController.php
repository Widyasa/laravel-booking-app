<?php

namespace App\Http\Controllers;

use App\Http\Requests\transaction\StoreTransactionRequest;
use App\Http\Requests\Transaction\UpdateTransactionRequest;
use App\Http\Requests\Transaction\UploadFileRequest;
use App\Models\Transaction;
use App\Repositories\TransactionRepository;
use App\Utils\ApiResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(
        protected readonly TransactionRepository $transaction
    )
    {}

    public function index() {
        $transactions = $this->transaction->findAll();
        return ApiResponse::success([
            'transactions' => $transactions
        ], 'Fetched', 'Transaction');
    }

    public function store(StoretransactionRequest $request)
    {
        try {
            return $this->transaction->store($request->validated());
        } catch (\Exception $exception) {
            return ApiResponse::error($exception->getMessage(), 'Create', 'Transaction');
        }
    }

    public function show($id)
    {
        $transaction = $this->transaction->findById($id);
        return ApiResponse::success([
            'data' => $transaction
        ], 'Fetched', 'Transaction');
    }

    public function showByUser()
    {
        $transaction = $this->transaction->findAllByCustomer();
        return ApiResponse::success([
            'data' => $transaction
        ], 'Fetched', 'Transaction');
    }
    public function update(UpdateTransactionRequest $request, $id)
    {
        try {
            return $this->transaction->update($request->validated(), $id);
        } catch (\Exception $exception) {
            return ApiResponse::error($exception->getMessage(), 'Update', 'Transaction');
        }
    }
    public function uploadImage(UploadFileRequest $request, $id)
    {
        try {
            return $this->transaction->uploadImage($request->validated(), $id);
        } catch (\Exception $exception) {
            return ApiResponse::error($exception->getMessage(), 'Upload', 'Image Transaction');
        }
    }
}
