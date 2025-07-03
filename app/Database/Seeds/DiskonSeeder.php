<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Carbon\Carbon;

class DiskonSeeder extends Seeder
{
    public function run()
    {
        $data = [];

        for ($i = 0; $i < 10; $i++) {
            $tanggal = Carbon::today()->addDays($i)->format('Y-m-d');
            $now = Carbon::now()->format('Y-m-d H:i:s');

            $data[] = [
                'tanggal'    => $tanggal,
                'nominal'    => rand(50000, 150000),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        $this->db->table('diskon')->insertBatch($data);
    }
}
