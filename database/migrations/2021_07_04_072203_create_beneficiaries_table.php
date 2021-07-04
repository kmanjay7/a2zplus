<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBeneficiariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('senders');
            $table->bigInteger('bank_id');
            $table->string('bank_name');
            $table->string('ifsc_code');
            $table->string('account_number');
            $table->string('beneficiary_name');
            $table->bigInteger('beneId')->nullable();
            $table->bigInteger('txnId')->nullable();
            $table->bigInteger('clientId')->nullable();
            $table->string('bankRefNo')->nullable();
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
        Schema::dropIfExists('beneficiaries');
    }
}
