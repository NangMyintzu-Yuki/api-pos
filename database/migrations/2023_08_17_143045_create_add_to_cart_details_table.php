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
        Schema::create('add_to_cart_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('add_to_cart_id')->nullable();
            $table->unsignedInteger('product_id')->nullable();
            $table->integer('quantity')->nullable();
            $table->decimal('amount')->nullable();
            $table->unsignedInteger('status')->nullable()->default(1);
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('add_to_cart_details');
    }
};
