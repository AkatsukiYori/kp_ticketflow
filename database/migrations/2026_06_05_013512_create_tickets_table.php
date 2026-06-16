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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assign_to')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete()
                ->default(null);
            $table->foreignId('category_id')
                ->constrained('categories')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('department_id')
                ->nullable()
                ->constrained('departments')
                ->cascadeOnUpdate()
                ->restrictOnDelete()
                ->default(null);
            $table->foreignId('member_id')
                ->nullable()
                ->constrained('members')
                ->cascadeOnUpdate()
                ->restrictOnDelete()
                ->default(null);
            $table->string('ticket_no', length: 100);
            $table->string('ticket_title', length: 20);
            $table->string('problem', length: 1000);
            $table->string('no_wa')->nullable()->default(null);
            $table->dateTime('report_date')->useCurrent();
            $table->string('location', length: 100);
            $table->enum('priority', ['low', 'mid', 'high'])->nullable()->default(null);
            $table->string('note', length: 255)->nullable()->default(null);
            $table->enum('status_ticket', ['pending', 'on_progress', 'completed', 'reject'])->default('pending');
            $table->string('status_reason')->nullable()->default(null);
            $table->dateTime('closed_at')->nullable()->default(null);
            $table->dateTime('estimate')->nullable()->default(null);
            $table->dateTime('reject_at')->nullable()->default(null);
            $table->string('modul', length: 100)->nullable()->default(null);
            $table->string('sub_modul', length: 100)->nullable()->default(null);
            $table->dateTime('reopened_at')->nullable()->default(null);
            $table->enum('ikb_status_point', ['bugs', 'additional'])->nullable()->default(null);
            $table->dateTime('expired_at')->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
