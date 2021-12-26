<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('type');
            $table->string('dealer')->nullable();
            $table->string('questioner')->nullable();
            $table->string('phoneNumber')->nullable();
            $table->string('customer')->nullable();
            $table->string('kinds')->default('設定');
            $table->LONGTEXT('question');
            $table->LONGTEXT('answer:');
            $table->string('remote')->default('なし');
            $table->string('authorizer')->nullable();
            $table->string('satisfaction')->default('満足');        
            $table->integer('operator_id');   
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inquiries');
    }
}
