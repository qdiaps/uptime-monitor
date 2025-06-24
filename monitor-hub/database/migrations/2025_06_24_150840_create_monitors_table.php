<?php declare(strict_types=1);

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
        Schema::create('monitors', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->foreignId('type_id')->constrained('monitor_types');
            $table->foreignId('ping_param_id')->unique()->constrained('ping_params');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monitors', function (Blueprint $table) {
            $table->dropForeign(['type_id']);
            $table->dropForeign(['ping_param_id']);
        });

        Schema::dropIfExists('monitors');
    }
};
