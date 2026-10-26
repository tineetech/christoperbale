<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->after('penjualan_draft_id')
                ->constrained('pengguna')
                ->nullOnDelete();
        });

        DB::table('pembayaran')->whereNull('user_id')->orderBy('id')->chunkById(100, function ($rows) {
            foreach ($rows as $row) {
                $userId = null;

                if ($row->penjualan_draft_id) {
                    $userId = DB::table('penjualan_draft')
                        ->where('id', $row->penjualan_draft_id)
                        ->value('created_by');
                } elseif ($row->penjualan_id) {
                    $userId = DB::table('penjualan')
                        ->where('id', $row->penjualan_id)
                        ->value('created_by');
                }

                if ($userId) {
                    DB::table('pembayaran')
                        ->where('id', $row->id)
                        ->update(['user_id' => $userId]);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
