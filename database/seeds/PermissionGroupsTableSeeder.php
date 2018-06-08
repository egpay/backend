<?php

use Illuminate\Database\Seeder;

class PermissionGroupsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('permission_groups')->delete();
        
        \DB::table('permission_groups')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Super Admin',
                'is_supervisor' => 'yes',
                'whitelist_ip' => '',
                'created_at' => '2017-09-20 14:11:59',
                'updated_at' => '2018-02-03 20:57:38',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Management',
                'is_supervisor' => 'yes',
                'whitelist_ip' => '',
                'created_at' => '2017-09-20 14:11:59',
                'updated_at' => '2018-02-27 15:27:12',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Developers',
                'is_supervisor' => 'yes',
                'whitelist_ip' => '',
                'created_at' => '2017-12-03 16:39:20',
                'updated_at' => '2018-01-22 19:45:50',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Accountant',
                'is_supervisor' => 'yes',
                'whitelist_ip' => '196.218.97.77',
                'created_at' => '2018-01-04 11:23:35',
                'updated_at' => '2018-01-31 20:46:15',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Sales Supervisor',
                'is_supervisor' => 'yes',
                'whitelist_ip' => '',
                'created_at' => '2018-01-27 08:31:20',
                'updated_at' => '2018-01-27 08:31:20',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Sales',
                'is_supervisor' => 'no',
                'whitelist_ip' => '',
                'created_at' => '2018-01-27 08:31:38',
                'updated_at' => '2018-01-27 08:31:38',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}