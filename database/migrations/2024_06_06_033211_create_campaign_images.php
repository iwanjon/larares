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
        Schema::create('campaign_images', function (Blueprint $table) {
            // $table->engine = 'InnoDB';
            $table->id()->autoIncrement;
            $table->timestamps();
            $table->text("filename");
            $table->boolean("is_primary")->nullable(false)->default(false);
            $table->unsignedBigInteger("campaign_id")->nullable(True);
            // $table->foreign("campaign_id")->references("id")->on("campaigns");
        });

        Schema::table('campaign_images', function($table) {
            $table->foreign('campaign_id')->references('id')->on('campaigns');
        });
    }

    // type CampaignImage struct {
// 	ID         int
// 	CampaignID int
// 	FileName   string
// 	IsPrimary  int
// 	CreatedAt  time.Time
// 	UpdatedAt  time.Time
// }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_images');
    }
};
