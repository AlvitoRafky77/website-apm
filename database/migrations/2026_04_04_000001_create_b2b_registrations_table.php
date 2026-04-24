<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('b2b_registrations', function (Blueprint $table) {
            $table->id();

            // Company Info
            $table->string('company_name');
            $table->string('company_type')->default('PT'); // PT, CV, UD, Firma, dll
            $table->string('npwp')->nullable();
            $table->text('company_address');
            $table->string('company_city')->nullable();
            $table->string('company_province')->nullable();
            $table->string('company_postal_code')->nullable();

            // PIC (Person in Charge)
            $table->string('pic_name');
            $table->string('pic_position')->nullable();
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('password'); // hashed

            // Documents
            $table->string('company_profile_path')->nullable();
            $table->string('siup_path')->nullable(); // Surat Izin Usaha Perdagangan
            $table->string('akta_path')->nullable();  // Akta Pendirian

            // Status
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();

            // Linked user after approval
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('b2b_registrations');
    }
};
