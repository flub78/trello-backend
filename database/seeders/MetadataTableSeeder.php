<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MetadataTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('metadata')->delete();
        
        \DB::table('metadata')->insert(array (
            0 => 
            array (
                'id' => 1,
                'table' => 'boards',
                'column' => 'favorite',
                'key' => 'subtype',
                'value' => 'boolean',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'table' => 'boards',
                'column' => 'read_at',
                'key' => 'json',
                'value' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'table' => 'boards',
                'column' => 'lists',
                'key' => 'json',
                'value' => '{"subtype":"super_csv_string", "fillable":"false"}',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}