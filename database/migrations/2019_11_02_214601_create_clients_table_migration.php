<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTableMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('id');            
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone',12)->nullable();
            $table->text('address')->nullable();
            $table->string('state',30)->nullable();
            $table->string('gstin',15)->nullable();
            $table->timestamp('last_purchase')->nullable();
            $table->unsignedInteger('total_purchases')->default(0);
            $table->unsignedDecimal('total_paid')->default(0.00);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
