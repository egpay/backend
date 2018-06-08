<?php

use Illuminate\Database\Seeder;

class AttributeCategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('attribute_categories')->delete();
        
        \DB::table('attribute_categories')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name_ar' => 'Clothes AR',
                'name_en' => 'Clothes EN',
                'description_ar' => 'Clothes Description AR',
                'description_en' => 'Clothes Description EN',
                'created_at' => '2017-10-30 14:02:24',
                'updated_at' => '2018-01-02 11:10:19',
            ),
            1 => 
            array (
                'id' => 2,
                'name_ar' => 'اللون',
                'name_en' => 'Colors',
                'description_ar' => 'وصف الألوان',
                'description_en' => 'Colors category',
                'created_at' => '2018-01-02 11:39:51',
                'updated_at' => '2018-01-02 11:39:51',
            ),
            2 => 
            array (
                'id' => 3,
                'name_ar' => 'ColorsAttr AR',
                'name_en' => 'ColorsAttr EN',
                'description_ar' => 'ColorsAttr AR Desc',
                'description_en' => 'ColorsAttr EN',
                'created_at' => '2018-01-04 14:34:47',
                'updated_at' => '2018-01-04 14:34:47',
            ),
        ));
        
        
    }
}