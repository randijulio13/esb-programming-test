<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->integer('customer_id')->references('id')->on('customers')->onDelete('restrict');
            $table->date('issue_date');
            $table->date('due_date');
            $table->float('subtotal');
            $table->float('tax');
            $table->float('total');
            $table->float('payments')->nullable();
            $table->float('amount_due')->default(0.00);
            $table->boolean('is_paid')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
};
