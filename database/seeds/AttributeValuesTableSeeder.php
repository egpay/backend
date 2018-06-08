<?php

use Illuminate\Database\Seeder;

class AttributeValuesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('attribute_values')->delete();
        
        \DB::table('attribute_values')->insert(array (
            0 => 
            array (
                'id' => 1,
                'attribute_id' => 1,
                'text_ar' => 'Red AR',
                'text_en' => 'Red EN',
                'is_default' => 0,
                'created_at' => '2017-10-30 14:02:24',
                'updated_at' => '2017-10-30 14:02:24',
            ),
            1 => 
            array (
                'id' => 2,
                'attribute_id' => 1,
                'text_ar' => 'Blue AR',
                'text_en' => 'Blue EN',
                'is_default' => 1,
                'created_at' => '2017-10-30 14:02:24',
                'updated_at' => '2017-10-30 14:02:24',
            ),
            2 => 
            array (
                'id' => 3,
                'attribute_id' => 1,
                'text_ar' => 'Green AR',
                'text_en' => 'Green EN',
                'is_default' => 0,
                'created_at' => '2017-10-30 14:02:24',
                'updated_at' => '2017-10-30 14:02:24',
            ),
            3 => 
            array (
                'id' => 4,
                'attribute_id' => 1,
                'text_ar' => 'Gray AR',
                'text_en' => 'Gray EN',
                'is_default' => 0,
                'created_at' => '2017-10-30 14:02:25',
                'updated_at' => '2017-10-30 14:02:25',
            ),
            4 => 
            array (
                'id' => 5,
                'attribute_id' => 2,
                'text_ar' => 'input text AR',
                'text_en' => 'input text EN',
                'is_default' => 0,
                'created_at' => '2017-10-30 14:02:25',
                'updated_at' => '2017-10-30 14:02:25',
            ),
            5 => 
            array (
                'id' => 6,
                'attribute_id' => 4,
                'text_ar' => 'l',
                'text_en' => 'u',
                'is_default' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'attribute_id' => 6,
                'text_ar' => 'fdhfdhfdhhhhhhh',
                'text_en' => 'hhhhhhhhh',
                'is_default' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'attribute_id' => 7,
                'text_ar' => '',
                'text_en' => 'B',
                'is_default' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'attribute_id' => 0,
                'text_ar' => '',
                'text_en' => 'B',
                'is_default' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'attribute_id' => 0,
                'text_ar' => 'red',
                'text_en' => 'أحمر',
                'is_default' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'attribute_id' => 0,
                'text_ar' => 'Blue',
                'text_en' => 'ازرق',
                'is_default' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'attribute_id' => 0,
                'text_ar' => 'Black',
                'text_en' => 'أسود',
                'is_default' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'attribute_id' => 0,
                'text_ar' => 'Black',
                'text_en' => 'أسود',
                'is_default' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'attribute_id' => 0,
                'text_ar' => 'Green',
                'text_en' => 'أخضر',
                'is_default' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            14 => 
            array (
                'id' => 18,
                'attribute_id' => 16,
                'text_ar' => 'أحمر',
                'text_en' => 'Red',
                'is_default' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            15 => 
            array (
                'id' => 19,
                'attribute_id' => 16,
                'text_ar' => 'Blue',
                'text_en' => 'أزرق',
                'is_default' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            16 => 
            array (
                'id' => 20,
                'attribute_id' => 16,
                'text_ar' => 'Black',
                'text_en' => 'أسود',
                'is_default' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}