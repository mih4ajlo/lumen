<?php

use App\Models\Sadrzaj;  
use Illuminate\Database\Seeder;

class SadrzajSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Sadrzaj::create([
            'content' => 'Success is going from failure to failure without losing your enthusiasm',
            'owner' => '0',
            'order' => '0'
        ]);

        Sadrzaj::create([
            'content' => 'Dream big and dare to fail',
            'owner' => '0',
            'order' => '0'
        ]);
    }
}
