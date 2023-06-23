<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_2', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('product_category_id');
            $table->foreign('product_category_id')->references('id')->on('product_category_2');

            $table->string('name', 255)->nullable();
            $table->float('price')->nullable()->unsigned();
            $table->float('disscount_price')->nullable()->unsigned();
            $table->text('description')->nullable(); 
            $table->text('short_description')->nullable(); 
            $table->string('slug', 255)->nullable();
            $table->text('information')->nullable();
            $table->string('image_url', 255)->nullable();
            $table->integer('qty')->nullable()->unsigned();
            $table->integer('status')->default(1);
            $table->float('weight')->nullable()->unsigned();
            $table->string('shipping', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_2');
    }
};
