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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('username')->nullable();
            $table->string('email')->nullable();
            $table->unsignedInteger('division_id')->nullable();
            $table->unsignedInteger('city_id')->nullable();
            $table->unsignedInteger('township_id')->nullable();
            $table->string('password')->nullable();
            $table->longText('address')->nullable();
            $table->unsignedInteger('user_type')->nullable()->default(1);
            $table->string('phone_no')->nullable();
            $table->unsignedInteger('point')->nullable()->default(0);
            $table->string('profile')->nullable();
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
        Schema::dropIfExists('users');
    }
};
