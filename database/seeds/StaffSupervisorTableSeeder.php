<?php

use Illuminate\Database\Seeder;

class StaffSupervisorTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('staff_supervisor')->delete();
        
        \DB::table('staff_supervisor')->insert(array (
            0 => 
            array (
                'id' => 1,
                'staff_supervisor_id' => 1,
                'staff_managed_id' => 3,
                'created_at' => '2017-11-13 00:00:00',
                'updated_at' => '2017-11-15 00:00:00',
            ),
        ));
        
        
    }
}