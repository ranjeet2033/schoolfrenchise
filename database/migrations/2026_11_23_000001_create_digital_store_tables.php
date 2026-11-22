<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Digital Products (PDFs & Videos)
        if (!Schema::hasTable('digital_products')) {
            Schema::create('digital_products', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->unique();
                $table->enum('type', ['pdf', 'video']);
                $table->string('category', 100)->nullable();
                $table->text('short_description')->nullable();
                $table->longText('description')->nullable();
                $table->decimal('price', 10, 2);
                $table->decimal('sale_price', 10, 2)->nullable();
                $table->string('thumbnail')->nullable();
                // PDF specifics
                $table->string('pdf_file_path')->nullable();
                $table->integer('total_pages')->nullable();
                $table->string('sample_pdf_path')->nullable();
                // Video specifics (YouTube Private)
                $table->string('youtube_video_id', 100)->nullable();
                $table->string('youtube_video_url')->nullable();
                $table->string('duration', 50)->nullable();
                $table->integer('max_invites')->default(50);
                $table->integer('current_invites')->default(0);
                // General
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->boolean('is_featured')->default(false);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        // 2. Digital Orders
        if (!Schema::hasTable('digital_orders')) {
            Schema::create('digital_orders', function (Blueprint $table) {
                $table->id();
                $table->string('order_number', 50)->unique();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('customer_name', 191);
                $table->string('customer_email', 191);
                $table->string('customer_mobile', 20);
                $table->string('customer_gmail', 191)->nullable(); // Required for YouTube private video access
                $table->decimal('subtotal', 10, 2);
                $table->decimal('discount', 10, 2)->default(0);
                $table->decimal('total_amount', 10, 2);
                $table->string('currency', 10)->default('INR');
                $table->string('payment_gateway', 50)->default('razorpay');
                $table->string('razorpay_order_id', 100)->nullable();
                $table->string('razorpay_payment_id', 100)->nullable();
                $table->string('razorpay_signature')->nullable();
                $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
                $table->timestamp('paid_at')->nullable();
                $table->timestamps();

                $table->index(['customer_email', 'payment_status']);
                $table->index(['razorpay_order_id']);
            });
        }

        // 3. Digital Order Items
        if (!Schema::hasTable('digital_order_items')) {
            Schema::create('digital_order_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('digital_order_id')->constrained('digital_orders')->onDelete('cascade');
                $table->foreignId('digital_product_id')->constrained('digital_products')->onDelete('cascade');
                $table->string('product_title', 255);
                $table->enum('product_type', ['pdf', 'video']);
                $table->decimal('price', 10, 2);
                $table->timestamps();
            });
        }

        // 4. Digital Product Access (Customer access rights, download tracker & YouTube invitation queue)
        if (!Schema::hasTable('digital_product_access')) {
            Schema::create('digital_product_access', function (Blueprint $table) {
                $table->id();
                $table->foreignId('digital_order_id')->constrained('digital_orders')->onDelete('cascade');
                $table->foreignId('digital_product_id')->constrained('digital_products')->onDelete('cascade');
                $table->string('customer_email', 191);
                $table->string('customer_gmail', 191)->nullable();
                $table->string('access_token', 64)->unique(); // Used for secure download verification
                // YouTube Access Lifecycle
                $table->enum('youtube_invite_status', ['not_applicable', 'pending_invite', 'invited', 'revoked'])->default('not_applicable');
                $table->timestamp('youtube_invited_at')->nullable();
                $table->text('admin_notes')->nullable();
                // PDF Download Lifecycle
                $table->integer('pdf_download_count')->default(0);
                $table->timestamp('last_downloaded_at')->nullable();
                // Status
                $table->enum('status', ['active', 'revoked'])->default('active');
                $table->timestamps();

                $table->index(['customer_email', 'digital_product_id']);
                $table->index(['youtube_invite_status']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('digital_product_access');
        Schema::dropIfExists('digital_order_items');
        Schema::dropIfExists('digital_orders');
        Schema::dropIfExists('digital_products');
    }
};
