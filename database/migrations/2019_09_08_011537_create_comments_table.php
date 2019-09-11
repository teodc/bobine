<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }

    /**
     * @return void
     */
    public function up()
    {
        Schema::create(
            'comments',
            function (Blueprint $table)
            {
                $table->uuid('id')->primary();
                $table->timestamps();
                $table->uuid('author_id');
                $table->text('body');

                $table->foreign('author_id')
                      ->references('id')
                      ->on('users')
                      ->onDelete('cascade');
            }
        );
    }
}
