<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('serial')->nullable()->change();
            $table->string('mac')->nullable()->change();
            $table->string('shipdate')->nullable()->change();
            $table->string('lisence_type')->nullable()->change();
            $table->string('lisence_start')->nullable()->change();
            $table->string('lisence_stop')->nullable()->change();
            $table->string('remoteInfo')->nullable()->change();
            $table->string('dealer')->nullable()->change();
            $table->string('name')->nullable()->change();
            $table->string('furigana')->nullable()->change();
            $table->string('phoneNumnber')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->string('address')->nullable()->change();
            $table->string('NumberOfClient')->nullable()->change();
            $table->string('remarks')->nullable()->change();
            $table->string('other01')->nullable()->change();
            $table->string('other02')->nullable()->change();
            $table->string('other03')->nullable()->change();
            $table->string('other04')->nullable()->change();
            $table->string('other05')->nullable()->change();
            $table->string('other06')->nullable()->change();
            $table->string('other07')->nullable()->change();
            $table->string('other08')->nullable()->change();
            $table->string('other09')->nullable()->change();
            $table->string('other10')->nullable()->change();            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('serial')->nullable(false)->change();
            $table->string('mac')->nullable(false)->change();
            $table->string('shipdate')->nullable(false)->change();
            $table->string('lisence_type')->nullable(false)->change();
            $table->string('lisence_start')->nullable(false)->change();
            $table->string('lisence_stop')->nullable(false)->change();
            $table->string('remoteInfo')->nullable(false)->change();
            $table->string('dealer')->nullable(false)->change();
            $table->string('name')->nullable(false)->change();
            $table->string('furigana')->nullable(false)->change();
            $table->string('phoneNumnber')->nullable(false)->change();
            $table->string('email')->nullable(false)->change();
            $table->string('address')->nullable(false)->change();
            $table->string('NumberOfClient')->nullable(false)->change();
            $table->string('remarks')->nullable(false)->change();
            $table->string('other01')->nullable(false)->change();
            $table->string('other02')->nullable(false)->change();
            $table->string('other03')->nullable(false)->change();
            $table->string('other04')->nullable(false)->change();
            $table->string('other05')->nullable(false)->change();
            $table->string('other06')->nullable(false)->change();
            $table->string('other07')->nullable(false)->change();
            $table->string('other08')->nullable(false)->change();
            $table->string('other09')->nullable(false)->change();
            $table->string('other10')->nullable(false)->change();  
        });
    }
}