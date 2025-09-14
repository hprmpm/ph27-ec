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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_name')->after('total_price');
            $table->string('shipping_postal_code')->after('shipping_name');
            $table->string('shipping_city')->after('shipping_postal_code');
            $table->string('shipping_street_address')->after('shipping_city');
            $table->string('shipping_building')->nullable()->after('shipping_street_address');
            $table->string('shipping_phone_number')->after('shipping_building');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_name',
                'shipping_postal_code',
                'shipping_city',
                'shipping_street_address',
                'shipping_building',
                'shipping_phone_number',
            ]);
        });
    }
};