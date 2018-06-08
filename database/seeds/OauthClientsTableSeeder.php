<?php

use Illuminate\Database\Seeder;

class OauthClientsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('oauth_clients')->delete();
        
        \DB::table('oauth_clients')->insert(array (
            0 => 
            array (
                'id' => 1,
                'user_id' => NULL,
                'name' => 'Laravel Personal Access Client',
                'guard_name' => NULL,
                'secret' => 'Q1CjWKsbZRI51uMoz6pgNG27ML8sCfup0qfP1gl6',
                'redirect' => 'http://localhost',
                'personal_access_client' => 1,
                'password_client' => 0,
                'revoked' => 0,
                'created_at' => '2017-10-16 09:28:46',
                'updated_at' => '2017-10-16 09:28:46',
            ),
            1 => 
            array (
                'id' => 2,
                'user_id' => NULL,
                'name' => 'Laravel Password Grant Client',
                'guard_name' => 'api',
                'secret' => 'iEVc9FcVcyn3oUWUYVoutcZzdXWSDrSjT9eISNpm',
                'redirect' => 'http://localhost',
                'personal_access_client' => 0,
                'password_client' => 1,
                'revoked' => 0,
                'created_at' => '2017-10-16 09:28:47',
                'updated_at' => '2017-10-16 09:28:47',
            ),
            2 => 
            array (
                'id' => 3,
                'user_id' => NULL,
                'name' => 'Laravel Personal Access Client',
                'guard_name' => NULL,
                'secret' => 'ByMNcxFGMk9b8ZX17EIkfI7V05jB8Not654bsgyQ',
                'redirect' => 'http://localhost',
                'personal_access_client' => 1,
                'password_client' => 0,
                'revoked' => 0,
                'created_at' => '2017-10-16 09:28:56',
                'updated_at' => '2017-10-16 09:28:56',
            ),
            3 => 
            array (
                'id' => 4,
                'user_id' => NULL,
                'name' => 'Laravel Password Grant Client',
                'guard_name' => 'apiMerchant',
                'secret' => '7XnO1Aar3qfgm829j5IXD0QTq2rhU8k0HIYampOD',
                'redirect' => 'http://localhost',
                'personal_access_client' => 0,
                'password_client' => 1,
                'revoked' => 0,
                'created_at' => '2017-10-16 09:28:56',
                'updated_at' => '2017-10-16 09:28:56',
            ),
        ));
        
        
    }
}