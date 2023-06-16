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
        Schema::create('product', function (Blueprint $table) { //Schema::create --> tạo table mới, Schema::table --> update table
            $table->id();
            $table->string('name', 255)->nullable();
            $table->float('price')->nullable()->unsigned(); // unsigned : số dương
            $table->text('description')->nullable();
            $table->string('image_url', 255)->nullable();
            //Bước 1: tạo field
            //$table->bigInteger('product_category_id')->unsigned();
            $table->unsignedBigInteger('product_category_id');
            //Bước 2: chỉ định field là khóa ngoại
            $table->foreign('product_category_id')->references('id')->on('product_category');


            $table->timestamps(); // #-->(create_at + update_at)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
