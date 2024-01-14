<?php

use App\Enums\User\Blood;
use App\Enums\User\Gender;
use App\Enums\User\Role;
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
            $table->string('name');
            $table->enum('role', Role::values())->default(Role::Student);
            $table->enum('gender', Gender::values())->default(Gender::Male);
            $table->enum('blood', Blood::values())->nullable();
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('emergency_phone')->nullable();
            $table->string('image')->nullable();
            $table->string('address')->nullable();
            $table->string('field_of_study')->nullable();
            $table->string('password')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
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
