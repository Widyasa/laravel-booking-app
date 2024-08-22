<?php
namespace App\Repositories;
use App\Models\Car;
use App\Models\Customer;
use App\Models\Transaction;
use App\Utils\ApiResponse;
use App\Utils\UploadFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionRepository {
    public function __construct(
        protected readonly Transaction $transaction,
        protected readonly Car $car,
        protected readonly Customer $customer,
        protected readonly UploadFile $uploadFile
    )
    {}
    public function findAll()
    {
        return $this->transaction->with(['transaction_car', 'transaction_user'])->latest()->paginate(10)->withQueryString();
    }
    public function findAllByCustomer()
    {
        return $this->transaction->where('customer_id', Auth::user()->id)->with(['transaction_car', 'transaction_user'])->latest()->paginate(10)->withQueryString();
    }
    public function findById(int $transaction_id)
    {
        return $this->transaction->where('id', $transaction_id)
            ->with(['transaction_car', 'transaction_user'])
            ->first();
    }
    public function store($request): \Illuminate\Http\JsonResponse
    {

        DB::beginTransaction();
        try {
            if (Auth::user()->role == 'customer') {
                $customer = $this->customer->where('user_id',Auth::user()->id)->first();
                $request['customer_id'] = $customer->id;
            }
            $request['status'] = 'unpaid';
            // Cek apakah ada transaksi yang overlap untuk mobil yang sama
            $overlap = Transaction::where('car_id', $request['car_id'])
                ->where('status', 'paid')
                ->where(function ($query) use ($request) {
                    $query->whereBetween('start_date', [$request['start_date'], $request['end_date']])
                        ->orWhereBetween('end_date', [$request['start_date'], $request['end_date']]);
                })
                ->exists();

            if ($overlap) {
                return response()->json(['message' => 'Car already Booked.'], 422);
            } else {
                $transaction = $this->transaction->create($request);
                DB::commit();
                return ApiResponse::success([$transaction], 'Create', 'Transaction');
            }

        } catch ( \Exception $e) {
            logger($e->getMessage());
            DB::rollBack();

            throw $e;
        }
    }


    public function update($request, $id): \Illuminate\Http\JsonResponse
    {
        try {
            DB::beginTransaction();
            // Find the transaction and validate its status
            $transaction = Transaction::findOrFail($id);
            if ($transaction->status !== 'finish' && Auth::user()->role === 'admin') {
                // Check for overlapping transactions
                $overlap = Transaction::where('car_id', $request['car_id'])
                    ->where('status', 'paid')
                    ->where(function ($query) use ($request) {
                        $query->whereBetween('start_date', [$request['start_date'], $request['end_date']])
                            ->orWhereBetween('end_date', [$request['start_date'], $request['end_date']]);
                    })
                    ->exists();

                if ($overlap) {
                    return response()->json(['message' => 'Car already booked'], 422);
                }

                $transaction->update($request);
                DB::commit();

                return ApiResponse::success([$transaction], 'Update', 'Transaction');
            } else {
                return response()->json(['message' => 'Transaction cannot be edited'], 422);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            logger($e->getMessage());
            throw $e;
        }
    }

    public function uploadImage($request, $id): \Illuminate\Http\JsonResponse
    {
        try {
            DB::beginTransaction();
            $transaction = Transaction::findOrFail($id);
            if (isset($request["payment_proof"])) {
                $this->uploadFile->deleteExistFile($transaction->payment_proof);

                $filename = $this->uploadFile->uploadSingleFile($request['payment_proof'], 'transactions/payment_proof/');
                $request['payment_proof'] = $filename;
            }
                $transaction->update($request);
                DB::commit();

                return ApiResponse::success([$transaction], 'Update', 'Payment Transaction');
        } catch (\Exception $e) {
            DB::rollBack();
            logger($e->getMessage());
            throw $e;
        }
    }
}
