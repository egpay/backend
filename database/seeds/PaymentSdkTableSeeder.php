<?php

use Illuminate\Database\Seeder;

class PaymentSdkTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('payment_sdk')->delete();
        
        \DB::table('payment_sdk')->insert(array (
            0 => 
            array (
                'id' => 1,
                'adapter_name' => 'Bee',
                'name' => 'Bee',
                'description' => 'Bee',
                'address' => 'Maady -Egypt',
                'logo' => NULL,
                'area_id' => 1,
                'staff_id' => 1,
                'created_at' => '2017-11-15 00:00:00',
                'updated_at' => '2017-11-01 09:04:19',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}