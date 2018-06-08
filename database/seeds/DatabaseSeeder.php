<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $this->call(AreasTableSeeder::class);
        $this->call(AreaTypesTableSeeder::class);
        $this->call(AttributeCategoriesTableSeeder::class);
        $this->call(AttributeValuesTableSeeder::class);
        $this->call(CommissionListTableSeeder::class);
        $this->call(CountriesTableSeeder::class);
        $this->call(MainWalletsTableSeeder::class);
        $this->call(MerchantPlansTableSeeder::class);
        $this->call(NewsCategoriesTableSeeder::class);
        $this->call(OauthClientsTableSeeder::class);
        $this->call(OauthPersonalAccessClientsTableSeeder::class);
        $this->call(PaymentSdkTableSeeder::class);
        $this->call(PaymentServicesTableSeeder::class);
        $this->call(PaymentServiceApisTableSeeder::class);
        $this->call(PaymentServiceApiParametersTableSeeder::class);
        $this->call(PaymentServiceProvidersTableSeeder::class);
        $this->call(PaymentServiceProviderCategoriesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(PermissionGroupsTableSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(StaffTableSeeder::class);
        $this->call(StaffSupervisorTableSeeder::class);
    }
}
