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
        Schema::table('users', function (Blueprint $table) {
            //
            $table->text("avatar_filename")->nullable();
            $table->string("role", 50);
            $table->string("occupation", 50);
            $table->text("token")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn('avatar_filename');
            $table->dropColumn('role');
            $table->dropColumn('occupation');
            $table->dropColumn('token');
        });
    }
};

    //     AvatarFileName string
    //     Role           string
    //     Token          sql.NullString
    //     CreatedAt      time.Time
    //     UpdatedAt      time.Time