<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConsultationStatusSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('consultation_status')->insert([
            ['id' => 1, 'status_name' => 'Consulted', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'status_name' => 'Completed', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
