<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vaksin;

class VaksinSeeder extends Seeder
{
    public function run(): void
    {
        Vaksin::insert([
            [
                'nama_vaksin' => 'Meningitis Meningococcus', 
                'is_aktif'    => true, 
                'created_at'  => now(), 
                'updated_at'  => now()
            ],
            [
                'nama_vaksin' => 'Polio Injeksi IPV',        
                'is_aktif'    => true, 
                'created_at'  => now(), 
                'updated_at'  => now()
            ],
            [
                'nama_vaksin' => 'Influenza',                
                'is_aktif'    => false, // Contoh ditutup sementara oleh admin
                'created_at'  => now(), 
                'updated_at'  => now()
            ],
            [
                'nama_vaksin' => 'Yellow Fever',            
                'is_aktif'    => true, 
                'created_at'  => now(), 
                'updated_at'  => now()
            ],
        ]);
    }
}