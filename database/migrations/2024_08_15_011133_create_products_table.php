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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('purchase_price',10,2);
            $table->decimal('sale_price',10,2);
            $table->integer('stock');
            $table->integer('minimum_stock');
            $table->string('photo')->nullable();
            $table->enum(
                'status',
                ['ACTIVE', 'INACTIVE']
                )->default('ACTIVE');

            $table->string('brand')->nullable();
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->nullable();

            $table->unsignedBigInteger('categories_id');
            $table->unsignedBigInteger('suppliers_id');

            $table->foreign('categories_id')
                ->references('id')
                ->on('categories');

            $table->foreign('suppliers_id')
                ->references('id')
                ->on('suppliers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
