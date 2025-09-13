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
        Schema::create('ligne_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id'); // links to orders table
            $table->enum('type', ['commande', 'vente']);
            $table->unsignedBigInteger('raw_id'); // original ligne_commande or ligne_vente id
            $table->unsignedBigInteger('medicament_id');
            $table->integer('quantite')->nullable();
            $table->float('montant')->nullable();
            $table->string('posologie')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('medicament_id')->references('id')->on('medicaments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ligne_orders');
    }
};
