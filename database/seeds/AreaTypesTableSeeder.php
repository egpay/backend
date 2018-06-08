<?php

use Illuminate\Database\Seeder;

class AreaTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('area_types')->delete();
        
        \DB::table('area_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name_ar' => 'الدولة',
                'name_en' => 'الدولة',
                'parent_id' => NULL,
                'created_at' => '2017-12-03 16:33:37',
                'updated_at' => '2017-09-16 08:34:54',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name_ar' => 'المحافظة',
                'name_en' => 'المحافظة',
                'parent_id' => NULL,
                'created_at' => '2017-12-03 16:33:37',
                'updated_at' => '2017-12-03 16:33:37',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name_ar' => 'الحى',
                'name_en' => 'الحى',
                'parent_id' => NULL,
                'created_at' => '2017-12-03 16:33:37',
                'updated_at' => '2017-12-03 16:33:37',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name_ar' => 'منطقة ثانوية',
                'name_en' => 'منطقة ثانوية',
                'parent_id' => NULL,
                'created_at' => '2017-12-03 16:33:37',
                'updated_at' => '2017-12-03 16:33:37',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'name_ar' => 'منطقة ثانوية 2',
                'name_en' => 'منطقة ثانوية 2',
                'parent_id' => NULL,
                'created_at' => '2017-12-03 16:33:37',
                'updated_at' => '2017-12-03 16:33:37',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'name_ar' => 'منطقة ثانوية 3',
                'name_en' => 'منطقة ثانوية 3',
                'parent_id' => NULL,
                'created_at' => '2017-12-03 16:33:37',
                'updated_at' => '2017-12-03 16:33:37',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}