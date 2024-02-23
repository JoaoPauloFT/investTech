<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActionHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('action_histories', function (Blueprint $table) {
            $table->id();
            $table->float('cotation', 6, 2);
            $table->float('pl', 8, 2);
            $table->float('pvp', 7, 2);
            $table->float('psr', 7, 3);
            $table->float('dividend_yield', 9, 2);
            $table->float('price_active', 7, 3);
            $table->float('price_working_capital', 9, 2);
            $table->float('price_ebit', 8, 2);
            $table->float('price_active_circ', 7, 2);
            $table->float('ev_ebit', 7, 2);
            $table->float('ev_ebitda', 7, 2);
            $table->float('margin_ebit', 8, 1);
            $table->float('liquid_margin', 8, 2);
            $table->float('current_liquidation', 5, 2);
            $table->float('roic', 7, 2);
            $table->float('roe', 8, 1);
            $table->float('liquidation_old_months', 13, 2);
            $table->float('net_worth', 14, 2);
            $table->float('gross_debt_patrimony', 5, 2);
            $table->float('recurring_growth', 7, 2);
            $table->unsignedBigInteger('action_id');
            $table->foreign('action_id')->references('id')->on('actions')->onDelete('CASCADE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('action_histories');
    }
}
