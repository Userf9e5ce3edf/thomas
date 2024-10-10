<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStepsTable extends Migration
{
    public function up()
    {
        Schema::create('steps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('intervention_id'); // Ensure this is unsignedBigInteger
            $table->foreign('intervention_id')->references('id')->on('interventions')->onDelete('cascade');
            $table->string('name');
            $table->text('description');
            $table->integer('duration');
            $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('steps');
    }
};
