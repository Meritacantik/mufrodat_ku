<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class DebugLeaderboard extends Command
{
    protected $signature = 'debug:leaderboard';
    protected $description = 'Telusuri di titik mana nama siswa hilang di alur LeaderboardController';

    public function handle(): int
    {
        $this->info('--- Langkah 1: query User biasa ---');
        $u = User::where('role', 'siswa')->first();
        $this->line('Nama (query biasa): ' . ($u->name ?? 'NULL'));

        $this->info('--- Langkah 2: query + with(score) ---');
        $ranking = User::where('role', 'siswa')->with('score')->get();
        $this->line('Nama (setelah with score): ' . ($ranking->first()->name ?? 'NULL'));

        $this->info('--- Langkah 3: setelah map (tambah total_poin/total_sesi) ---');
        $ranking = $ranking->map(function ($user) {
            $user->total_poin = $user->score->total_poin ?? 0;
            $user->total_sesi = $user->score->total_sesi ?? 0;
            return $user;
        });
        $this->line('Nama (setelah map pertama): ' . ($ranking->first()->name ?? 'NULL'));
        $this->line('total_poin ikut kebawa: ' . ($ranking->first()->total_poin ?? 'NULL'));

        $this->info('--- Langkah 4: setelah sortBy ---');
        $sorted = $ranking->sortBy([
            fn($a, $b) => $b->total_poin <=> $a->total_poin,
            fn($a, $b) => $a->name <=> $b->name,
        ])->values();
        $this->line('Nama (setelah sortBy, urutan #1): ' . ($sorted->first()->name ?? 'NULL'));
        $this->line('Poin (setelah sortBy, urutan #1): ' . ($sorted->first()->total_poin ?? 'NULL'));

        $this->info('--- Langkah 5: setelah map peringkat (map kedua) ---');
        $final = $sorted->map(function ($user) use ($sorted) {
            $user->peringkat = $sorted->where('total_poin', '>', $user->total_poin)->count() + 1;
            return $user;
        });
        $this->line('Nama (final, urutan #1): ' . ($final->first()->name ?? 'NULL'));
        $this->line('Peringkat (final, urutan #1): ' . ($final->first()->peringkat ?? 'NULL'));

        $this->info('--- Langkah 6: cek toArray / apa yang sebenarnya dikirim ke view ---');
        $this->line('name via array access: ' . ($final->first()->toArray()['name'] ?? 'TIDAK ADA DI ARRAY'));

        return self::SUCCESS;
    }
}
