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
                'value' => '{"subtype":"csv_string"}',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'table' => 'boards',
                'column' => NULL,
                'key' => 'imageField',
                'value' => 'name',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'table' => 'columns',
                'column' => NULL,
                'key' => 'imageField',
                'value' => 'name',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}