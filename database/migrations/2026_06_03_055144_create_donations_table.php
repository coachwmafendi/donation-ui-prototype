<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('profile_id')->constrained()->cascadeOnDelete();
            $table->integer('amount_cents');
            $table->string('currency', 3)->default('SGD');
            $table->integer('converted_amount_cents')->nullable();
            $table->string('converted_currency', 3)->nullable();
            $table->string('status')->default('pending'); // pending, succeeded, failed, refunded
            $table->string('campaign');
            $table->string('designation')->nullable();
            $table->string('frequency')->default('one-time'); // one-time, monthly, weekly, yearly
            $table->timestamp('donation_date');
            $table->timestamp('success_date')->nullable();
            $table->integer('payment_amount_cents')->nullable();
            $table->integer('processing_fee_cents')->nullable();
            $table->integer('net_amount_cents')->nullable();
            $table->string('payment_method')->nullable(); // credit_card, paypal, bank_transfer
            $table->string('source')->nullable(); // donation_form, api, import
            $table->string('device')->nullable(); // desktop, mobile, tablet
            $table->string('donor_type')->nullable(); // new, returning
            $table->text('tribute_info')->nullable();
            $table->text('comment')->nullable();
            $table->string('utm_source')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->boolean('receipt_email_sent')->default(false);
            $table->boolean('thank_you_email_sent')->default(false);
            $table->text('custom_fields')->nullable(); // JSON
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
