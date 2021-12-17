<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_answers', function (Blueprint $table) {
            $table->id();
            $table->integer('video_id');
            $table->integer('user_id');
            $table->integer('parent_id')->default(0)->comment('0=qustion, id=answers');
            $table->text('description');
            $table->integer('is_approved')->default(0)->comment('0=pending, 1=Approve, 2=reject');
            $table->integer('approve_id')->default(0);
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
        Schema::dropIfExists('question_answers');
    }
}
