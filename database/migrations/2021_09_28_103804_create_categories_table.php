<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mt_categories', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->nullable();
            $table->string('slug', 250);
            $table->string('title', 250);
            $table->text('image');
            $table->text('banner_image')->nullable();
            $table->text('content');
            $table->string('meta_title', 250)->nullable();
            $table->string('meta_keyword', 250)->nullable();
            $table->text('meta_description')->nullable();
            $table->enum('is_featured', ['1', '2'])->default('1')->comment("1 = Yes / 2 = No");
            $table->integer('sort_order')->default(1);
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
        Schema::dropIfExists('mt_categories');
    }
}
