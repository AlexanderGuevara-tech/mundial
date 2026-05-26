<?php

namespace Database\Seeders;

use App\Models\GameMatch;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorldCupMatchesSeeder extends Seeder
{
    public function run(): void
    {
        $matches = [
            ['A', 'México', 'Sudáfrica', '🇲🇽', '🇿🇦', '2026-06-11'],
            ['A', 'Corea del Sur', 'Chequia', '🇰🇷', '🇨🇿', '2026-06-12'],
            ['A', 'Chequia', 'Sudáfrica', '🇨🇿', '🇿🇦', '2026-06-18'],
            ['A', 'México', 'Corea del Sur', '🇲🇽', '🇰🇷', '2026-06-18'],
            ['A', 'Chequia', 'México', '🇨🇿', '🇲🇽', '2026-06-24'],
            ['A', 'Sudáfrica', 'Corea del Sur', '🇿🇦', '🇰🇷', '2026-06-24'],
            ['B', 'Canadá', 'Bosnia y Herzegovina', '🇨🇦', '🇧🇦', '2026-06-12'],
            ['B', 'Catar', 'Suiza', '🇶🇦', '🇨🇭', '2026-06-13'],
            ['B', 'Suiza', 'Bosnia y Herzegovina', '🇨🇭', '🇧🇦', '2026-06-18'],
            ['B', 'Canadá', 'Catar', '🇨🇦', '🇶🇦', '2026-06-18'],
            ['B', 'Suiza', 'Canadá', '🇨🇭', '🇨🇦', '2026-06-24'],
            ['B', 'Bosnia y Herzegovina', 'Catar', '🇧🇦', '🇶🇦', '2026-06-24'],
            ['C', 'Brasil', 'Marruecos', '🇧🇷', '🇲🇦', '2026-06-13'],
            ['C', 'Haití', 'Escocia', '🇭🇹', '🏴', '2026-06-13'],
            ['C', 'Brasil', 'Haití', '🇧🇷', '🇭🇹', '2026-06-19'],
            ['C', 'Escocia', 'Marruecos', '🏴', '🇲🇦', '2026-06-19'],
            ['C', 'Brasil', 'Escocia', '🇧🇷', '🏴', '2026-06-24'],
            ['C', 'Marruecos', 'Haití', '🇲🇦', '🇭🇹', '2026-06-24'],
            ['D', 'Estados Unidos', 'Paraguay', '🇺🇸', '🇵🇾', '2026-06-12'],
            ['D', 'Australia', 'Turquía', '🇦🇺', '🇹🇷', '2026-06-13'],
            ['D', 'Estados Unidos', 'Australia', '🇺🇸', '🇦🇺', '2026-06-19'],
            ['D', 'Turquía', 'Paraguay', '🇹🇷', '🇵🇾', '2026-06-19'],
            ['D', 'Turquía', 'Estados Unidos', '🇹🇷', '🇺🇸', '2026-06-25'],
            ['D', 'Paraguay', 'Australia', '🇵🇾', '🇦🇺', '2026-06-25'],
            ['E', 'Alemania', 'Curazao', '🇩🇪', '🇨🇼', '2026-06-14'],
            ['E', 'Costa de Marfil', 'Ecuador', '🇨🇮', '🇪🇨', '2026-06-14'],
            ['E', 'Alemania', 'Costa de Marfil', '🇩🇪', '🇨🇮', '2026-06-20'],
            ['E', 'Ecuador', 'Curazao', '🇪🇨', '🇨🇼', '2026-06-20'],
            ['E', 'Ecuador', 'Alemania', '🇪🇨', '🇩🇪', '2026-06-25'],
            ['E', 'Curazao', 'Costa de Marfil', '🇨🇼', '🇨🇮', '2026-06-25'],
            ['F', 'Países Bajos', 'Japón', '🇳🇱', '🇯🇵', '2026-06-14'],
            ['F', 'Suecia', 'Túnez', '🇸🇪', '🇹🇳', '2026-06-15'],
            ['F', 'Países Bajos', 'Suecia', '🇳🇱', '🇸🇪', '2026-06-20'],
            ['F', 'Túnez', 'Japón', '🇹🇳', '🇯🇵', '2026-06-21'],
            ['F', 'Japón', 'Suecia', '🇯🇵', '🇸🇪', '2026-06-25'],
            ['F', 'Túnez', 'Países Bajos', '🇹🇳', '🇳🇱', '2026-06-25'],
            ['G', 'Bélgica', 'Egipto', '🇧🇪', '🇪🇬', '2026-06-15'],
            ['G', 'Irán', 'Nueva Zelanda', '🇮🇷', '🇳🇿', '2026-06-15'],
            ['G', 'Bélgica', 'Irán', '🇧🇪', '🇮🇷', '2026-06-21'],
            ['G', 'Nueva Zelanda', 'Egipto', '🇳🇿', '🇪🇬', '2026-06-21'],
            ['G', 'Egipto', 'Irán', '🇪🇬', '🇮🇷', '2026-06-26'],
            ['G', 'Nueva Zelanda', 'Bélgica', '🇳🇿', '🇧🇪', '2026-06-26'],
            ['H', 'España', 'Cabo Verde', '🇪🇸', '🇨🇻', '2026-06-15'],
            ['H', 'Arabia Saudita', 'Uruguay', '🇸🇦', '🇺🇾', '2026-06-15'],
            ['H', 'España', 'Arabia Saudita', '🇪🇸', '🇸🇦', '2026-06-21'],
            ['H', 'Uruguay', 'Cabo Verde', '🇺🇾', '🇨🇻', '2026-06-21'],
            ['H', 'Uruguay', 'España', '🇺🇾', '🇪🇸', '2026-06-26'],
            ['H', 'Cabo Verde', 'Arabia Saudita', '🇨🇻', '🇸🇦', '2026-06-26'],
            ['I', 'Francia', 'Senegal', '🇫🇷', '🇸🇳', '2026-06-16'],
            ['I', 'Irak', 'Noruega', '🇮🇶', '🇳🇴', '2026-06-16'],
            ['I', 'Francia', 'Irak', '🇫🇷', '🇮🇶', '2026-06-21'],
            ['I', 'Noruega', 'Senegal', '🇳🇴', '🇸🇳', '2026-06-21'],
            ['I', 'Noruega', 'Francia', '🇳🇴', '🇫🇷', '2026-06-26'],
            ['I', 'Senegal', 'Irak', '🇸🇳', '🇮🇶', '2026-06-26'],
            ['J', 'Argentina', 'Argelia', '🇦🇷', '🇩🇿', '2026-06-16'],
            ['J', 'Austria', 'Jordania', '🇦🇹', '🇯🇴', '2026-06-16'],
            ['J', 'Argentina', 'Austria', '🇦🇷', '🇦🇹', '2026-06-22'],
            ['J', 'Jordania', 'Argelia', '🇯🇴', '🇩🇿', '2026-06-23'],
            ['J', 'Argentina', 'Jordania', '🇦🇷', '🇯🇴', '2026-06-27'],
            ['J', 'Argelia', 'Austria', '🇩🇿', '🇦🇹', '2026-06-27'],
            ['K', 'Portugal', 'RD Congo', '🇵🇹', '🇨🇩', '2026-06-17'],
            ['K', 'Uzbekistán', 'Colombia', '🇺🇿', '🇨🇴', '2026-06-17'],
            ['K', 'Portugal', 'Uzbekistán', '🇵🇹', '🇺🇿', '2026-06-23'],
            ['K', 'Colombia', 'RD Congo', '🇨🇴', '🇨🇩', '2026-06-23'],
            ['K', 'Colombia', 'Portugal', '🇨🇴', '🇵🇹', '2026-06-27'],
            ['K', 'RD Congo', 'Uzbekistán', '🇨🇩', '🇺🇿', '2026-06-27'],
            ['L', 'Inglaterra', 'Croacia', '🏴', '🇭🇷', '2026-06-17'],
            ['L', 'Ghana', 'Panamá', '🇬🇭', '🇵🇦', '2026-06-17'],
            ['L', 'Panamá', 'Croacia', '🇵🇦', '🇭🇷', '2026-06-23'],
            ['L', 'Inglaterra', 'Ghana', '🏴', '🇬🇭', '2026-06-23'],
            ['L', 'Panamá', 'Inglaterra', '🇵🇦', '🏴', '2026-06-27'],
            ['L', 'Croacia', 'Ghana', '🇭🇷', '🇬🇭', '2026-06-27'],
        ];

        DB::transaction(function () use ($matches): void {
            foreach ($matches as [$group, $home, $away, $flagHome, $flagAway, $date]) {
                GameMatch::query()->firstOrCreate(
                    ['group_name' => $group, 'team_home' => $home, 'team_away' => $away],
                    ['flag_home' => $flagHome, 'flag_away' => $flagAway, 'match_date' => $date]
                );
            }
        });
    }
}
