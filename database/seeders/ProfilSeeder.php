<?php

namespace Database\Seeders;

use App\Models\Profil;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $profils = [
            [
                "name" => "Sivinaries Coffe",
                "alamat" => "Jl. Satrio Wibowo 3 No.73",
                "jam" => "10.00 - 23.00 WIB",
                "no_wa" => "6287733839260",
                "deskripsi" => "Buka Senin - Minggu | Sejak Dulu | Kami Ada Karena Kamu #CahSkena",

            ],
        ];
        foreach ($profils as $profil) {
            Profil::create($profil);
        }
    }
}
