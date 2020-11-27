<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrenciesRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencies__rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('currency_id');
            $table->date('date');
            $table->unsignedSmallInteger('denomination')->default(1);
            $table->unsignedFloat('value', 8, 4);
            $table->timestamps();
            $table->unique(['currency_id', 'date']);
            $table->foreign('currency_id')
                ->references('id')
                ->on('currencies')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currencies__rates');
    }
}
