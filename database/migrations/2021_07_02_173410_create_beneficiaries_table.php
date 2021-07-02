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
            $table->foreignId('user_id')->constrained('users');
            $table->string('beneName')->nullable();
            $table->string('bankName')->nullable();
            $table->string('ifscCode')->nullable();
            $table->string('accountNumber')->nullable();
            $table->bigInteger('beneId')->nullable();
            $table->bigInteger('txnId')->nullable();
            $table->bigInteger('clientId')->nullable();
            $table->string('bankRefNo')->nullable();
            $table->string('account_status')->nullable();
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
