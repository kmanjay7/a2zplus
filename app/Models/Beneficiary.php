<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sender_id',
        'bank_id',
        'bank_name',
        'ifsc_code',
        'account_number',
        'beneficiary_name',
        'beneId',
        'status'
    ];
}
