<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'depositable_type',
        'depositable_id',
        'transaction_no',
        'deposited_admin_bank_account_id',
        'amount',
        'fee',
        'currency',
        'method',
        'status',
        'remark',
        'admin_remark',
        'deposited_by',
        'updated_by',
    ];

    public function depositable() {
        return $this->morphTo();
    }

    public function files() {
        return $this->morphMany(File::class, 'fileable', 'fileable_type', 'fileable_id');
    }

    public function transaction() {
        return $this->morphOne(Transaction::class, 'transactionable', 'transactionable_type', 'transactionable_id');
    }

    public function depositedBy() {
        return $this->belongsTo(User::class, 'deposited_by', 'id');
    }

    public function depositedAdminBankAccount() {
        return $this->belongsTo(AdminBankAccount::class, 'deposited_admin_bank_account_id', 'id');
    }

    public function depositedToMethod() {
        return $this->belongsTo(TransactionMethod::class, 'deposited_admin_bank_account_id', 'id');
    }

    public function jsons() {
        return $this->morphMany(Json::class, 'jsonable', 'jsonable_type', 'jsonable_id');
    }
}
