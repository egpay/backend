<?php

use Illuminate\Database\Seeder;

class StaffTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('staff')->delete();
        
        \DB::table('staff')->insert(array (
            0 => 
            array (
                'id' => 1,
                'firstname' => 'Egpay',
                'lastname' => 'Admin',
                'national_id' => '',
                'email' => 'admin@egpay.com',
                'mobile' => '01163548659',
                'avatar' => NULL,
                'gender' => '',
                'birthdate' => NULL,
                'address' => NULL,
                'password' => bcrypt('123456'),
                'remember_token' => 'bAMpxI0NOKiDhgHAxFL1OSFAfou5s7jHbMOHmJ6p39Y6DqfPNdsbvxJ2SMlt',
                'description' => NULL,
                'job_title' => NULL,
                'status' => 'active',
                'language_id' => NULL,
                'permission_group_id' => 2,
                'supervisor_id' => 0,
                'menu_type' => 127,
                'lastlogin' => NULL,
                'language_key' => 'en',
                'created_at' => '2018-02-20 00:00:00',
                'updated_at' => '2018-02-26 14:54:45',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}