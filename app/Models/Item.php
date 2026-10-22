<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    // Mengizinkan kolom-kolom ini diisi data baru dari formulir admin
    protected $fillable = [
    'name',
    'brand',
    'type',
    'quantity',
    'unit',
    'prefix',      // TAMBAHKAN INI ⚡
    'abbr',        // TAMBAHKAN INI ⚡
    'serial_code',
    'status',
    'foto',

];


}
