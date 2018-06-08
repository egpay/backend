<?php

use Illuminate\Database\Seeder;

class CommissionListTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('commission_list')->delete();
        
        \DB::table('commission_list')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Amr Alaa',
                'description' => '10wwwwwwwwwwwwww',
                'commission_type' => 'one',
                'condition_data' => 'a:4:{s:11:"charge_type";s:7:"percent";s:17:"system_commission";s:1:"1";s:16:"agent_commission";s:1:"2";s:19:"merchant_commission";s:1:"3";}',
                'staff_id' => 1,
                'created_at' => '2017-11-21 00:00:00',
                'updated_at' => '2018-01-16 13:02:30',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Amr Alaadd',
                'description' => 'Meroaaaaaaaaaaaaaaaaaaaaaaa',
                'commission_type' => 'multiple',
                'condition_data' => 'a:2:{i:0;a:5:{s:11:"amount_from";s:1:"0";s:9:"amount_to";s:1:"5";s:11:"charge_type";s:5:"fixed";s:17:"system_commission";s:1:"2";s:19:"merchant_commission";s:1:"3";}i:1;a:5:{s:11:"amount_from";s:1:"6";s:9:"amount_to";s:25:"1009999999999999999999999";s:11:"charge_type";s:7:"percent";s:17:"system_commission";s:2:"50";s:19:"merchant_commission";s:2:"10";}}',
                'staff_id' => 1,
                'created_at' => '2017-11-23 07:53:08',
                'updated_at' => '2017-11-26 15:07:07',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Smith',
                'description' => '208.100.0.117',
                'commission_type' => 'one',
                'condition_data' => 'a:3:{s:11:"charge_type";s:5:"fixed";s:17:"system_commission";s:1:"3";s:19:"merchant_commission";s:1:"3";}',
                'staff_id' => 1,
                'created_at' => '2017-12-03 16:40:52',
                'updated_at' => '2017-12-03 16:40:52',
            ),
        ));
        
        
    }
}