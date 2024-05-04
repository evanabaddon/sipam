<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function pemakaian()
    {
        return $this->belongsTo(Pemakaian::class, 'pemakaian_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
