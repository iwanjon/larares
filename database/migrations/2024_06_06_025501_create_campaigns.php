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
        Schema::create('campaigns', function (Blueprint $table) {
            // $table->engine = 'InnoDB';
            $table->id()->autoIncrement;
            $table->timestamps();
            $table->string("name",100);
            $table->string("short_description",100);
            $table->text("description")->nullable(false);
            $table->string("perks",50);
            $table->integer("backer_count")->default(0);
            $table->integer("goal_amount")->default(0)->nullable(false);
            $table->integer("current_amount")->default(0);
            $table->string("slug",100);
            $table->unsignedBigInteger("user_id")->nullable(True);
            // $table->foreign("user_id")->references("id")->on("users");
        });

        Schema::table('campaigns', function($table) {
            $table->foreign('user_id')->references('id')->on('users');
        });

    }
// type Campaign struct {
// 	ID               int
// 	UserID           int
// 	Name             string
// 	ShortDescription string
// 	Description      string
// 	Perks            string
// 	BackerCount      int
// 	GoalAmount       int
// 	CurrentAmount    int
// 	Slug             string
// 	CreatedAt        time.Time
// 	UpdatedAt        time.Time
// 	CampaignImages   []CampaignImage
// 	User             user.User
// }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};




