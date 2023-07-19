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
        Schema::create('product', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->nullable();
            $table->string('slug', 255)->nullable();
            $table->float('price')->nullable()->unsigned();
            $table->float('discount_price')->nullable()->unsigned();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->text('information')->nullable();
            $table->integer('qty')->unsigned();
            $table->string('shipping', 255)->nullable();
            $table->float('weight')->nullable()->unsigned();
            $table->string('image_url', 255)->nullable();
            $table->boolean('status')->default(1);

            //Buoc 1 - tao field
            // $table->bigInteger('product_category_id')->unsigned();
            $table->unsignedBigInteger('product_category_id')->nullable();
            //Buoc 2 - chi dinh field do la khoa ngoai
            $table->foreign('product_category_id')->references('id')->on('product_category')->onDelete('cascade');

            $table->timestamps(); //created_at updated_at
            $table->softDeletes(); //deleted_at
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

