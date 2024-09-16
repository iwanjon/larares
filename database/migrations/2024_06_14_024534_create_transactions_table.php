<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


// ID         int
// CampaignID int
// UserID     int
// Amount     int
// Status     string
// Code       string
// // PaymentURL string `gorm:"-"`
// PaymentURL string
// User       user.User
// Campaign   campaign.Campaign
// CreatedAt  time.Time
// UpdatedAt  time.Time
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->timestamps(); 
            $table->unsignedBigInteger("user_id")->nullable(True);
            $table->unsignedBigInteger("campaign_id")->nullable(True);
            $table->integer("amount")->nullable(false);
            $table->smallInteger("status")->default(0);
            $table->string("code")->nullable(false);
            $table->string("payment_url")->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
