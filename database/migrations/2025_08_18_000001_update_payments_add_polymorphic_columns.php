<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::table('payments', function (Blueprint $table) {

      if (!Schema::hasColumn('payments', 'gateway_order_id')) {
        $table->string('gateway_order_id')->nullable()->index();
      }
      if (!Schema::hasColumn('payments', 'gateway_charge_id')) {
        $table->string('gateway_charge_id')->nullable()->index();
      }

      $addPayableIndex = false;
      if (!Schema::hasColumn('payments', 'payable_type')) {
        $table->string('payable_type')->nullable();
        $addPayableIndex = true;
      }
      if (!Schema::hasColumn('payments', 'payable_id')) {
        $table->unsignedBigInteger('payable_id')->nullable();
        $addPayableIndex = true;
      }

      if ($addPayableIndex) {
        $table->index(['payable_type', 'payable_id']);
      }
    });
  }

  public function down(): void
  {
    Schema::table('payments', function (Blueprint $table) {

      if (Schema::hasColumn('payments', 'payable_type')) {
        $table->dropColumn('payable_type');
      }
      if (Schema::hasColumn('payments', 'payable_id')) {
        $table->dropColumn('payable_id');
      }
    });
  }
};
