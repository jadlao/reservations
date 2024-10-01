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
        if (!Schema::hasTable('user_invitations')) {
            Schema::create('user_invitations', function (Blueprint $table) {
                $table->id();
                $table->string('email');
                $table->string('token', 36)->unique()->nullable();
                $table->timestamp('registered_at')->nullable();
                $table->unsignedBigInteger('company_id');
                $table->unsignedBigInteger('role_id');
                $table->timestamps();

                $table->unique(['email', 'company_id']);
            });

            // Add foreign key constraints after table creation
            Schema::table('user_invitations', function (Blueprint $table) {
                $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
                $table->foreign('role_id')->references('id')->on('roles')->cascadeOnDelete();
            });
        }
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_invitations');
    }
};
