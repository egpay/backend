<?php

use Illuminate\Database\Seeder;

class MainWalletsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('main_wallets')->delete();
        
        \DB::table('main_wallets')->insert(array (
            0 => 
            array (
                'id' => 1,
                'unique_name' => 'egpay_main_wallet',
                'name' => 'EGPAY Main Wallet',
                'description' => 'Push Money to System',
                'transfer_in' => 'yes',
                'transfer_out' => 'yes',
                'created_at' => '2017-11-19 00:00:00',
                'updated_at' => '2017-11-20 00:00:00',
            ),
            1 => 
            array (
                'id' => 2,
                'unique_name' => '222',
                'name' => 'EGPAY Main Walletasdasd',
                'description' => 'Push Money to System',
                'transfer_in' => 'yes',
                'transfer_out' => 'yes',
                'created_at' => '2017-11-19 00:00:00',
                'updated_at' => '2017-11-20 00:00:00',
            ),
            2 => 
            array (
                'id' => 3,
                'unique_name' => 'payment_wallet',
                'name' => 'EGPAY Payment Wallet',
                'description' => 'Receive money form payment transactions then send commission to merchant and EGPAY',
                'transfer_in' => 'yes',
                'transfer_out' => 'yes',
                'created_at' => '2017-11-19 00:00:00',
                'updated_at' => '2017-11-20 00:00:00',
            ),
        ));
        
        
    }
}