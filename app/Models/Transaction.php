<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sender_id',
        'beneficiary_id',
        'beneficiary_name',
        'account_number',
        'ifsc_code',
        'amount',
        'debit_amount',
        'channel',
        'beneId',
        'clientId',
        'txnId',
        'bankRefNo',
        'trans_status',
        'status'
    ];

    /**
     * Get the sender for the post.
     */
    public function sender()
    {
        return $this->belongsTo('App\Models\Sender', 'sender_id', 'id');
    }

    /**
     * Get the beneficiary for the post.
     */
    public function beneficiary()
    {
        return $this->belongsTo('App\Models\Beneficiary', 'beneficiary_id', 'id');
    }
}
