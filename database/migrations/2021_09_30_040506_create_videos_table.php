<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mt_videos', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('category_id');
            $table->string('slug', 250);
            $table->string('name', 250);
            $table->text('image');
            $table->text('video')->nullable();
            $table->text('description');
            $table->integer('sort_order')->default(1);
            $table->integer('status')->default(0)->comment('0=Active, 1=InActive');
            $table->enum('is_featured', ['1', '2'])->default('2')->comment("1 = Yes / 2 = No");
            $table->integer('is_approved')->default(0)->comment('0=pending, 1=Approve, 2=reject');
            $table->integer('approve_id')->default(0);
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
        Schema::dropIfExists('mt_videos');
    }
}
