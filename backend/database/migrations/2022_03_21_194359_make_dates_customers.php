<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeDatesCustomers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->timestamp('shipdate')->nullable();
            $table->timestamp('lisence_start')->nullable();
            $table->timestamp('lisence_stop')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
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
            $table->dropColumn('shipdate');
            $table->dropColumn('lisence_start');
            $table->dropColumn('lisence_stop');
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
    }
}