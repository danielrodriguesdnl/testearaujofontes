<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesSectorsTable extends Migration
{
    public function up()
    {
        Schema::create('companies_sectors', function (Blueprint $table) {
            $table->foreignId('company_id')->constrained();
            $table->foreignId('sector_id')->constrained();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('companies_sectors');
    }
}
