<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['1', '2'])->default('1')->comment("1 = Admin / 2 = Customer");
            $table->string('username',160)->unique();
            $table->string('email',250)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('slug');
            $table->string('image', 150)->nullable();
            $table->string('phone',50)->nullable();
            $table->string('address', 150)->nullable();
            $table->text('settings')->nullable();
            $table->string('password');
            $table->integer('role_id')->nullable();
            $table->rememberToken()->nullable();
            $table->integer('status')->default(0)->comment('0=Active, 1=Inaction');
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
        Schema::dropIfExists('users');
    }
}
