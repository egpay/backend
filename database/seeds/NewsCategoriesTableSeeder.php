<?php

use Illuminate\Database\Seeder;

class NewsCategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('news_categories')->delete();
        
        \DB::table('news_categories')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name_ar' => 'N AR',
                'name_en' => 'N EN',
                'descriptin_ar' => 'asdasd',
                'descriptin_en' => 'ddddddddddd',
                'icon' => '',
                'staff_id' => 1,
                'status' => 'active',
                'type' => 'merchant',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name_ar' => '3',
                'name_en' => '1',
                'descriptin_ar' => '4',
                'descriptin_en' => '2',
                'icon' => 'news-category/17/09/mG9PbUcKinozxMwH8e0Hvdna6gymlgI6ZTjoG7A7.png',
                'staff_id' => 1,
                'status' => 'in-active',
                'type' => 'merchant',
                'created_at' => '2017-09-16 13:39:33',
                'updated_at' => '2017-09-16 13:40:07',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}