<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('equations', function (Blueprint $table) {
            $table->id();
            $table->string('expression'); // ✅ only add this line
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('equations');
    }
};
