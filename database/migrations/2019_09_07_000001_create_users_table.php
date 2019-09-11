<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }

    /**
     * @return void
     */
    public function up()
    {
        Schema::create(
            'users',
            function (Blueprint $table)
            {
                $table->uuid('id')->primary();
                $table->timestamps();
                //$table->softDeletes();
                $table->string('name')->index();
                $table->string('email')->unique()->nullable();
                $table->string('password')->nullable();
                $table->string('token', 100)->unique()->nullable();
                $table->boolean('is_enabled')->default(true)->index();
            }
        );
    }
}
