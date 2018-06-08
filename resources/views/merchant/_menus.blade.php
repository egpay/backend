<?php

    $menus['Dashboard'] = [
        'permission'=> ['panel.merchant.home'],
        'class'=>'',
        'icon'=>'ft-home',
        'text'=>__('Home'),
        'sub'=>[
            'Home'=> [
                'permission'=> 'panel.merchant.home',
                'url'=> route('panel.merchant.home'),
                'text'=> __('Merchant Statistics'),
            ],
            'News'=> [
                'permission'=> 'panel.merchant.news.home',
                'url'=> route('panel.merchant.news.home'),
                'text'=> __('News'),
            ],
        ]
    ];

    /*
    * Sub Merchant
    */
    if(request()->user()->merchant()->is_reseller == 'active'){
        $menus['SubMerchant'] = [
            'permission'=> ['panel.merchant.sub-merchant.index','panel.merchant.sub-merchant.create',
            'panel.merchant.sub-merchant-employee.index','panel.merchant.sub-merchant-employee.create',
            'panel.merchant.sub-merchant.requested','panel.merchant.sub-merchant.requested.edit','panel.merchant.sub-merchant.requested.update',
            ],
            'class'=>'',
            'icon'=>'fa fa-window-restore',
            'text'=>__('Sub-Merchant'),
            'sub'=>[
                'SubMerchant'=> [
                    'permission'=> 'panel.merchant.sub-merchant.index',
                    'url'=> route('panel.merchant.sub-merchant.index'),
                    'text'=> __('Sub-Merchant'),
                    'sub'=>[
                        'SubMerchant'=> [
                            'permission'=> 'panel.merchant.sub-merchant.index',
                            'url'=> route('panel.merchant.sub-merchant.index'),
                            'text'=> __('Sub-Merchant'),
                        ],
                        'NonReviewed'=> [
                            'permission'=> 'panel.merchant.sub-merchant.requested',
                            'url'=> route('panel.merchant.sub-merchant.requested'),
                            'text'=> __('Non-Reviewed Merchants'),
                        ],
                        'New Sub-Merchant'=> [
                            'permission'=> 'panel.merchant.sub-merchant.create',
                            'url'=> route('panel.merchant.sub-merchant.create'),
                            'text'=> __('New Sub-Merchant'),
                        ],
                    ]
                ],
            ]
        ];
    }

    /*
    * Merchant Branches
    */
    $menus['Branches'] = [
        'permission'=> ['panel.merchant.branch.index','panel.merchant.branch.create'],
        'class'=>'',
        'icon'=>'fa fa-database',
        'text'=>__('Branches'),
        'sub'=>[
            'Branches'=> [
                'permission'=> 'panel.merchant.branch.index',
                'url'=> route('panel.merchant.branch.index'),
                'text'=> __('Branches'),
            ],
            'New Branch'=> [
                'permission'=> 'panel.merchant.branch.create',
                'url'=> route('panel.merchant.branch.create'),
                'text'=> __('New Branch'),
            ],
        ]
    ];


    /*
    * Permissions & groups
    */
    $menus['groups_and_staff'] = [
        'permission'=> ['panel.merchant.staff-group.index','panel.merchant.staff-group.create','panel.merchant.employee.index','panel.merchant.employee.create'],
        'class'=>'',
        'icon'=>'fa fa-lock',
        'text'=>__('Permissions & Groups'),
        'sub'=>[
            'SubMerchant'=> [
                'permission'=> 'panel.merchant.staff-group.index',
                'url'=> route('panel.merchant.staff-group.index'),
                'text'=> __('Groups'),
                'sub'=>[
                    'SubMerchant'=> [
                        'permission'=> 'panel.merchant.staff-group.index',
                        'url'=> route('panel.merchant.staff-group.index'),
                        'text'=> __('Groups'),
                    ],
                    'New Sub-Merchant'=> [
                        'permission'=> 'panel.merchant.staff-group.create',
                        'url'=> route('panel.merchant.staff-group.create'),
                        'text'=> __('New Group'),
                    ],
                ]
            ],
            'employee'=> [
                'permission'=> ['panel.merchant.employee.index','panel.merchant.employee.create'],
                'url'=> route('panel.merchant.sub-merchant-employee.index'),
                'text'=> __('Employees'),
                'sub'=>[
                    'Employees'=> [
                        'permission'=> 'panel.merchant.employee.index',
                        'url'=> route('panel.merchant.employee.index'),
                        'text'=> __('Employees'),
                    ],
                    'NewEmployee'=> [
                        'permission'=> 'panel.merchant.employee.create',
                        'url'=> route('panel.merchant.employee.create'),
                        'text'=> __('New Employee'),
                    ],
                ]
            ],
        ]
    ];


    /*
    * E-Payment
    */
    $menus['ePayment'] = [
        'permission'=> ['panel.merchant.payment.index','panel.merchant.payment.invoice.index','panel.merchant.payment.transactions.list'],
        'class'=>'',
        'icon'=>'fa fa-money',
        'text'=>__('E-Payment'),
        'sub'=>[
            'services'=> [
                'permission'=> 'panel.merchant.payment.index',
                'url'=> route('panel.merchant.payment.index'),
                'text'=> __('Payment Services'),
            ],
            'transfer'=> [
                'permission'=> 'panel.merchant.payment.transfer',
                'url'=> route('panel.merchant.payment.transfer'),
                'text'=> __('Transfer'),
            ],
            'invoice'=> [
                'permission'=> 'panel.merchant.payment.invoice.index',
                'url'=> route('panel.merchant.payment.invoice.index'),
                'text'=> __('Invoices'),
            ],
            'transactions'=> [
                'permission'=> 'panel.merchant.payment.transactions.list',
                'url'=> route('panel.merchant.payment.transactions.list'),
                'text'=> __('Transactions'),
            ],
        ]
    ];


    /*
    * E-Commerce
    */
    $menus['eCommerce'] = [
        'permission'=> ['panel.merchant.order.index','panel.merchant.product-category.index','panel.merchant.product-category.create','panel.merchant.product.index','panel.merchant.product.create','panel.merchant.product.edit'],
        'class'=>'',
        'icon'=>'fa fa-usd',
        'text'=>__('E-Commerce'),
        'sub'=>[
            'productCategories'=> [
                'permission'=> ['panel.merchant.product-category.index','panel.merchant.product-category.create'],
                'url'=> route('panel.merchant.product-category.index'),
                'text'=> __('Categories'),
                'sub'=>[
                    'categories'=> [
                        'permission'=> 'panel.merchant.product-category.index',
                        'url'=> route('panel.merchant.product-category.index'),
                        'text'=> __('Categories'),
                    ],
                    'New Category'=> [
                        'permission'=> 'panel.merchant.product-category.create',
                        'url'=> route('panel.merchant.product-category.create'),
                        'text'=> __('New category'),
                    ],
                ]
            ],
            'products'=> [
                'permission'=> ['panel.merchant.product.index','panel.merchant.product.create'],
                'url'=> route('panel.merchant.product.index'),
                'text'=> __('Products'),
                'sub'=>[
                    'products'=> [
                        'permission'=> 'panel.merchant.product.index',
                        'url'=> route('panel.merchant.product.index'),
                        'text'=> __('Products'),
                    ],
                    'newProducts'=> [
                        'permission'=> 'panel.merchant.product.create',
                        'url'=> route('panel.merchant.product.create'),
                        'text'=> __('New Product'),
                    ],
                ]
            ],
            'ProductAttributes'=> [
                'permission'=> ['panel.merchant.product-attribute.index','panel.merchant.product-attribute.create'],
                'url'=> route('panel.merchant.product-attribute.index'),
                'text'=> __('Product Attributes'),
                'sub'=>[
                    'orders'=> [
                        'permission'=> 'panel.merchant.product-attribute.index',
                        'url'=> route('panel.merchant.product-attribute.index'),
                        'text'=> __('Product Attributes'),
                    ],
                    'newProducts'=> [
                        'permission'=> 'panel.merchant.product-attribute.create',
                        'url'=> route('panel.merchant.product-attribute.create'),
                        'text'=> __('New Product Attributes'),
                    ],
                ]
            ],
            'orders'=> [
                'permission'=> ['panel.merchant.order.index','panel.merchant.order.create'],
                'url'=> route('panel.merchant.order.index'),
                'text'=> __('Orders'),
                'sub'=>[
                    'orders'=> [
                        'permission'=> 'panel.merchant.order.index',
                        'url'=> route('panel.merchant.order.index'),
                        'text'=> __('Orders'),
                    ],
                    'newProducts'=> [
                        'permission'=> 'panel.merchant.order.create',
                        'url'=> route('panel.merchant.order.create'),
                        'text'=> __('New Order'),
                    ],
                ]
            ],
        ]
    ];


    $menus['wallet'] = [
        'permission'=> ['panel.merchant.wallet.index','panel.merchant.wallet.transactions','panel.merchant.wallet.transactions.show'],
        'class'=>'',
        'icon'=>'fa fa-usd',
        'text'=>__('Wallet'),
        'sub'=>[
            'Banks'=> [
                'permission'=> 'panel.merchant.wallet.index',
                'url'=> route('panel.merchant.wallet.index'),
                'text'=> __('Wallet'),
            ],
        ]
    ];



    /*
     * Bank Accounts
     */
    $menus['banks'] = [
        'permission'=> ['panel.merchant.bank.index','panel.merchant.bank.create'],
        'class'=>'',
        'icon'=>'fa fa-bank',
        'text'=>__('Banks'),
        'sub'=>[
            'Banks'=> [
                'permission'=> 'panel.merchant.bank.index',
                'url'=> route('panel.merchant.bank.index'),
                'text'=> __('Bank Accounts'),
            ],
            'newBank'=> [
                'permission'=> 'panel.merchant.bank.create',
                'url'=> route('panel.merchant.bank.create'),
                'text'=> __('New Bank Acc'),
            ],
        ]
    ];




foreach($menus as $onemenu){
    echo GenerateHorizMenu($onemenu);
}