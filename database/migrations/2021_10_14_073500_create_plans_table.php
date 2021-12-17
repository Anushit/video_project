<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mt_plans', function (Blueprint $table) {
            $table->id();
            $table->string('title', 250);
            $table->text('description', 250);
            $table->string('amount_type')->nullable()->comment("$,euro,dollar,rupees");
            $table->decimal('total_amount');
            $table->string('plan_mode')->comment("1= weekly, 2=monthly, 3=yearly");
            $table->string('plan_value');
            $table->integer('type')->default(0)->comment('0=paid,1=free	');
            $table->text('image');
            $table->integer('status')->default(0)->comment('0=Active, 1=InActive');
            $table->integer('log_id')->default(0)->comment('Activity Login user Id');
            $table->enum('removed', ['Y', 'N'])->default('N')->comment("Y = true / N = false");
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
        Schema::dropIfExists('plans');
    }
}
