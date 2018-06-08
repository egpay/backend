<?php

use Illuminate\Database\Seeder;

class MerchantPlansTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('merchant_plans')->delete();
        
        \DB::table('merchant_plans')->insert(array (
            0 => 
            array (
                'id' => 2,
                'title' => 'hfdhfdj',
                'description' => 'fjhfdjfd',
                'months' => 12,
                'amount' => 0.0,
                'staff_id' => 1,
                'type' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}