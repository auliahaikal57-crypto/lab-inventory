<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
    'user_id',          
    'item_id',
    'loan_type',
    'group_size',
    'student_name',
    'npm',
    'borrow_quantity',
    'borrow_condition',
    'borrow_photo',
    'return_quantity',
    'return_condition',
    'return_photo',
    'loan_status'
];



    // Relasi untuk mengambil nama barang
    public function item() {
        return $this->belongsTo(Item::class);
    }
}
