<?php

use App\Models\Donation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->char('public_id', 8)->nullable()->after('id');
        });

        // Generate public_ids for existing records
        Donation::chunkById(100, function ($donations) {
            foreach ($donations as $donation) {
                do {
                    $id = strtoupper(Str::random(8));
                } while (Donation::where('public_id', $id)->exists());

                $donation->update(['public_id' => $id]);
            }
        });

        // Make column not null and unique after population
        Schema::table('donations', function (Blueprint $table) {
            $table->char('public_id', 8)->nullable(false)->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn('public_id');
        });
    }
};
