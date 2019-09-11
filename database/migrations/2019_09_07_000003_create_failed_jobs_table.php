<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFailedJobsTable extends Migration
{
    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('failed_jobs');
    }

    /**
     * @return void
     */
    public function up()
    {
        Schema::create(
            'failed_jobs',
            function (Blueprint $table)
            {
                $table->bigIncrements('id');
                $table->timestamp('failed_at')->useCurrent();
                $table->string('connection')->index();
                $table->string('queue')->index();
                $table->longText('payload')->nullable();
                $table->longText('exception')->nullable();
            }
        );
    }
}
