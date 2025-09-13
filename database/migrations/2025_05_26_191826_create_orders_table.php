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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['commande', 'vente']);
            $table->timestamp('creation_time')->useCurrent();
            $table->string('status')->nullable();
            $table->float('total')->default(0);
            $table->string('payment_method')->nullable();
            $table->string('patient_name')->nullable();
            $table->unsignedBigInteger('patient_id')->nullable(); // for commandes
            $table->unsignedBigInteger('raw_id')->nullable(); // original id from commandes/ventes if needed
            $table->timestamps();

            // Optional: Foreign key constraint for patient_id
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
