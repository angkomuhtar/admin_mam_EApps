<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('oauth_clients', function (Blueprint $table) {
            $table->json('scopes')->nullable()->after('redirect');
        });

        DB::table('oauth_clients')
            ->where('personal_access_client', false)
            ->where('password_client', false)
            ->where('redirect', '')
            ->update(['scopes' => json_encode(['plant:read'])]);

        DB::table('oauth_clients')
            ->where('personal_access_client', false)
            ->where('password_client', false)
            ->where('redirect', '!=', '')
            ->update(['scopes' => json_encode(['profile:read'])]);
    }

    public function down(): void
    {
        Schema::table('oauth_clients', function (Blueprint $table) {
            $table->dropColumn('scopes');
        });
    }
};
