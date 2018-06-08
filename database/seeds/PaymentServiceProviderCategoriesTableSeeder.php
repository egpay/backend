<?php

use Illuminate\Database\Seeder;

class PaymentServiceProviderCategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('payment_service_provider_categories')->delete();
        
        \DB::table('payment_service_provider_categories')->insert(array (
            0 => 
            array (
                'id' => 2,
                'name_ar' => 'موبايل',
                'name_en' => 'Mobile Bill Payment',
                'description_ar' => NULL,
                'description_en' => NULL,
                'icon' => 'icons/service.png',
                'status' => 'active',
                'staff_id' => 0,
                'sort_by' => 99,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 3,
                'name_ar' => 'فواتير الانترنت',
                'name_en' => 'DSL Bills',
                'description_ar' => NULL,
                'description_en' => NULL,
                'icon' => 'icons/provider_category.png',
                'status' => 'active',
                'staff_id' => 0,
                'sort_by' => 99,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 21,
                'name_ar' => 'خدمات اون لاين',
                'name_en' => 'Online Services',
                'description_ar' => NULL,
                'description_en' => NULL,
                'icon' => 'icons/service.png',
                'status' => 'active',
                'staff_id' => 0,
                'sort_by' => 99,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 41,
                'name_ar' => 'العاب اون لاين',
                'name_en' => 'Online Games',
                'description_ar' => NULL,
                'description_en' => NULL,
                'icon' => 'icons/service.png',
                'status' => 'active',
                'staff_id' => 0,
                'sort_by' => 99,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 62,
                'name_ar' => 'كروت شحن موبايل',
                'name_en' => 'Mobile E-vouchers',
                'description_ar' => NULL,
                'description_en' => NULL,
                'icon' => 'icons/service.png',
                'status' => 'active',
                'staff_id' => 0,
                'sort_by' => 99,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 101,
                'name_ar' => 'جامعات',
                'name_en' => 'Universities',
                'description_ar' => NULL,
                'description_en' => NULL,
                'icon' => 'icons/universities.png',
                'status' => 'active',
                'staff_id' => 0,
                'sort_by' => 99,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 141,
                'name_ar' => 'كهرباء',
                'name_en' => 'Electricity Bills',
                'description_ar' => NULL,
                'description_en' => NULL,
                'icon' => 'icons/service.png',
                'status' => 'active',
                'staff_id' => 0,
                'sort_by' => 99,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 261,
                'name_ar' => 'تبرعات',
                'name_en' => 'Donations',
                'description_ar' => NULL,
                'description_en' => NULL,
                'icon' => 'icons/service.png',
                'status' => 'active',
                'staff_id' => 0,
                'sort_by' => 99,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 341,
                'name_ar' => 'شحن موبايل',
                'name_en' => 'Mobile Credit',
                'description_ar' => NULL,
                'description_en' => NULL,
                'icon' => 'icons/service.png',
                'status' => 'active',
                'staff_id' => 0,
                'sort_by' => 99,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}