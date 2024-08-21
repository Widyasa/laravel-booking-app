<?php
namespace App\Repositories;
use App\Models\Car;
use App\Models\Customer;
use App\Models\Transaction;

class TransactionRepository {
    public function __construct(
        protected readonly Transaction $transaction,
        protected readonly Car $car,
        protected readonly Customer $customer,
    )
    {}
}
