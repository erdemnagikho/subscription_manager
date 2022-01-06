<?php

use App\Models\Subscription;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('receipt')->unique();
            $table->unsignedBigInteger('device_id');
            $table->unsignedBigInteger('app_id');
            $table->enum('status', [Subscription::SUBSCRIPTION_STARTED, Subscription::SUBSCRIPTION_RENEWED, Subscription::SUBSCRIPTION_CANCELLED])
            ->default(Subscription::SUBSCRIPTION_STARTED);
            $table->timestamp('expire_date');
            $table->timestamp('cancelled_date')->nullable();
            $table->timestamps();

            $table->index(['device_id', 'receipt', 'expire_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
}
