<?php

use Illuminate\Database\Seeder;

class OauthPersonalAccessClientsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('oauth_personal_access_clients')->delete();
        
        \DB::table('oauth_personal_access_clients')->insert(array (
            0 => 
            array (
                'id' => 1,
                'client_id' => 1,
                'created_at' => '2017-10-16 09:28:46',
                'updated_at' => '2017-10-16 09:28:46',
            ),
            1 => 
            array (
                'id' => 2,
                'client_id' => 3,
                'created_at' => '2017-10-16 09:28:56',
                'updated_at' => '2017-10-16 09:28:56',
            ),
        ));
        
        
    }
}