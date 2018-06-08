<?php

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('settings')->delete();
        
        \DB::table('settings')->insert(array (
            0 => 
            array (
                'name' => 'contract_papers',
                'value' => 'صورة بطاقة
سجل ضريبى
سجل تجارى',
                'shown_name_ar' => 'Contract Papers',
                'shown_name_en' => 'Contract Papers',
                'input_type' => 'textarea',
                'option_list' => NULL,
                'group_name' => 'Merchant',
                'sort' => 4,
                'created_at' => '2017-12-13 22:00:00',
                'updated_at' => '2018-02-13 10:18:33',
            ),
            1 => 
            array (
                'name' => 'description_ar',
                'value' => 'EGPAY',
                'shown_name_ar' => '',
            'shown_name_en' => 'Description (AR)',
                'input_type' => 'textarea',
                'option_list' => NULL,
                'group_name' => 'general',
                'sort' => 3,
                'created_at' => '2017-12-14 00:00:00',
                'updated_at' => '2018-02-13 10:18:33',
            ),
            2 => 
            array (
                'name' => 'description_en',
                'value' => 'EGPAY',
                'shown_name_ar' => '',
            'shown_name_en' => 'Description (EN)',
                'input_type' => 'textarea',
                'option_list' => NULL,
                'group_name' => 'general',
                'sort' => 4,
                'created_at' => '2017-12-14 00:00:00',
                'updated_at' => '2018-02-13 10:18:33',
            ),
            3 => 
            array (
                'name' => 'disabled_wallets',
                'value' => '',
                'shown_name_ar' => '',
            'shown_name_en' => 'Disabled transfer to (Wallet ID)',
                'input_type' => 'textarea',
                'option_list' => NULL,
                'group_name' => 'wallets',
                'sort' => 20,
                'created_at' => '2017-12-14 00:00:00',
                'updated_at' => '2018-02-13 10:18:33',
            ),
            4 => 
            array (
                'name' => 'elasticSearch_IP',
                'value' => '',
                'shown_name_ar' => '',
                'shown_name_en' => 'IP',
                'input_type' => 'text',
                'option_list' => NULL,
                'group_name' => 'search_server',
                'sort' => 6,
                'created_at' => '2017-12-14 00:00:00',
                'updated_at' => '2018-02-13 10:18:33',
            ),
            5 => 
            array (
                'name' => 'elasticSearch_port',
                'value' => '',
                'shown_name_ar' => '',
                'shown_name_en' => 'Port',
                'input_type' => 'number',
                'option_list' => NULL,
                'group_name' => 'search_server',
                'sort' => 7,
                'created_at' => '2017-12-14 00:00:00',
                'updated_at' => '2018-02-13 10:18:33',
            ),
            6 => 
            array (
                'name' => 'loyalty_wallet_id',
                'value' => '4',
                'shown_name_ar' => '',
                'shown_name_en' => 'Loyalty Wallet ID',
                'input_type' => 'number',
                'option_list' => NULL,
                'group_name' => 'wallets',
                'sort' => 17,
                'created_at' => '2017-12-14 00:00:00',
                'updated_at' => '2018-02-13 10:18:33',
            ),
            7 => 
            array (
                'name' => 'mail_driver',
                'value' => '',
                'shown_name_ar' => '',
                'shown_name_en' => 'Driver',
                'input_type' => 'text',
                'option_list' => NULL,
                'group_name' => 'mail',
                'sort' => 8,
                'created_at' => '2017-12-14 00:00:00',
                'updated_at' => '2018-02-13 10:18:33',
            ),
            8 => 
            array (
                'name' => 'mail_encryption',
                'value' => 'ssl',
                'shown_name_ar' => '',
                'shown_name_en' => 'Encryption',
                'input_type' => 'select',
                'option_list' => 'a:2:{s:3:"tls";s:3:"TLS";s:3:"ssl";s:3:"SSL";}',
                'group_name' => 'mail',
                'sort' => 8,
                'created_at' => '2017-12-14 00:00:00',
                'updated_at' => '2018-02-13 10:18:33',
            ),
            9 => 
            array (
                'name' => 'mail_host',
                'value' => 'ssl',
                'shown_name_ar' => '',
                'shown_name_en' => 'Host',
                'input_type' => 'text',
                'option_list' => NULL,
                'group_name' => 'mail',
                'sort' => 9,
                'created_at' => '2017-12-14 00:00:00',
                'updated_at' => '2018-02-13 10:18:33',
            ),
            10 => 
            array (
                'name' => 'mail_password',
                'value' => 'ssl',
                'shown_name_ar' => '',
                'shown_name_en' => 'Password',
                'input_type' => 'text',
                'option_list' => NULL,
                'group_name' => 'mail',
                'sort' => 9,
                'created_at' => '2017-12-14 00:00:00',
                'updated_at' => '2018-02-13 10:18:33',
            ),
            11 => 
            array (
                'name' => 'mail_secret',
                'value' => 'ssl',
                'shown_name_ar' => '',
                'shown_name_en' => 'Secret',
                'input_type' => 'text',
                'option_list' => NULL,
                'group_name' => 'mail',
                'sort' => 10,
                'created_at' => '2017-12-14 00:00:00',
                'updated_at' => '2018-02-13 10:18:34',
            ),
            12 => 
            array (
                'name' => 'mail_secure',
                'value' => 'ssl',
                'shown_name_ar' => '',
                'shown_name_en' => 'Secure',
                'input_type' => 'text',
                'option_list' => NULL,
                'group_name' => 'mail',
                'sort' => 11,
                'created_at' => '2017-12-14 00:00:00',
                'updated_at' => '2018-02-13 10:18:34',
            ),
            13 => 
            array (
                'name' => 'mail_sender_email',
                'value' => 'ssl',
                'shown_name_ar' => '',
                'shown_name_en' => 'Sender E-mail',
                'input_type' => 'text',
                'option_list' => NULL,
                'group_name' => 'mail',
                'sort' => 12,
                'created_at' => '2017-12-14 00:00:00',
                'updated_at' => '2018-02-13 10:18:34',
            ),
            14 => 
            array (
                'name' => 'mail_sender_name',
                'value' => 'ssl',
                'shown_name_ar' => '',
                'shown_name_en' => 'Sender Name',
                'input_type' => 'text',
                'option_list' => NULL,
                'group_name' => 'mail',
                'sort' => 13,
                'created_at' => '2017-12-14 00:00:00',
                'updated_at' => '2018-02-13 10:18:34',
            ),
            15 => 
            array (
                'name' => 'mail_username',
                'value' => 'ssl',
                'shown_name_ar' => '',
                'shown_name_en' => 'User Name',
                'input_type' => 'text',
                'option_list' => NULL,
                'group_name' => 'mail',
                'sort' => 14,
                'created_at' => '2017-12-14 00:00:00',
                'updated_at' => '2018-02-13 10:18:34',
            ),
            16 => 
            array (
                'name' => 'main_wallet_id',
                'value' => '3',
                'shown_name_ar' => '',
                'shown_name_en' => 'Main Wallet ID',
                'input_type' => 'number',
                'option_list' => NULL,
                'group_name' => 'wallets',
                'sort' => 17,
                'created_at' => '2017-12-14 00:00:00',
                'updated_at' => '2018-02-13 10:18:34',
            ),
            17 => 
            array (
                'name' => 'merchant_mobile_app_database_lastupdate',
                'value' => '2018-01-27 12:00:22',
                'shown_name_ar' => '',
                'shown_name_en' => 'Last Update Merchant Database',
                'input_type' => 'text',
                'option_list' => NULL,
                'group_name' => 'merchant_app',
                'sort' => 100,
                'created_at' => '2017-12-14 00:00:00',
                'updated_at' => '2018-02-13 10:18:34',
            ),
            18 => 
            array (
                'name' => 'merchant_mobile_application_version',
                'value' => '1.0',
                'shown_name_ar' => '',
                'shown_name_en' => 'Merchant Application Version',
                'input_type' => 'text',
                'option_list' => NULL,
                'group_name' => 'merchant_app',
                'sort' => 100,
                'created_at' => '2017-12-14 00:00:00',
                'updated_at' => '2018-02-13 10:18:34',
            ),
            19 => 
            array (
                'name' => 'monitor_staff',
                'value' => '1',
                'shown_name_ar' => '',
            'shown_name_en' => 'Monitor Staff (ID for each line)',
                'input_type' => 'textarea',
                'option_list' => NULL,
                'group_name' => 'monitor',
                'sort' => 3,
                'created_at' => '2017-12-14 00:00:00',
                'updated_at' => '2018-02-13 10:18:34',
            ),
            20 => 
            array (
                'name' => 'msg_merchantstaff_created',
                'value' => 'تم انشاء حسابك في ايجي باي بنجاح
كود المستخدم هو: {1}
كلمة المرور هي: {2}
كود التاجر: {3}',
                'shown_name_ar' => 'Merchant staff created message',
                'shown_name_en' => 'Merchant staff created message',
                'input_type' => 'textarea',
                'option_list' => NULL,
                'group_name' => 'sms',
                'sort' => 17,
                'created_at' => '2017-12-14 00:00:00',
                'updated_at' => '2018-02-13 10:18:34',
            ),
            21 => 
            array (
                'name' => 'payment_bee_service_version',
                'value' => '144',
                'shown_name_ar' => '',
            'shown_name_en' => 'Bee (Service Version)',
                'input_type' => 'number',
                'option_list' => NULL,
                'group_name' => 'payment_system',
                'sort' => 50,
                'created_at' => '2017-12-14 00:00:00',
                'updated_at' => '2018-02-13 10:18:34',
            ),
            22 => 
            array (
                'name' => 'payment_sales_commission_rate',
                'value' => 'a:3:{i:0;s:3:"1.2";i:1;s:3:"1.5";i:2;s:1:"2";}',
                'shown_name_ar' => '',
            'shown_name_en' => 'Commission % (Rates)',
                'input_type' => 'multiple',
                'option_list' => NULL,
                'group_name' => 'payment_sales_commission',
                'sort' => 16,
                'created_at' => '2017-12-14 00:00:00',
                'updated_at' => '2018-02-13 10:18:34',
            ),
            23 => 
            array (
                'name' => 'payment_sales_target',
                'value' => 'a:3:{i:0;s:2:"70";i:1;s:2:"80";i:2;s:3:"100";}',
                'shown_name_ar' => '',
            'shown_name_en' => 'Target % (Rates)',
                'input_type' => 'multiple',
                'option_list' => NULL,
                'group_name' => 'payment_sales_commission',
                'sort' => 15,
                'created_at' => '2017-12-14 00:00:00',
                'updated_at' => '2018-02-13 10:18:34',
            ),
            24 => 
            array (
                'name' => 'payment_settlement_wallet_id',
                'value' => '2',
                'shown_name_ar' => '',
                'shown_name_en' => 'Payment Settlement Wallet ID',
                'input_type' => 'number',
                'option_list' => NULL,
                'group_name' => 'wallets',
                'sort' => 17,
                'created_at' => '2017-12-14 00:00:00',
                'updated_at' => '2018-02-13 10:18:34',
            ),
            25 => 
            array (
                'name' => 'payment_wallet_id',
                'value' => '8',
                'shown_name_ar' => '',
                'shown_name_en' => 'Payment Wallet ID',
                'input_type' => 'number',
                'option_list' => NULL,
                'group_name' => 'wallets',
                'sort' => 17,
                'created_at' => '2017-12-14 00:00:00',
                'updated_at' => '2018-02-13 10:18:34',
            ),
            26 => 
            array (
                'name' => 'reverse_fail_payments_invoice',
                'value' => '1',
                'shown_name_ar' => '',
            'shown_name_en' => 'Reverse Fail Payments Invoice After (X) hours',
                'input_type' => 'number',
                'option_list' => NULL,
                'group_name' => 'schedule_tasks',
                'sort' => 17,
                'created_at' => '2017-12-14 00:00:00',
                'updated_at' => '2018-02-13 10:18:34',
            ),
            27 => 
            array (
                'name' => 'site_url',
                'value' => 'https://www.egpay.com/',
                'shown_name_ar' => '',
                'shown_name_en' => 'Site URL',
                'input_type' => 'text',
                'option_list' => NULL,
                'group_name' => 'url',
                'sort' => 5,
                'created_at' => '2017-12-14 00:00:00',
                'updated_at' => '2018-02-13 10:18:34',
            ),
            28 => 
            array (
                'name' => 'sitename_ar',
                'value' => 'EGPAY',
            'shown_name_ar' => 'Site Name (AR)',
            'shown_name_en' => 'Site Name (AR)',
                'input_type' => 'text',
                'option_list' => NULL,
                'group_name' => 'general',
                'sort' => 1,
                'created_at' => '2017-12-14 00:00:00',
                'updated_at' => '2018-02-13 10:18:34',
            ),
            29 => 
            array (
                'name' => 'sitename_en',
                'value' => 'EGPAY',
                'shown_name_ar' => '',
            'shown_name_en' => 'Site Name (EN)',
                'input_type' => 'text',
                'option_list' => NULL,
                'group_name' => 'general',
                'sort' => 2,
                'created_at' => '2017-12-14 00:00:00',
                'updated_at' => '2018-02-13 10:18:34',
            ),
            30 => 
            array (
                'name' => 'sms_password',
                'value' => 'ssl',
                'shown_name_ar' => '',
                'shown_name_en' => 'Password',
                'input_type' => 'text',
                'option_list' => NULL,
                'group_name' => 'sms',
                'sort' => 17,
                'created_at' => '2017-12-14 00:00:00',
                'updated_at' => '2018-02-13 10:18:34',
            ),
            31 => 
            array (
                'name' => 'sms_sender_name',
                'value' => 'ssl',
                'shown_name_ar' => '',
                'shown_name_en' => 'Sender Name',
                'input_type' => 'text',
                'option_list' => NULL,
                'group_name' => 'sms',
                'sort' => 15,
                'created_at' => '2017-12-14 00:00:00',
                'updated_at' => '2018-02-13 10:18:34',
            ),
            32 => 
            array (
                'name' => 'sms_username',
                'value' => 'ssl',
                'shown_name_ar' => '',
                'shown_name_en' => 'User Name',
                'input_type' => 'text',
                'option_list' => NULL,
                'group_name' => 'sms',
                'sort' => 16,
                'created_at' => '2017-12-14 00:00:00',
                'updated_at' => '2018-02-13 10:18:34',
            ),
        ));
        
        
    }
}