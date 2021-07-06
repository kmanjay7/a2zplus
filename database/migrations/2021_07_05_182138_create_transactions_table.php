<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('senders');
            $table->foreignId('beneficiary_id')->constrained('beneficiaries');
            $table->string('beneficiary_name');
            $table->string('account_number');
            $table->string('ifsc_code');
            $table->float('amount');
            $table->float('debit_amount');
            $table->string('channel');
            $table->string('beneId');
            $table->string('clientId');
            $table->string('txnId')->nullable();
            $table->string('bankRefNo')->nullable();
            $table->float('trans_charge')->nullable();
            $table->string('trans_status')->nullable();
            $table->string('trans_type')->default('transfer');
            $table->timestamps();
            $table->boolean('status')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
