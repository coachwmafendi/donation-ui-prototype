<?php

use App\Models\Profile;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->char('public_id', 8)->nullable()->after('id');
        });

        // Generate public_ids for existing records
        Profile::chunkById(100, function ($profiles) {
            foreach ($profiles as $profile) {
                do {
                    $id = strtoupper(Str::random(8));
                } while (Profile::where('public_id', $id)->exists());

                $profile->update(['public_id' => $id]);
            }
        });

        // Make column not null and unique after population
        Schema::table('profiles', function (Blueprint $table) {
            $table->char('public_id', 8)->nullable(false)->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn('public_id');
        });
    }
};
