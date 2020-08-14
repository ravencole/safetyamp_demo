<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJhasTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('jhas', function (Blueprint $table) {
      $table->id();
      $table->unsignedSmallInteger('department_id')->index();
      $table->unsignedSmallInteger('supervisor_id')->index();
      $table->unsignedSmallInteger('prepared_by_id')->index();
      $table->string('task_name');
      $table->longText('ppe')->nullable();
      $table->longText('training')->nullable();
      $table->longText('steps')->nullable();
      $table->dateTime('reviewed_at')->nullable();
      $table->unsignedSmallInteger('reviewed_by_id')->nullable()->index();
      $table->dateTime('approved_at')->nullable();
      $table->unsignedSmallInteger('approved_by_id')->nullable()->index();
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
    Schema::dropIfExists('jhas');
  }
}
