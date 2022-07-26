<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Permissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->integer('pg_id')->default(0);
            $table->string('display_name', 50)->nullable();
            $table->string('icon', 30)->nullable();
            $table->smallInteger('sequence')->nullable();
            $table->string('created_name', 50)->nullable();
            $table->string('updated_name', 50)->nullable();
            $table->string('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
    }
}
