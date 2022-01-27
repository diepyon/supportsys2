<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('type');//機種
            $table->string('serial');
            $table->string('mac');
            $table->dateTime('shipdate');//出荷日
            $table->string('lisence_type');
            $table->dateTime('lisence_start');
            $table->dateTime('lisence_stop');
            $table->string('remoteInfo');//外部アクセス等に必要な情報
            $table->string('dealer');
            $table->string('name');
            $table->string('furigana');
            $table->string('phoneNumnber');
            $table->string('email');
            $table->string('address');
            $table->integer('NumberOfClient');//クライアント数
            $table->string('remarks');//備考

            //予備カラム
            $table->string('other01');
            $table->string('other02');
            $table->string('other03');
            $table->string('other04');
            $table->string('other05');
            $table->string('other06');
            $table->string('other07');
            $table->string('other08');
            $table->string('other09');
            $table->string('other10');    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}