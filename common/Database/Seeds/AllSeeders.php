<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AllSeeders extends Seeder
{
    public function run()
    {
        $this->call('UserSeeder');
        $this->call('ProductSeeder');
    }
}
