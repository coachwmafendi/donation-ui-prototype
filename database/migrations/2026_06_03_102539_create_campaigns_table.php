<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('public_id')->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('status')->default('active');
            $table->unsignedBigInteger('goal_amount_cents')->default(0);
            $table->unsignedBigInteger('raised_amount_cents')->default(0);
            $table->unsignedInteger('donor_count')->default(0);
            $table->text('description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('currency', 3)->default('USD');
            $table->string('cover_image')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
