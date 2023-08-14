<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function getConnection(): string|null
    {
        return config('telescope-aggregate.storage.database.connection');
    }

    public function up()
    {
        $schema = Schema::connection($this->getConnection());

        $schema->create('telescope_aggregate', function (Blueprint $table) {
            $table->id();
            $table->string('type', 20);
            $table->jsonb('content');
            $table->dateTime('period');
            $table->enum('period_type', ['hour', 'day', 'week', 'month', 'year']);
            $table->dateTime('created_at')->nullable();

            $table->index('type');
            $table->index('period');
            $table->index(['type', 'period', 'period_type']);
        });
    }

    public function down()
    {
        $schema = Schema::connection($this->getConnection());

        $schema->dropIfExists('telescope_aggregate');
    }
};
