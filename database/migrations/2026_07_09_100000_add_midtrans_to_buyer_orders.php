<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('buyer_orders', function (Blueprint $table) {
            $table->string('snap_token')->nullable()->after('unique_amount');
            $table->string('transaction_id')->nullable()->after('snap_token');
            $table->string('payment_type')->nullable()->after('transaction_id');
            $table->json('midtrans_response')->nullable()->after('payment_type');
        });
    }

    public function down(): void
    {
        Schema::table('buyer_orders', function (Blueprint $table) {
            $table->dropColumn(['snap_token', 'transaction_id', 'payment_type', 'midtrans_response']);
        });
    }
};
