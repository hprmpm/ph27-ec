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
            $table->string('postal_code')->nullable()->after('state');
            $table->string('city')->nullable()->after('postal_code');
            $table->string('street_address')->nullable()->after('city');
            $table->string('building')->nullable()->after('street_address');
            $table->string('phone_number')->nullable()->after('building');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['postal_code', 'city', 'street_address', 'building', 'phone_number']);
        });
    }
};
