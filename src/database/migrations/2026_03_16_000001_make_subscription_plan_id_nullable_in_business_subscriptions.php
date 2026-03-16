<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            Schema::table('business_subscriptions', function (Blueprint $table) {
                $table->dropForeign(['subscription_plan_id']);
            });
            Schema::create('business_subscriptions_new', function (Blueprint $table) {
                $table->id();
                $table->foreignId('business_id')->constrained()->cascadeOnDelete();
                $table->foreignId('subscription_plan_id')->nullable()->constrained('subscription_plans')->nullOnDelete();
                $table->date('start_date');
                $table->date('end_date');
                $table->string('status')->default('active');
                $table->timestamps();
                $table->index(['business_id', 'status']);
                $table->index('end_date');
            });
            DB::statement('INSERT INTO business_subscriptions_new (id, business_id, subscription_plan_id, start_date, end_date, status, created_at, updated_at) SELECT id, business_id, subscription_plan_id, start_date, end_date, status, created_at, updated_at FROM business_subscriptions');
            Schema::drop('business_subscriptions');
            Schema::rename('business_subscriptions_new', 'business_subscriptions');
            return;
        }

        Schema::table('business_subscriptions', function (Blueprint $table) {
            $table->dropForeign(['subscription_plan_id']);
        });
        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE business_subscriptions MODIFY subscription_plan_id BIGINT UNSIGNED NULL');
        } else {
            DB::statement('ALTER TABLE business_subscriptions ALTER COLUMN subscription_plan_id DROP NOT NULL');
        }
        Schema::table('business_subscriptions', function (Blueprint $table) {
            $table->foreign('subscription_plan_id')
                ->references('id')
                ->on('subscription_plans')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            Schema::table('business_subscriptions', function (Blueprint $table) {
                $table->dropForeign(['subscription_plan_id']);
            });
            Schema::create('business_subscriptions_new', function (Blueprint $table) {
                $table->id();
                $table->foreignId('business_id')->constrained()->cascadeOnDelete();
                $table->foreignId('subscription_plan_id')->constrained('subscription_plans')->cascadeOnDelete();
                $table->date('start_date');
                $table->date('end_date');
                $table->string('status')->default('active');
                $table->timestamps();
                $table->index(['business_id', 'status']);
                $table->index('end_date');
            });
            DB::statement('INSERT INTO business_subscriptions_new (id, business_id, subscription_plan_id, start_date, end_date, status, created_at, updated_at) SELECT id, business_id, subscription_plan_id, start_date, end_date, status, created_at, updated_at FROM business_subscriptions');
            Schema::drop('business_subscriptions');
            Schema::rename('business_subscriptions_new', 'business_subscriptions');
            return;
        }

        Schema::table('business_subscriptions', function (Blueprint $table) {
            $table->dropForeign(['subscription_plan_id']);
        });
        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE business_subscriptions MODIFY subscription_plan_id BIGINT UNSIGNED NOT NULL');
        } else {
            DB::statement('ALTER TABLE business_subscriptions ALTER COLUMN subscription_plan_id SET NOT NULL');
        }
        Schema::table('business_subscriptions', function (Blueprint $table) {
            $table->foreign('subscription_plan_id')
                ->references('id')
                ->on('subscription_plans')
                ->cascadeOnDelete();
        });
    }
};
