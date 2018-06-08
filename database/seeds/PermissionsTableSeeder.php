<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('permissions')->delete();
        
        \DB::table('permissions')->insert(array (
            0 => 
            array (
                'id' => 6,
                'route_name' => 'merchant.create',
                'permission_group_id' => 1,
                'created_at' => '2017-09-20 14:13:15',
                'updated_at' => '2017-09-20 14:13:15',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 8,
                'route_name' => 'merchant.update',
                'permission_group_id' => 1,
                'created_at' => '2017-09-20 14:13:15',
                'updated_at' => '2017-09-20 14:13:15',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 9,
                'route_name' => 'loyalty-programs.show',
                'permission_group_id' => 3,
                'created_at' => '2017-12-27 15:52:16',
                'updated_at' => '2017-12-27 15:52:16',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 10,
                'route_name' => 'loyalty-programs.delete',
                'permission_group_id' => 3,
                'created_at' => '2017-12-27 15:52:17',
                'updated_at' => '2017-12-27 15:52:17',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 11,
                'route_name' => 'loyalty-programs.create',
                'permission_group_id' => 3,
                'created_at' => '2017-12-27 15:52:17',
                'updated_at' => '2017-12-27 15:52:17',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 12,
                'route_name' => 'loyalty-programs.store',
                'permission_group_id' => 3,
                'created_at' => '2017-12-27 15:52:17',
                'updated_at' => '2017-12-27 15:52:17',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 13,
                'route_name' => 'loyalty-programs.edit',
                'permission_group_id' => 3,
                'created_at' => '2017-12-27 15:52:17',
                'updated_at' => '2017-12-27 15:52:17',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 14,
                'route_name' => 'loyalty-programs.update',
                'permission_group_id' => 3,
                'created_at' => '2017-12-27 15:52:17',
                'updated_at' => '2017-12-27 15:52:17',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 6035,
                'route_name' => 'merchant.merchant.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 6036,
                'route_name' => 'merchant.merchant.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => 6037,
                'route_name' => 'merchant.merchant.destroy',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                'id' => 6038,
                'route_name' => 'merchant.merchant.review',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            12 => 
            array (
                'id' => 6039,
                'route_name' => 'merchant.merchant.create',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            13 => 
            array (
                'id' => 6040,
                'route_name' => 'merchant.merchant.store',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            14 => 
            array (
                'id' => 6041,
                'route_name' => 'merchant.merchant.fast-create',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            15 => 
            array (
                'id' => 6042,
                'route_name' => 'merchant.merchant.fast-create.store',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            16 => 
            array (
                'id' => 6043,
                'route_name' => 'merchant.merchant.edit',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            17 => 
            array (
                'id' => 6044,
                'route_name' => 'merchant.merchant.update',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            18 => 
            array (
                'id' => 6045,
                'route_name' => 'merchant.category.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            19 => 
            array (
                'id' => 6046,
                'route_name' => 'merchant.category.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            20 => 
            array (
                'id' => 6047,
                'route_name' => 'merchant.category.destroy',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            21 => 
            array (
                'id' => 6048,
                'route_name' => 'merchant.category.create',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            22 => 
            array (
                'id' => 6049,
                'route_name' => 'merchant.category.store',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            23 => 
            array (
                'id' => 6050,
                'route_name' => 'merchant.category.edit',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            24 => 
            array (
                'id' => 6051,
                'route_name' => 'merchant.category.update',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            25 => 
            array (
                'id' => 6052,
                'route_name' => 'merchant.contract.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            26 => 
            array (
                'id' => 6053,
                'route_name' => 'merchant.contract.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            27 => 
            array (
                'id' => 6054,
                'route_name' => 'merchant.contract.destroy',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            28 => 
            array (
                'id' => 6055,
                'route_name' => 'merchant.contract.create',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            29 => 
            array (
                'id' => 6056,
                'route_name' => 'merchant.contract.store',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            30 => 
            array (
                'id' => 6057,
                'route_name' => 'merchant.contract.edit',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            31 => 
            array (
                'id' => 6058,
                'route_name' => 'merchant.contract.update',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            32 => 
            array (
                'id' => 6059,
                'route_name' => 'merchant.branch.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            33 => 
            array (
                'id' => 6060,
                'route_name' => 'merchant.branch.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            34 => 
            array (
                'id' => 6061,
                'route_name' => 'merchant.branch.destroy',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            35 => 
            array (
                'id' => 6062,
                'route_name' => 'merchant.branch.create',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            36 => 
            array (
                'id' => 6063,
                'route_name' => 'merchant.branch.store',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            37 => 
            array (
                'id' => 6064,
                'route_name' => 'merchant.branch.edit',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            38 => 
            array (
                'id' => 6065,
                'route_name' => 'merchant.branch.update',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            39 => 
            array (
                'id' => 6066,
                'route_name' => 'merchant.product-category.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            40 => 
            array (
                'id' => 6067,
                'route_name' => 'merchant.product-category.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            41 => 
            array (
                'id' => 6068,
                'route_name' => 'merchant.product-category.destroy',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            42 => 
            array (
                'id' => 6069,
                'route_name' => 'merchant.product-category.create',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            43 => 
            array (
                'id' => 6070,
                'route_name' => 'merchant.product-category.store',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            44 => 
            array (
                'id' => 6071,
                'route_name' => 'merchant.product-category.edit',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            45 => 
            array (
                'id' => 6072,
                'route_name' => 'merchant.product-category.update',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            46 => 
            array (
                'id' => 6073,
                'route_name' => 'merchant.product.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            47 => 
            array (
                'id' => 6074,
                'route_name' => 'merchant.product.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            48 => 
            array (
                'id' => 6075,
                'route_name' => 'merchant.product.destroy',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            49 => 
            array (
                'id' => 6076,
                'route_name' => 'merchant.product.create',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            50 => 
            array (
                'id' => 6077,
                'route_name' => 'merchant.product.store',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            51 => 
            array (
                'id' => 6078,
                'route_name' => 'merchant.product.edit',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            52 => 
            array (
                'id' => 6079,
                'route_name' => 'merchant.product.update',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            53 => 
            array (
                'id' => 6080,
                'route_name' => 'merchant.product-attributes-category.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            54 => 
            array (
                'id' => 6081,
                'route_name' => 'merchant.product-attributes-category.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            55 => 
            array (
                'id' => 6082,
                'route_name' => 'merchant.product-attributes-category.destroy',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            56 => 
            array (
                'id' => 6083,
                'route_name' => 'merchant.product-attributes-category.create',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            57 => 
            array (
                'id' => 6084,
                'route_name' => 'merchant.product-attributes-category.store',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            58 => 
            array (
                'id' => 6085,
                'route_name' => 'merchant.product-attributes-category.edit',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            59 => 
            array (
                'id' => 6086,
                'route_name' => 'merchant.product-attributes-category.update',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            60 => 
            array (
                'id' => 6087,
                'route_name' => 'merchant.product-attributes.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            61 => 
            array (
                'id' => 6088,
                'route_name' => 'merchant.product-attributes.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            62 => 
            array (
                'id' => 6089,
                'route_name' => 'merchant.product-attributes.destroy',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            63 => 
            array (
                'id' => 6090,
                'route_name' => 'merchant.product-attributes.create',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            64 => 
            array (
                'id' => 6091,
                'route_name' => 'merchant.product-attributes.store',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            65 => 
            array (
                'id' => 6092,
                'route_name' => 'merchant.product-attributes.edit',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            66 => 
            array (
                'id' => 6093,
                'route_name' => 'merchant.product-attributes.update',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            67 => 
            array (
                'id' => 6094,
                'route_name' => 'merchant.staff-group.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            68 => 
            array (
                'id' => 6095,
                'route_name' => 'merchant.staff-group.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            69 => 
            array (
                'id' => 6096,
                'route_name' => 'merchant.staff-group.destroy',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            70 => 
            array (
                'id' => 6097,
                'route_name' => 'merchant.staff-group.create',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            71 => 
            array (
                'id' => 6098,
                'route_name' => 'merchant.staff-group.store',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            72 => 
            array (
                'id' => 6099,
                'route_name' => 'merchant.staff-group.edit',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            73 => 
            array (
                'id' => 6100,
                'route_name' => 'merchant.staff-group.update',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            74 => 
            array (
                'id' => 6101,
                'route_name' => 'merchant.coupon.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            75 => 
            array (
                'id' => 6102,
                'route_name' => 'merchant.coupon.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            76 => 
            array (
                'id' => 6103,
                'route_name' => 'merchant.coupon.destroy',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            77 => 
            array (
                'id' => 6104,
                'route_name' => 'merchant.coupon.create',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            78 => 
            array (
                'id' => 6105,
                'route_name' => 'merchant.coupon.store',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            79 => 
            array (
                'id' => 6106,
                'route_name' => 'merchant.coupon.edit',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            80 => 
            array (
                'id' => 6107,
                'route_name' => 'merchant.coupon.update',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            81 => 
            array (
                'id' => 6108,
                'route_name' => 'merchant.plan.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            82 => 
            array (
                'id' => 6109,
                'route_name' => 'merchant.plan.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            83 => 
            array (
                'id' => 6110,
                'route_name' => 'merchant.plan.destroy',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            84 => 
            array (
                'id' => 6111,
                'route_name' => 'merchant.plan.create',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            85 => 
            array (
                'id' => 6112,
                'route_name' => 'merchant.plan.store',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            86 => 
            array (
                'id' => 6113,
                'route_name' => 'merchant.plan.edit',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            87 => 
            array (
                'id' => 6114,
                'route_name' => 'merchant.plan.update',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            88 => 
            array (
                'id' => 6115,
                'route_name' => 'merchant.staff.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            89 => 
            array (
                'id' => 6116,
                'route_name' => 'merchant.staff.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            90 => 
            array (
                'id' => 6117,
                'route_name' => 'merchant.staff.destroy',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            91 => 
            array (
                'id' => 6118,
                'route_name' => 'merchant.staff.create',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            92 => 
            array (
                'id' => 6119,
                'route_name' => 'merchant.staff.store',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            93 => 
            array (
                'id' => 6120,
                'route_name' => 'merchant.staff.edit',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            94 => 
            array (
                'id' => 6121,
                'route_name' => 'merchant.staff.update',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            95 => 
            array (
                'id' => 6122,
                'route_name' => 'merchant.order.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            96 => 
            array (
                'id' => 6123,
                'route_name' => 'merchant.order.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            97 => 
            array (
                'id' => 6124,
                'route_name' => 'merchant.order.destroy',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            98 => 
            array (
                'id' => 6125,
                'route_name' => 'merchant.order.create',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            99 => 
            array (
                'id' => 6126,
                'route_name' => 'merchant.order.store',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            100 => 
            array (
                'id' => 6127,
                'route_name' => 'merchant.order.edit',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            101 => 
            array (
                'id' => 6128,
                'route_name' => 'merchant.order.update',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            102 => 
            array (
                'id' => 6129,
                'route_name' => 'merchant.order.qrcode',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            103 => 
            array (
                'id' => 6130,
                'route_name' => 'payment.sdk.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            104 => 
            array (
                'id' => 6131,
                'route_name' => 'payment.sdk.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            105 => 
            array (
                'id' => 6132,
                'route_name' => 'payment.sdk.destroy',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            106 => 
            array (
                'id' => 6133,
                'route_name' => 'payment.sdk.create',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            107 => 
            array (
                'id' => 6134,
                'route_name' => 'payment.sdk.store',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            108 => 
            array (
                'id' => 6135,
                'route_name' => 'payment.sdk.edit',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            109 => 
            array (
                'id' => 6136,
                'route_name' => 'payment.sdk.update',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            110 => 
            array (
                'id' => 6137,
                'route_name' => 'payment.service-api.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            111 => 
            array (
                'id' => 6138,
                'route_name' => 'payment.service-api.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            112 => 
            array (
                'id' => 6139,
                'route_name' => 'payment.service-api.destroy',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            113 => 
            array (
                'id' => 6140,
                'route_name' => 'payment.service-api.create',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            114 => 
            array (
                'id' => 6141,
                'route_name' => 'payment.service-api.store',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            115 => 
            array (
                'id' => 6142,
                'route_name' => 'payment.service-api.edit',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            116 => 
            array (
                'id' => 6143,
                'route_name' => 'payment.service-api.update',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            117 => 
            array (
                'id' => 6144,
                'route_name' => 'payment.service-api-parameters.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            118 => 
            array (
                'id' => 6145,
                'route_name' => 'payment.service-api-parameters.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            119 => 
            array (
                'id' => 6146,
                'route_name' => 'payment.service-api-parameters.destroy',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            120 => 
            array (
                'id' => 6147,
                'route_name' => 'payment.service-api-parameters.create',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            121 => 
            array (
                'id' => 6148,
                'route_name' => 'payment.service-api-parameters.store',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            122 => 
            array (
                'id' => 6149,
                'route_name' => 'payment.service-api-parameters.edit',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            123 => 
            array (
                'id' => 6150,
                'route_name' => 'payment.service-api-parameters.update',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            124 => 
            array (
                'id' => 6151,
                'route_name' => 'payment.services.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            125 => 
            array (
                'id' => 6152,
                'route_name' => 'payment.services.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            126 => 
            array (
                'id' => 6153,
                'route_name' => 'payment.services.destroy',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            127 => 
            array (
                'id' => 6154,
                'route_name' => 'payment.services.create',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            128 => 
            array (
                'id' => 6155,
                'route_name' => 'payment.services.store',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            129 => 
            array (
                'id' => 6156,
                'route_name' => 'payment.services.edit',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            130 => 
            array (
                'id' => 6157,
                'route_name' => 'payment.services.update',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            131 => 
            array (
                'id' => 6158,
                'route_name' => 'payment.service-providers.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            132 => 
            array (
                'id' => 6159,
                'route_name' => 'payment.service-providers.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            133 => 
            array (
                'id' => 6160,
                'route_name' => 'payment.service-providers.destroy',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            134 => 
            array (
                'id' => 6161,
                'route_name' => 'payment.service-providers.create',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            135 => 
            array (
                'id' => 6162,
                'route_name' => 'payment.service-providers.store',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            136 => 
            array (
                'id' => 6163,
                'route_name' => 'payment.service-providers.edit',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            137 => 
            array (
                'id' => 6164,
                'route_name' => 'payment.service-providers.update',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            138 => 
            array (
                'id' => 6165,
                'route_name' => 'payment.service-provider-categories.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            139 => 
            array (
                'id' => 6166,
                'route_name' => 'payment.service-provider-categories.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            140 => 
            array (
                'id' => 6167,
                'route_name' => 'payment.service-provider-categories.destroy',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            141 => 
            array (
                'id' => 6168,
                'route_name' => 'payment.service-provider-categories.create',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            142 => 
            array (
                'id' => 6169,
                'route_name' => 'payment.service-provider-categories.store',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            143 => 
            array (
                'id' => 6170,
                'route_name' => 'payment.service-provider-categories.edit',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            144 => 
            array (
                'id' => 6171,
                'route_name' => 'payment.service-provider-categories.update',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            145 => 
            array (
                'id' => 6172,
                'route_name' => 'payment.output.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            146 => 
            array (
                'id' => 6173,
                'route_name' => 'payment.output.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            147 => 
            array (
                'id' => 6174,
                'route_name' => 'payment.output.destroy',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            148 => 
            array (
                'id' => 6175,
                'route_name' => 'payment.output.create',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            149 => 
            array (
                'id' => 6176,
                'route_name' => 'payment.output.store',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            150 => 
            array (
                'id' => 6177,
                'route_name' => 'payment.output.edit',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            151 => 
            array (
                'id' => 6178,
                'route_name' => 'payment.output.update',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            152 => 
            array (
                'id' => 6179,
                'route_name' => 'payment.invoice.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            153 => 
            array (
                'id' => 6180,
                'route_name' => 'payment.invoice.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            154 => 
            array (
                'id' => 6181,
                'route_name' => 'payment.invoice.destroy',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            155 => 
            array (
                'id' => 6182,
                'route_name' => 'payment.invoice.create',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            156 => 
            array (
                'id' => 6183,
                'route_name' => 'payment.invoice.store',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            157 => 
            array (
                'id' => 6184,
                'route_name' => 'payment.invoice.edit',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            158 => 
            array (
                'id' => 6185,
                'route_name' => 'payment.invoice.update',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            159 => 
            array (
                'id' => 6186,
                'route_name' => 'payment.invoice.change-status',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            160 => 
            array (
                'id' => 6187,
                'route_name' => 'payment.transactions.list',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            161 => 
            array (
                'id' => 6188,
                'route_name' => 'payment.transactions.ajax-details',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            162 => 
            array (
                'id' => 6189,
                'route_name' => 'system.commission-list.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            163 => 
            array (
                'id' => 6190,
                'route_name' => 'system.commission-list.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            164 => 
            array (
                'id' => 6191,
                'route_name' => 'system.commission-list.destroy',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            165 => 
            array (
                'id' => 6192,
                'route_name' => 'system.commission-list.create',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            166 => 
            array (
                'id' => 6193,
                'route_name' => 'system.commission-list.store',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            167 => 
            array (
                'id' => 6194,
                'route_name' => 'system.commission-list.edit',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            168 => 
            array (
                'id' => 6195,
                'route_name' => 'system.commission-list.update',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            169 => 
            array (
                'id' => 6196,
                'route_name' => 'system.system-knowledge.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            170 => 
            array (
                'id' => 6197,
                'route_name' => 'system.system-knowledge.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            171 => 
            array (
                'id' => 6198,
                'route_name' => 'system.system-knowledge.destroy',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            172 => 
            array (
                'id' => 6199,
                'route_name' => 'system.system-knowledge.create',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            173 => 
            array (
                'id' => 6200,
                'route_name' => 'system.system-knowledge.store',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            174 => 
            array (
                'id' => 6201,
                'route_name' => 'system.system-knowledge.edit',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            175 => 
            array (
                'id' => 6202,
                'route_name' => 'system.system-knowledge.update',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            176 => 
            array (
                'id' => 6203,
                'route_name' => 'system.system-knowledge.search',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            177 => 
            array (
                'id' => 6204,
                'route_name' => 'system.permission-group.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            178 => 
            array (
                'id' => 6205,
                'route_name' => 'system.permission-group.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            179 => 
            array (
                'id' => 6206,
                'route_name' => 'system.permission-group.destroy',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            180 => 
            array (
                'id' => 6207,
                'route_name' => 'system.permission-group.create',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            181 => 
            array (
                'id' => 6208,
                'route_name' => 'system.permission-group.store',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            182 => 
            array (
                'id' => 6209,
                'route_name' => 'system.permission-group.edit',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            183 => 
            array (
                'id' => 6210,
                'route_name' => 'system.permission-group.update',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            184 => 
            array (
                'id' => 6211,
                'route_name' => 'system.tickets.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            185 => 
            array (
                'id' => 6212,
                'route_name' => 'system.tickets.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            186 => 
            array (
                'id' => 6213,
                'route_name' => 'system.tickets.comment',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            187 => 
            array (
                'id' => 6214,
                'route_name' => 'system.tickets.status',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            188 => 
            array (
                'id' => 6215,
                'route_name' => 'system.tickets.destroy',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            189 => 
            array (
                'id' => 6216,
                'route_name' => 'system.tickets.create',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            190 => 
            array (
                'id' => 6217,
                'route_name' => 'system.tickets.store',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            191 => 
            array (
                'id' => 6218,
                'route_name' => 'system.tickets.edit',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            192 => 
            array (
                'id' => 6219,
                'route_name' => 'system.tickets.update',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            193 => 
            array (
                'id' => 6220,
                'route_name' => 'system.call-tracking.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            194 => 
            array (
                'id' => 6221,
                'route_name' => 'system.call-tracking.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            195 => 
            array (
                'id' => 6222,
                'route_name' => 'system.call-tracking.destroy',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            196 => 
            array (
                'id' => 6223,
                'route_name' => 'system.call-tracking.create',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            197 => 
            array (
                'id' => 6224,
                'route_name' => 'system.call-tracking.store',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            198 => 
            array (
                'id' => 6225,
                'route_name' => 'system.call-tracking.edit',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            199 => 
            array (
                'id' => 6226,
                'route_name' => 'system.call-tracking.update',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            200 => 
            array (
                'id' => 6227,
                'route_name' => 'system.users.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            201 => 
            array (
                'id' => 6228,
                'route_name' => 'system.users.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            202 => 
            array (
                'id' => 6229,
                'route_name' => 'system.users.destroy',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            203 => 
            array (
                'id' => 6230,
                'route_name' => 'system.users.create',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            204 => 
            array (
                'id' => 6231,
                'route_name' => 'system.users.store',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            205 => 
            array (
                'id' => 6232,
                'route_name' => 'system.users.edit',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            206 => 
            array (
                'id' => 6233,
                'route_name' => 'system.users.update',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            207 => 
            array (
                'id' => 6234,
                'route_name' => 'system.staff.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            208 => 
            array (
                'id' => 6235,
                'route_name' => 'system.staff.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            209 => 
            array (
                'id' => 6236,
                'route_name' => 'system.staff.destroy',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            210 => 
            array (
                'id' => 6237,
                'route_name' => 'system.staff.create',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            211 => 
            array (
                'id' => 6238,
                'route_name' => 'system.staff.store',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            212 => 
            array (
                'id' => 6239,
                'route_name' => 'system.staff.edit',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            213 => 
            array (
                'id' => 6240,
                'route_name' => 'system.staff.update',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            214 => 
            array (
                'id' => 6241,
                'route_name' => 'system.staff.add-managed-staff',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            215 => 
            array (
                'id' => 6242,
                'route_name' => 'system.staff.delete-managed-staff',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            216 => 
            array (
                'id' => 6243,
                'route_name' => 'show-tree-users-data',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            217 => 
            array (
                'id' => 6244,
                'route_name' => 'system.staff-target.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            218 => 
            array (
                'id' => 6245,
                'route_name' => 'system.staff-target.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            219 => 
            array (
                'id' => 6246,
                'route_name' => 'system.staff-target.destroy',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            220 => 
            array (
                'id' => 6247,
                'route_name' => 'system.staff-target.create',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            221 => 
            array (
                'id' => 6248,
                'route_name' => 'system.staff-target.store',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            222 => 
            array (
                'id' => 6249,
                'route_name' => 'system.staff-target.edit',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            223 => 
            array (
                'id' => 6250,
                'route_name' => 'system.staff-target.update',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            224 => 
            array (
                'id' => 6251,
                'route_name' => 'system.sender.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            225 => 
            array (
                'id' => 6252,
                'route_name' => 'system.sender.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            226 => 
            array (
                'id' => 6253,
                'route_name' => 'system.sender.destroy',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            227 => 
            array (
                'id' => 6254,
                'route_name' => 'system.sender.create',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            228 => 
            array (
                'id' => 6255,
                'route_name' => 'system.sender.store',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            229 => 
            array (
                'id' => 6256,
                'route_name' => 'system.area-type.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            230 => 
            array (
                'id' => 6257,
                'route_name' => 'system.area-type.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            231 => 
            array (
                'id' => 6258,
                'route_name' => 'system.area-type.destroy',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            232 => 
            array (
                'id' => 6259,
                'route_name' => 'system.area-type.create',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            233 => 
            array (
                'id' => 6260,
                'route_name' => 'system.area-type.store',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            234 => 
            array (
                'id' => 6261,
                'route_name' => 'system.area-type.edit',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            235 => 
            array (
                'id' => 6262,
                'route_name' => 'system.area-type.update',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            236 => 
            array (
                'id' => 6263,
                'route_name' => 'system.area.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            237 => 
            array (
                'id' => 6264,
                'route_name' => 'system.area.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            238 => 
            array (
                'id' => 6265,
                'route_name' => 'system.area.destroy',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            239 => 
            array (
                'id' => 6266,
                'route_name' => 'system.area.create',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            240 => 
            array (
                'id' => 6267,
                'route_name' => 'system.area.store',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            241 => 
            array (
                'id' => 6268,
                'route_name' => 'system.area.edit',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            242 => 
            array (
                'id' => 6269,
                'route_name' => 'system.area.update',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            243 => 
            array (
                'id' => 6270,
                'route_name' => 'system.advertisement.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            244 => 
            array (
                'id' => 6271,
                'route_name' => 'system.advertisement.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            245 => 
            array (
                'id' => 6272,
                'route_name' => 'system.advertisement.destroy',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            246 => 
            array (
                'id' => 6273,
                'route_name' => 'system.advertisement.create',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            247 => 
            array (
                'id' => 6274,
                'route_name' => 'system.advertisement.store',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            248 => 
            array (
                'id' => 6275,
                'route_name' => 'system.advertisement.edit',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            249 => 
            array (
                'id' => 6276,
                'route_name' => 'system.advertisement.update',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            250 => 
            array (
                'id' => 6277,
                'route_name' => 'system.news.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            251 => 
            array (
                'id' => 6278,
                'route_name' => 'system.news.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            252 => 
            array (
                'id' => 6279,
                'route_name' => 'system.news.destroy',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            253 => 
            array (
                'id' => 6280,
                'route_name' => 'system.news.create',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            254 => 
            array (
                'id' => 6281,
                'route_name' => 'system.news.store',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            255 => 
            array (
                'id' => 6282,
                'route_name' => 'system.news.edit',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            256 => 
            array (
                'id' => 6283,
                'route_name' => 'system.news.update',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            257 => 
            array (
                'id' => 6284,
                'route_name' => 'system.news-category.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            258 => 
            array (
                'id' => 6285,
                'route_name' => 'system.news-category.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            259 => 
            array (
                'id' => 6286,
                'route_name' => 'system.news-category.destroy',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            260 => 
            array (
                'id' => 6287,
                'route_name' => 'system.news-category.create',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            261 => 
            array (
                'id' => 6288,
                'route_name' => 'system.news-category.store',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            262 => 
            array (
                'id' => 6289,
                'route_name' => 'system.news-category.edit',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            263 => 
            array (
                'id' => 6290,
                'route_name' => 'system.news-category.update',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            264 => 
            array (
                'id' => 6291,
                'route_name' => 'system.banks.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            265 => 
            array (
                'id' => 6292,
                'route_name' => 'system.banks.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            266 => 
            array (
                'id' => 6293,
                'route_name' => 'system.banks.destroy',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            267 => 
            array (
                'id' => 6294,
                'route_name' => 'system.banks.create',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            268 => 
            array (
                'id' => 6295,
                'route_name' => 'system.banks.store',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            269 => 
            array (
                'id' => 6296,
                'route_name' => 'system.banks.edit',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            270 => 
            array (
                'id' => 6297,
                'route_name' => 'system.banks.update',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            271 => 
            array (
                'id' => 6298,
                'route_name' => 'system.marketing-message.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            272 => 
            array (
                'id' => 6299,
                'route_name' => 'system.marketing-message.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            273 => 
            array (
                'id' => 6300,
                'route_name' => 'system.marketing-message.destroy',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            274 => 
            array (
                'id' => 6301,
                'route_name' => 'system.marketing-message.create',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            275 => 
            array (
                'id' => 6302,
                'route_name' => 'system.marketing-message.store',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            276 => 
            array (
                'id' => 6303,
                'route_name' => 'system.marketing-message.edit',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            277 => 
            array (
                'id' => 6304,
                'route_name' => 'system.marketing-message.update',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            278 => 
            array (
                'id' => 6305,
                'route_name' => 'system.loyalty-program-ignore.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            279 => 
            array (
                'id' => 6306,
                'route_name' => 'system.loyalty-program-ignore.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            280 => 
            array (
                'id' => 6307,
                'route_name' => 'system.loyalty-program-ignore.destroy',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            281 => 
            array (
                'id' => 6308,
                'route_name' => 'system.loyalty-program-ignore.create',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            282 => 
            array (
                'id' => 6309,
                'route_name' => 'system.loyalty-program-ignore.store',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            283 => 
            array (
                'id' => 6310,
                'route_name' => 'system.loyalty-program-ignore.edit',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            284 => 
            array (
                'id' => 6311,
                'route_name' => 'system.loyalty-program-ignore.update',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            285 => 
            array (
                'id' => 6312,
                'route_name' => 'system.loyalty-programs.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            286 => 
            array (
                'id' => 6313,
                'route_name' => 'system.loyalty-programs.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            287 => 
            array (
                'id' => 6314,
                'route_name' => 'system.loyalty-programs.destroy',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            288 => 
            array (
                'id' => 6315,
                'route_name' => 'system.loyalty-programs.create',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            289 => 
            array (
                'id' => 6316,
                'route_name' => 'system.loyalty-programs.store',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            290 => 
            array (
                'id' => 6317,
                'route_name' => 'system.loyalty-programs.edit',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            291 => 
            array (
                'id' => 6318,
                'route_name' => 'system.loyalty-programs.update',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            292 => 
            array (
                'id' => 6319,
                'route_name' => 'system.activity-log.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            293 => 
            array (
                'id' => 6320,
                'route_name' => 'system.wallet.transactions',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            294 => 
            array (
                'id' => 6321,
                'route_name' => 'system.wallet.transactions.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            295 => 
            array (
                'id' => 6322,
                'route_name' => 'system.wallet.main-wallets',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            296 => 
            array (
                'id' => 6323,
                'route_name' => 'system.wallet.transfer-money-supervisor',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            297 => 
            array (
                'id' => 6324,
                'route_name' => 'system.wallet.transfer-money-supervisor.post',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            298 => 
            array (
                'id' => 6325,
                'route_name' => 'system.wallet.transfer-money-staff',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            299 => 
            array (
                'id' => 6326,
                'route_name' => 'system.wallet.transfer-money-staff.post',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            300 => 
            array (
                'id' => 6327,
                'route_name' => 'system.wallet.transfer-money-main-wallets',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            301 => 
            array (
                'id' => 6328,
                'route_name' => 'system.wallet.transfer-money-main-wallets.post',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            302 => 
            array (
                'id' => 6329,
                'route_name' => 'system.wallet.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            303 => 
            array (
                'id' => 6330,
                'route_name' => 'system.wallet.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            304 => 
            array (
                'id' => 6331,
                'route_name' => 'system.loyalty-wallet.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            305 => 
            array (
                'id' => 6332,
                'route_name' => 'system.loyalty-wallet.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            306 => 
            array (
                'id' => 6333,
                'route_name' => 'system.settlement.generate-report',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            307 => 
            array (
                'id' => 6334,
                'route_name' => 'system.settlement.generate-report-port',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            308 => 
            array (
                'id' => 6335,
                'route_name' => 'system.settlement.generate-report-ajax',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            309 => 
            array (
                'id' => 6336,
                'route_name' => 'system.settlement.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            310 => 
            array (
                'id' => 6337,
                'route_name' => 'system.settlement.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            311 => 
            array (
                'id' => 6338,
                'route_name' => 'system.audio-messages.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            312 => 
            array (
                'id' => 6339,
                'route_name' => 'system.audio-messages.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            313 => 
            array (
                'id' => 6340,
                'route_name' => 'system.setting.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            314 => 
            array (
                'id' => 6341,
                'route_name' => 'system.setting.update',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            315 => 
            array (
                'id' => 6342,
                'route_name' => 'system.activity-log.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            316 => 
            array (
                'id' => 6343,
                'route_name' => 'system.activity-log.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            317 => 
            array (
                'id' => 6344,
                'route_name' => 'system.appointment.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            318 => 
            array (
                'id' => 6345,
                'route_name' => 'system.appointment.show',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            319 => 
            array (
                'id' => 6346,
                'route_name' => 'system.appointment.destroy',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            320 => 
            array (
                'id' => 6347,
                'route_name' => 'system.appointment.change-status',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            321 => 
            array (
                'id' => 6348,
                'route_name' => 'system.appointment.change-appointment-datetime',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            322 => 
            array (
                'id' => 6349,
                'route_name' => 'system.chat.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            323 => 
            array (
                'id' => 6350,
                'route_name' => 'system.chat.get-conversation',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            324 => 
            array (
                'id' => 6351,
                'route_name' => 'system.access-data.index',
                'permission_group_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}