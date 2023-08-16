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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('branch_id')->nullable();
            $table->string('voucher_no')->nullable();
            $table->date('date')->nullable();
            $table->decimal('total_amount')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('table_id')->nullable();
            $table->integer('user_qty')->nullable();
            $table->unsignedInteger('order_type')->nullable()->default(1);
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
        Schema::dropIfExists('sales');
    }
};
