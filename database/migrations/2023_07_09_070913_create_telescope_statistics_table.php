<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (env('APP_ENV') === 'local') {
            Schema::create('telescope_statistics', function (Blueprint $table) {
                $table->id();
                $table->string('type', 20);
                $table->jsonb('content');// {duration: number, count: number, memory: number}
                $table->dateTime('period');
                $table->enum('period_type', ['hour', 'day', 'week', 'month', 'year']);
                $table->dateTime('created_at')->nullable();

                $table->index('type');
                $table->index('period');
                $table->index(['type', 'period', 'period_type']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('telescope_statistics');
    }
};
