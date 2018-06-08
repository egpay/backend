@php


    $menu['Dashboard'] = [
           'url'=> route('system.dashboard'),
           'icon'=>'ft-home',
           'text'=>__('Dashboard'),
    ];

    $menu['Chat'] = [
           'permission'=> [
                'system.chat.index'
           ],
           'class'=>'',
           'icon'=>'fa fa-commenting-o',
           'text'=>__('Chat'),
           'url'=> route('system.chat.index'),

       ];

    $menu['Audio-Messages'] = [
           'permission'=> [
                'system.audio-messages.index'
           ],
           'class'=>'',
           'icon'=>'fa fa-file-audio-o',
           'text'=>__('Audio Messages'),
           'url'=> route('system.audio-messages.index'),

       ];

    $menu['Email'] = [
           'permission'=> [
                'system.system-ticket.index'
           ],
           'class'=>'',
           'icon'=>'fa fa-envelope-o',
           'text'=>__('Email'),
           'url'=> route('system.system-ticket.index'),

       ];

    $menu['Appointments'] = [
           'permission'=> [
                'system.appointment.index'
           ],
           'class'=>'',
           'icon'=>'fa fa-clock-o',
           'text'=>__('Appointments'),
           'url'=> route('system.appointment.index'),

       ];






    $menu['Merchants'] = [
           'permission'=> [
                'merchant.category.index','merchant.category.create',
                'merchant.plan.index','merchant.plan.create',
                'merchant.merchant.index','merchant.merchant.create','merchant.merchant.fast-create',
                'merchant.merchant.review',
                'merchant.branch.index','merchant.branch.create',
                'merchant.contract.index','merchant.contract.create',
                'merchant.product-attributes-category.index','merchant.product-attributes-category.create',
                'merchant.product-attributes.index','merchant.product-attributes.create',
                'merchant.product.index','merchant.product.create',
                'merchant.order.index','merchant.order.create',
                'merchant.staff-group.index','merchant.staff-group.create',
                'merchant.staff.index','merchant.staff.create','merchant.staff.edit',
                'merchant.coupon.index','merchant.coupon.create','merchant.coupon.edit'

           ],
           'class'=>'',
           'icon'=>'fa fa-building',
           'text'=>__('Merchants'),
           'sub'=>[

               'Categories'=> [
                   'permission'=> [
                       'merchant.category.index',
                       'merchant.category.create'
                   ],
                   'icon'=>'fa fa-sitemap',
                   'text'=> __('Categories'),
                   'sub'=>[
                       [
                           'permission'=> 'merchant.category.index',
                           'url'=> route('merchant.category.index'),
                           'text'=> __('View Categories')
                       ],
                       [
                           'permission'=> 'merchant.category.create',
                           'url'=> route('merchant.category.create'),
                           'text'=> __('Create Category')
                       ],
                       [
                           'aClass'=> 'color-red',
                           'permission'=> 'merchant.category.index',
                           'url'=> route('merchant.category.index',['withTrashed'=>1]),
                           'text'=> __('Trashed Categories')
                       ],

                   ]
               ],
               'Plans'=> [
                   'permission'=> [
                       'merchant.plan.index',
                       'merchant.plan.create',
                       'merchant.plan.edit'
                   ],
                   'icon'=>'fa fa-paper-plane',
                   'text'=> __('Plans'),
                   'sub'=>[
                       [
                           'permission'=> 'merchant.plan.index',
                           'url'=> route('merchant.plan.index'),
                           'text'=> __('View Plans')
                       ],
                       [
                           'permission'=> 'merchant.plan.create',
                           'url'=> route('merchant.plan.create'),
                           'text'=> __('Create Plan')
                       ],
                       [
                           'aClass'=> 'color-red',
                           'permission'=> 'merchant.plan.index',
                           'url'=> route('merchant.plan.index',['withTrashed'=>1]),
                           'text'=> __('Trashed Plans')
                       ],

                   ]
               ],


               'Merchants'=> [
                   'permission'=> [
                       'merchant.merchant.index',
                       'merchant.merchant.edit',
                       'merchant.merchant.create','merchant.merchant.fast-create',
                       'merchant.merchant.review',
                   ],
                   'icon'=>'fa fa-building',
                   'text'=> __('Merchants'),
                   'sub'=>[
                       [
                           'permission'=> 'merchant.merchant.index',
                           'url'=> route('merchant.merchant.index'),
                           'text'=> __('View Merchants')
                       ],
                       [
                           'permission'=> 'merchant.merchant.review',
                           'url'=> route('merchant.merchant.review'),
                           'text'=> __('Review Merchants')
                       ],
                       [
                           'permission'=> 'merchant.merchant.fast-create',
                           'url'=> route('merchant.merchant.fast-create'),
                           'text'=> __('Create Merchant')
                       ],
                       [
                           'aClass'=> 'color-red',
                           'permission'=> 'merchant.merchant.index',
                           'url'=> route('merchant.merchant.index',['withTrashed'=>1]),
                           'text'=> __('Trashed Merchant')
                       ],

                   ]
               ],
               'Branches'=> [
                   'permission'=> [
                       'merchant.branch.index',
                       'merchant.branch.create',
                       'merchant.branch.edit'
                   ],
                   'icon'=>'fa fa-building-o',
                   'text'=> __('Branches'),
                   'sub'=>[
                       [
                           'permission'=> 'merchant.branch.index',
                           'url'=> route('merchant.branch.index'),
                           'text'=> __('View Branches')
                       ],
                       [
                           'permission'=> 'merchant.branch.create',
                           'url'=> route('merchant.branch.create'),
                           'text'=> __('Create Branch')
                       ],
                       [
                           'aClass'=> 'color-red',
                           'permission'=> 'merchant.branch.index',
                           'url'=> route('merchant.branch.index',['withTrashed'=>1]),
                           'text'=> __('Trashed Branchs')
                       ],

                   ]
               ],
               'Contract'=> [
                   'permission'=> [
                       'merchant.contract.index',
                       'merchant.contract.create',
                       'merchant.contract.edit'
                   ],
                   'icon'=>'fa fa-paperclip',
                   'text'=> __('Contracts'),
                   'sub'=>[
                       [
                           'permission'=> 'merchant.contract.index',
                           'url'=> route('merchant.contract.index'),
                           'text'=> __('View Contracts')
                       ],
                       [
                           'permission'=> 'merchant.contract.create',
                           'url'=> route('merchant.contract.create'),
                           'text'=> __('Create Contract')
                       ],
                       [
                           'aClass'=> 'color-red',
                           'permission'=> 'merchant.contract.index',
                           'url'=> route('merchant.contract.index',['withTrashed'=>1]),
                           'text'=> __('Trashed Contracts')
                       ],

                   ]
               ],
                'ProductAttributesCategories'=> [
                   'permission'=> [
                       'merchant.product-attributes-category.index',
                       'merchant.product-attributes-category.create',
                       'merchant.product-attributes-category.edit'
                   ],
                   'icon'=>'fa fa-pagelines',
                   'text'=> __('Attribute Categories'),
                   'sub'=>[
                       [
                           'permission'=> 'merchant.product-attributes-category.index',
                           'url'=> route('merchant.product-attributes-category.index'),
                           'text'=> __('View Pro-Attr Categories')
                       ],
                       [
                           'permission'=> 'merchant.product-attributes-category.create',
                           'url'=> route('merchant.product-attributes-category.create'),
                           'text'=> __('Create Pro-Attr Category')
                       ],
                       [
                           'aClass'=> 'color-red',
                           'permission'=> 'merchant.product-attributes-category.index',
                           'url'=> route('merchant.product-attributes-category.index',['withTrashed'=>1]),
                           'text'=> __('Trashed Pro-Attr Categories')
                       ],

                   ]
               ],
                'ProductAttributes'=> [
                   'permission'=> [
                       'merchant.product-attributes.index',
                       'merchant.product-attributes.create',
                       'merchant.product-attributes.edit'
                   ],
                   'icon'=>'fa fa-pagelines',
                   'text'=> __('Product Attributes'),
                   'sub'=>[
                       [
                           'permission'=> 'merchant.product-attributes.index',
                           'url'=> route('merchant.product-attributes.index'),
                           'text'=> __('View Pro-Attr')
                       ],
                       [
                           'permission'=> 'merchant.product-attributes.create',
                           'url'=> route('merchant.product-attributes.create'),
                           'text'=> __('Create Pro-Attr')
                       ],
                       [
                           'aClass'=> 'color-red',
                           'permission'=> 'merchant.product-attributes.index',
                           'url'=> route('merchant.product-attributes.index',['withTrashed'=>1]),
                           'text'=> __('Trashed Pro-Attr')
                       ],

                   ]
               ],

               'ProductCategories'=> [
                   'permission'=> [
                       'merchant.product-category.index',
                       'merchant.product-category.create',
                       'merchant.product-category.edit'
                   ],
                   'icon'=>'fa fa-pagelines',
                   'text'=> __('Product Categories'),
                   'sub'=>[
                       [
                           'permission'=> 'merchant.product-category.index',
                           'url'=> route('merchant.product-category.index'),
                           'text'=> __('View Product Categories')
                       ],
                       [
                           'permission'=> 'merchant.product-category.create',
                           'url'=> route('merchant.product-category.create'),
                           'text'=> __('Create Product Category')
                       ],
                       [
                           'aClass'=> 'color-red',
                           'permission'=> 'merchant.product-category.index',
                           'url'=> route('merchant.product-category.index',['withTrashed'=>1]),
                           'text'=> __('Trashed Product Categories')
                       ],

                   ]
               ],
               'Products'=> [
                   'permission'=> [
                       'merchant.product.index',
                       'merchant.product.create',
                       'merchant.product.edit'
                   ],
                   'icon'=>'fa fa-cubes',
                   'text'=> __('Products'),
                   'sub'=>[
                       [
                           'permission'=> 'merchant.product.index',
                           'url'=> route('merchant.product.index'),
                           'text'=> __('View Products')
                       ],
                       [
                           'permission'=> 'merchant.product.create',
                           'url'=> route('merchant.product.create'),
                           'text'=> __('Create Product')
                       ],
                       [
                           'aClass'=> 'color-red',
                           'permission'=> 'merchant.product.index',
                           'url'=> route('merchant.product.index',['withTrashed'=>1]),
                           'text'=> __('Trashed Products')
                       ],

                   ]
               ],
               'Orders'=> [
                   'permission'=> [
                       'merchant.order.index',
                       'merchant.order.create',
                       'merchant.order.edit'
                   ],
                   'icon'=>'fa fa-money',
                   'text'=> __('Orders'),
                   'sub'=>[
                       [
                           'permission'=> 'merchant.order.index',
                           'url'=> route('merchant.order.index'),
                           'text'=> __('View Orders')
                       ],
                       [
                           'permission'=> 'merchant.order.create',
                           'url'=> route('merchant.order.create'),
                           'text'=> __('Create Order')
                       ],
                       [
                           'aClass'=> 'color-red',
                           'permission'=> 'merchant.order.index',
                           'url'=> route('merchant.order.index',['withTrashed'=>1]),
                           'text'=> __('Trashed Orders')
                       ],

                   ]
               ],

               'Coupons'=> [
                   'permission'=> [
                       'merchant.coupon.index',
                       'merchant.coupon.create',
                       'merchant.coupon.edit'
                   ],
                   'icon'=>'fa fa-building-o',
                   'text'=> __('Coupons'),
                   'sub'=>[
                       [
                           'permission'=> 'merchant.coupon.index',
                           'url'=> route('merchant.coupon.index'),
                           'text'=> __('View Coupons')
                       ],
                       [
                           'permission'=> 'merchant.coupon.create',
                           'url'=> route('merchant.coupon.create'),
                           'text'=> __('Create Coupon')
                       ],
                   ]
               ],

               'StaffGroups'=> [
                   'permission'=> [
                       'merchant.staff-group.index',
                       'merchant.staff-group.create',
                       'merchant.staff-group.edit'
                   ],
                   'icon'=>'fa fa-low-vision',
                   'text'=> __('Staff Groups'),
                   'sub'=>[
                       [
                           'permission'=> 'merchant.staff-group.index',
                           'url'=> route('merchant.staff-group.index'),
                           'text'=> __('View Staff Groups')
                       ],
                       [
                           'permission'=> 'merchant.staff-group.create',
                           'url'=> route('merchant.staff-group.create'),
                           'text'=> __('Create Staff Group')
                       ],
                       [
                           'aClass'=> 'color-red',
                           'permission'=> 'merchant.staff-group.index',
                           'url'=> route('merchant.staff-group.index',['withTrashed'=>1]),
                           'text'=> __('Trashed Staff Groups')
                       ],

                   ]
               ],
               'Staff'=> [
                   'permission'=> [
                       'merchant.staff.index',
                       'merchant.staff.create',
                       'merchant.staff.edit'
                   ],
                   'icon'=>'fa fa-user-circle-o',
                   'text'=> __('Staff'),
                   'sub'=>[
                       [
                           'permission'=> 'merchant.staff.index',
                           'url'=> route('merchant.staff.index'),
                           'text'=> __('View Staff')
                       ],
                       [
                           'permission'=> 'merchant.staff.create',
                           'url'=> route('merchant.staff.create'),
                           'text'=> __('Create Staff')
                       ],
                       [
                           'aClass'=> 'color-red',
                           'permission'=> 'merchant.staff.index',
                           'url'=> route('merchant.staff.index',['withTrashed'=>1]),
                           'text'=> __('Trashed Staff')
                       ],

                   ]
               ],

           ]
       ];

    $menu['Payments'] = [
           'permission'=> [
                'payment.payment.index',
                'payment.payment.create',
                'payment.invoice.index',
                'payment.invoice.create',
                'payment.transactions.list',
                'payment.sdk.index',
                'payment.sdk.create',
                'payment.sdk.edit',
                ],
           'class'=>'',
           'icon'=>'fa fa-money',
           'text'=>__('Payments'),
           'sub'=>[
               'Status'=> [
                   'permission'=> [
                       'payment.payment.index',
                   ],
                   'icon'=>'fa fa-search',
                   'text'=> __('Payment Status'),
                   'url'=> route('payment.payment.index')
               ],
               'Summary report'=> [
                   'permission'=> [
                       'payment.payment.summary',
                   ],
                   'icon'=>'fa fa-flag',
                   'text'=> __('Summary report'),
                   'url'=> route('payment.payment.summary')
               ],
               'Invoice'=> [
                   'permission'=> [
                       'payment.invoice.index',
                       'payment.invoice.create',
                       'payment.invoice.edit'
                   ],
                   'icon'=>'fa fa-file-text',
                   'text'=> __('Invoice'),
                   'url'=> route('payment.invoice.index')
               ],

               'Transactions'=> [
                   'permission'=> [
                       'payment.transactions.list',
                   ],
                   'icon'=>'fa fa-exchange',
                   'text'=> __('Transactions'),
                   'url'=> route('payment.transactions.list')
               ],

               'SDK'=> [
                   'permission'=> [
                       'payment.sdk.index',
                       'payment.sdk.create'
                   ],
                   'icon'=>'fa fa-microchip',
                   'text'=> __('SDK'),
                   'sub'=>[
                       [
                           'permission'=> 'payment.sdk.index',
                           'url'=> route('payment.sdk.index'),
                           'text'=> __('View SDKs')
                       ],
                       [
                           'permission'=> 'payment.sdk.create',
                           'url'=> route('payment.sdk.create'),
                           'text'=> __('Create SDK')
                       ],
                       [
                           'aClass'=> 'color-red',
                           'permission'=> 'payment.sdk.index',
                           'url'=> route('payment.sdk.index',['withTrashed'=>1]),
                           'text'=> __('Trashed SDKs')
                       ],

                   ]
               ],

               'ServiceProviderCategories'=> [
                   'permission'=> [
                       'payment.service-provider-categories.index',
                       'payment.service-provider-categories.create',
                       'payment.service-provider-categories.edit'
                   ],
                   'icon'=>'fa fa-window-restore',
                   'text'=> __('Service Provider Categories'),
                   'sub'=>[
                       [
                           'permission'=> 'payment.service-provider-categories.index',
                           'url'=> route('payment.service-provider-categories.index'),
                           'text'=> __('View Service Providers')
                       ],
                       [
                           'permission'=> 'payment.service-provider-categories.create',
                           'url'=> route('payment.service-provider-categories.create'),
                           'text'=> __('Create Service Provider')
                       ],
                       [
                           'aClass'=> 'color-red',
                           'permission'=> 'payment.service-provider-categories.index',
                           'url'=> route('payment.service-provider-categories.index',['withTrashed'=>1]),
                           'text'=> __('Trashed Service Providers')
                       ],

                   ]
               ],

               'ServiceProviders'=> [
                   'permission'=> [
                       'payment.service-providers.index',
                       'payment.service-providers.create',
                       'payment.service-providers.edit'
                   ],
                   'icon'=>'fa fa-user-o',
                   'text'=> __('Service Providers'),
                   'sub'=>[
                       [
                           'permission'=> 'payment.service-providers.index',
                           'url'=> route('payment.service-providers.index'),
                           'text'=> __('View Service Providers')
                       ],
                       [
                           'permission'=> 'payment.service-providers.create',
                           'url'=> route('payment.service-providers.create'),
                           'text'=> __('Create Service Provider')
                       ],
                       [
                           'aClass'=> 'color-red',
                           'permission'=> 'payment.service-providers.index',
                           'url'=> route('payment.service-providers.index',['withTrashed'=>1]),
                           'text'=> __('Trashed Service Providers')
                       ],

                   ]
               ],


    'Services'=> [
                   'permission'=> [
                       'payment.services.index',
                       'payment.services.create',
                       'payment.services.edit'
                   ],
                   'icon'=>'fa fa-window-maximize',
                   'text'=> __('Services'),
                   'sub'=>[
                       [
                           'permission'=> 'payment.services.index',
                           'url'=> route('payment.services.index'),
                           'text'=> __('View Services')
                       ],
                       [
                           'permission'=> 'payment.services.create',
                           'url'=> route('payment.services.create'),
                           'text'=> __('Create Service')
                       ],
                       [
                           'aClass'=> 'color-red',
                           'permission'=> 'payment.services.index',
                           'url'=> route('payment.services.index',['withTrashed'=>1]),
                           'text'=> __('Trashed Services')
                       ],

                   ]
               ],




               'ServiceAPIs'=> [
                   'permission'=> [
                       'payment.service-api.index',
                       'payment.service-api.create',
                       'payment.service-api.edit'
                   ],
                   'icon'=>'fa fa-server',
                   'text'=> __('APIs'),
                   'sub'=>[
                       [
                           'permission'=> 'payment.service-api.index',
                           'url'=> route('payment.service-api.index'),
                           'text'=> __('View APIs')
                       ],
                       [
                           'permission'=> 'payment.service-api.create',
                           'url'=> route('payment.service-api.create'),
                           'text'=> __('Create API')
                       ],
                       [
                           'aClass'=> 'color-red',
                           'permission'=> 'payment.service-api.index',
                           'url'=> route('payment.service-api.index',['withTrashed'=>1]),
                           'text'=> __('Trashed APIs')
                       ],

                   ]
               ],



               'ServiceAPIParameters'=> [
                   'permission'=> [
                       'payment.service-api-parameters.index',
                       'payment.service-api-parameters.create',
                       'payment.service-api-parameters.edit'
                   ],
                   'icon'=>'fa fa-keyboard-o',
                   'text'=> __('API Parameters'),
                   'sub'=>[
                       [
                           'permission'=> 'payment.service-api-parameters.index',
                           'url'=> route('payment.service-api-parameters.index'),
                           'text'=> __('View API Parameters')
                       ],
                       [
                           'permission'=> 'payment.service-api-parameters.create',
                           'url'=> route('payment.service-api-parameters.create'),
                           'text'=> __('Create API Parameter')
                       ],
                       [
                           'aClass'=> 'color-red',
                           'permission'=> 'payment.service-api-parameters.index',
                           'url'=> route('payment.service-api-parameters.index',['withTrashed'=>1]),
                           'text'=> __('Trashed API Parameters')
                       ],

                   ]
               ],


               'Outputs'=> [
                   'permission'=> [
                       'payment.output.index',
                       'payment.output.create',
                       'payment.output.edit'
                   ],
                   'icon'=>'fa fa-outdent',
                   'text'=> __('Outputs'),
                   'sub'=>[
                       [
                           'permission'=> 'payment.output.index',
                           'url'=> route('payment.output.index'),
                           'text'=> __('View Outputs')
                       ],
                       [
                           'permission'=> 'payment.output.create',
                           'url'=> route('payment.output.create'),
                           'text'=> __('Create Output')
                       ],
                       [
                           'aClass'=> 'color-red',
                           'permission'=> 'payment.output.index',
                           'url'=> route('payment.output.index',['withTrashed'=>1]),
                           'text'=> __('Trashed Outputs')
                       ],

                   ]
               ],

           ]
       ];

    $menu['Staff'] = [
           'permission'=> [
                'system.staff.index',
                'system.staff.create',
                'system.staff.create'
                ],
           'class'=>'',
           'icon'=>'fa fa-user',
           'text'=>__('Staff'),
           'sub'=>[

               'Users'=> [
                   'permission'=> [
                       'system.staff.index',
                       'system.staff.create'
                   ],
                   'icon'=>'fa fa-user',
                   'text'=> __('Users'),
                   'sub'=>[
                       [
                           'permission'=> 'system.staff.index',
                           'url'=> route('system.staff.index'),
                           'text'=> __('View Users')
                       ],
                       [
                           'permission'=> 'system.staff.create',
                           'url'=> route('system.staff.create'),
                           'text'=> __('Create User')
                       ],
                       [
                           'aClass'=> 'color-red',
                           'permission'=> 'system.staff.index',
                           'url'=> route('system.staff.index',['withTrashed'=>1]),
                           'text'=> __('Trashed Users')
                       ],

                   ]
               ],
               'Staff-Target'=> [
                   'permission'=> [
                        'system.staff-target.index',
                        'system.staff-target.create',
                        'system.staff-target.show',
                        'system.staff-target.edit'
                        ],
                   'class'=>'',
                   'icon'=>'fa fa-dot-circle-o',
                   'text'=>__('Staff Target'),
                   'sub'=>[
                       'View'=> [
                           'permission'=> 'system.staff-target.index',
                           'url'=> route('system.staff-target.index'),
                           'text'=> __('View Staff Target'),
                       ],

                       [
                           'aClass'=> 'color-red',
                           'permission'=> 'system.staff-target.index',
                           'url'=> route('system.staff-target.index',['withTrashed'=>1]),
                           'text'=> __('Trashed Staff Target')
                       ],

                   ]
               ],
               'Permission'=> [
                   'permission'=> [
                       'system.permission-group.index',
                       'system.permission-group.create',
                       'system.permission-group.edit'
                   ],
                   'icon'=>'fa fa-universal-access',
                   'text'=> __('Permissions'),
                   'sub'=>[
                       [
                           'permission'=> 'system.permission-group.index',
                           'url'=> route('system.permission-group.index'),
                           'text'=> __('View Permissions')
                       ],
                       [
                           'permission'=> 'system.permission-group.create',
                           'url'=> route('system.permission-group.create'),
                           'text'=> __('Create Permission')
                       ],
                       [
                           'aClass'=> 'color-red',
                           'permission'=> 'system.permission-group.index',
                           'url'=> route('system.permission-group.index',['withTrashed'=>1]),
                           'text'=> __('Trashed Permissions')
                       ],

                   ]
               ],

           ]
       ];

    $menu['Users'] = [
           'permission'=> [
                'system.users.index',
                'system.users.create',
                'system.users.edit',
                ],
           'class'=>'',
           'icon'=>'fa fa-users',
           'text'=>__('Users'),
           'sub'=>[

               'View'=> [
                   'permission'=> 'system.users.index',
                   'url'=> route('system.users.index'),
                   'text'=> __('View Users'),
               ],

               'Create'=> [
                   'permission'=> 'system.users.create',
                   'url'=> route('system.users.create'),
                   'text'=> __('Create User'),
               ],

               [
                   'aClass'=> 'color-red',
                   'permission'=> 'system.users.index',
                   'url'=> route('system.users.index',['withTrashed'=>1]),
                   'text'=> __('Trashed Users')
               ],

           ]
       ];


    $menu['Wallet'] = [
           'permission'=> [
               'system.wallet.index','system.wallet.main-wallets','system.wallet.transactions',
               'system.wallet.transfer-money-supervisor','system.wallet.transfer-money-supervisor.post',
               'system.wallet.transfer-money-staff',
               'system.wallet.transfer-money-staff.post',
               'system.wallet.transfer-money-main-wallets',
               'system.wallet.transfer-money-main-wallets.post',
               'system.wallet.requestRechargeWallet'

           ],
           'class'=>'',
           'icon'=>'fa fa-google-wallet',
           'text'=>__('Wallet'),
           'sub'=>[

               'View'=> [
                   'permission'=> 'system.wallet.index',
                   'url'=> route('system.wallet.index'),
                   'text'=> __('View Wallets'),
               ],

               'MainWallets'=> [
                   'permission'=> 'system.wallet.main-wallets',
                   'url'=> route('system.wallet.main-wallets'),
                   'text'=> __('Main Wallets'),
               ],

               'RequestTransfare'=> [
                   'permission'=> 'system.wallet.requestRechargeWallet',
                   'url'=> route('system.wallet.requestRechargeWallet'),
                   'text'=> __('Request Transfer'),
               ],

               'transactions'=> [
                   'permission'=> 'system.wallet.transactions',
                   'url'=> route('system.wallet.transactions'),
                   'text'=> __('Transactions'),
               ],

               'Transfer-Money'=> [
                   'permission'=> [
                       'system.wallet.transfer-money-supervisor',
                       'system.wallet.transfer-money-supervisor.post',
                       'system.wallet.transfer-money-staff',
                       'system.wallet.transfer-money-staff.post',
                       'system.wallet.transfer-money-main-wallets',
                       'system.wallet.transfer-money-main-wallets.post'
                   ],
                   'icon'=>'fa fa-exchange',
                   'text'=> __('Transfer Money'),
                   'sub'=>[
                       [
                           'permission'=> 'system.wallet.transfer-money-main-wallets',
                           'url'=> route('system.wallet.transfer-money-main-wallets'),
                           'text'=> __('Main Wallets')
                       ],
                       [
                           'permission'=> 'system.wallet.transfer-money-supervisor',
                           'url'=> route('system.wallet.transfer-money-supervisor'),
                           'text'=> __('Supervisor')
                       ],
                       [
                           'permission'=> 'system.wallet.transfer-money-staff',
                           'url'=> route('system.wallet.transfer-money-staff'),
                           'text'=> __('Merchant')
                       ],
                       [
                           'permission'=> 'system.wallet.transferMoneyWallets',
                           'url'=> route('system.wallet.transferMoneyWallets'),
                           'text'=> __('Wallets')
                       ],
                       [
                           'permission'=> 'system.wallet.transferMoneyTwoWallets',
                           'url'=> route('system.wallet.transferMoneyTwoWallets'),
                           'text'=> __('FW-TW')
                       ]
                   ]
               ],

           ]
       ];

    $menu['Settlement'] = [
           'permission'=> [
                'system.settlement.generate-report','system.settlement.generate-report-port',
                'system.settlement.generate-report-ajax','system.settlement.show','system.settlement.index',
           ],
           'class'=>'',
           'icon'=>'fa fa-exchange',
           'text'=>__('Settlement'),
           'sub'=>[

               'View'=> [
                   'permission'=> 'system.settlement.index',
                   'url'=> route('system.settlement.index'),
                   'text'=> __('View Settlement'),
               ],

               'Generate-Report'=> [
                   'permission'=> 'system.settlement.generate-report',
                   'url'=> route('system.settlement.generate-report'),
                   'text'=> __('Generate Report'),
               ],


           ]
       ];

    $menu['Commission-List'] = [
           'permission'=> [
                'system.commission-list.index','system.commission-list.show','system.commission-list.edit',
           ],
           'class'=>'',
           'icon'=>'fa fa-signal',
           'text'=>__('Commission'),
           'sub'=>[

               'View'=> [
                   'permission'=> 'system.commission-list.index',
                   'url'=> route('system.commission-list.index'),
                   'text'=> __('View Commission'),
               ],

               'Create'=> [
                   'permission'=> 'system.commission-list.create',
                   'url'=> route('system.commission-list.create'),
                   'text'=> __('Create Commission'),
               ],

               [
                   'aClass'=> 'color-red',
                   'permission'=> 'system.commission-list.index',
                   'url'=> route('system.commission-list.index',['withTrashed'=>1]),
                   'text'=> __('Trashed Commission')
               ],

           ]
       ];


    $menu['Loyalty-Programs'] = [
           'permission'=> [
                'system.loyalty-programs.index',
                'system.loyalty-programs.create',
                'system.loyalty-programs.edit',
                ],
           'class'=>'',
           'icon'=>'fa fa-smile-o',
           'text'=>__('Loyalty Programs'),
           'sub'=>[

               'View'=> [
                   'permission'=> 'system.loyalty-programs.index',
                   'url'=> route('system.loyalty-programs.index'),
                   'text'=> __('View Programs'),
               ],

               'create'=> [
                   'permission'=> 'system.loyalty-programs.create',
                   'url'=> route('system.loyalty-programs.create'),
                   'text'=> __('Create Program'),
               ],

               'Trashed'=> [
                   'aClass'=> 'color-red',
                   'permission'=> 'system.loyalty-programs.index',
                   'url'=> route('system.loyalty-programs.index',['withTrashed'=>1]),
                   'text'=> __('Trashed Programs'),
               ],

           ]
       ];





    $menu['KnowledgeBase'] = [
           'permission'=> [
                    'system.system-knowledge.index',
                    'system.system-knowledge.create',
                    'system.system-knowledge.edit',
                    ],
           'class'=>'',
           'icon'=>'fa fa-info',
           'text'=>__('Knowledge Base'),
           'sub'=>[

               'Search'=> [
                   'permission'=> 'system.system-knowledge.search',
                   'url'=> route('system.system-knowledge.search'),
                   'text'=> __('Search'),
               ],

               'View'=> [
                   'permission'=> 'system.system-knowledge.index',
                   'url'=> route('system.system-knowledge.index'),
                   'text'=> __('View Knowledge Base'),
               ],

               'Create'=> [
                   'permission'=> 'system.system-knowledge.create',
                   'url'=> route('system.system-knowledge.create'),
                   'text'=> __('Create Knowledge Base'),
               ],

               [
                   'aClass'=> 'color-red',
                   'permission'=> 'system.system-knowledge.index',
                   'url'=> route('system.system-knowledge.index',['withTrashed'=>1]),
                   'text'=> __('Trashed Knowledge Base')
               ],

           ]
       ];

    $menu['Location'] = [
           'permission'=> [
                    'system.area-type.index',
                    'system.area-type.create',
                    'system.area-type.edit',
                    ],
           'class'=>'',
           'icon'=>'fa fa-globe',
           'text'=>__('Location'),
           'sub'=>[

               'AreaType'=> [
                   'permission'=> [
                       'system.area-type.index',
                       'system.area-type.create'
                   ],
                   'icon'=>'fa fa-compass',
                   'text'=> __('Area Types'),
                   'sub'=>[
                       [
                           'permission'=> 'system.area-type.index',
                           'url'=> route('system.area-type.index'),
                           'text'=> __('View Area Types')
                       ],
                       [
                           'permission'=> 'system.area-type.create',
                           'url'=> route('system.area-type.create'),
                           'text'=> __('Create Area Type')
                       ],
                       [
                           'aClass'=> 'color-red',
                           'permission'=> 'system.area-type.index',
                           'url'=> route('system.area-type.index',['withTrashed'=>1]),
                           'text'=> __('Trashed Area Types')
                       ],

                   ]
               ],
               'Areas'=> [
                   'permission'=> [
                       'system.area.index',
                       'system.area.create',
                       'system.area.edit',
                   ],
                   'icon'=>'fa fa-map-marker',
                   'text'=> __('Areas'),
                   'sub'=>[
                       [
                           'permission'=> 'system.area.index',
                           'url'=> route('system.area.index'),
                           'text'=> __('View Areas')
                       ],
                       [
                           'permission'=> 'system.area.create',
                           'url'=> route('system.area.create'),
                           'text'=> __('Create Area')
                       ],
                       [
                           'aClass'=> 'color-red',
                           'permission'=> 'system.area.index',
                           'url'=> route('system.area.index',['withTrashed'=>1]),
                           'text'=> __('Trashed Areas')
                       ],

                   ]
               ],

           ]
       ];






    $menu['System'] = [
           'permission'=> [
                'system.setting.index',
                'system.activity-log.index'
                ],
           'class'=>'',
           'icon'=>'fa fa-cogs',
           'text'=>__('System'),
           'sub'=>[

               'Setting'=> [
                   'permission'=> 'system.setting.index',
                   'icon'=> 'fa fa-cog',
                   'url'=> route('system.setting.index'),
                   'text'=> __('Setting'),
               ],
               'SendLog'=> [
                   'permission'=> 'system.sender.index',
                   'icon'=> 'fa fa-paper-plane',
                   'url'=> route('system.sender.index'),
                   'text'=> __('Send Log'),
               ],


               'ActivityLog'=> [
                   'permission'=> 'system.activity-log.index',
                   'icon'=> 'fa fa-binoculars',
                   'url'=> route('system.activity-log.index'),
                   'text'=> __('Activity Log'),
               ],




           ]
       ];

    $menu['MarketingMessage'] = [
           'permission'=> [
                        'system.marketing-message.index',
                        'system.marketing-message.create',
                        'system.marketing-message.edit'
                        ],
           'class'=>'',
           'icon'=>'fa fa-glass',
           'text'=>__('Marketing Messages'),
           'sub'=>[

               'View'=> [
                   'permission'=> 'system.marketing-message.index',
                   'url'=> route('system.marketing-message.index'),
                   'text'=> __('View Messages'),
               ],


               'Create'=> [
                   'permission'=> 'system.marketing-message.create',
                   'url'=> route('system.marketing-message.create'),
                   'text'=> __('Create Message'),
               ],

               'Trashed'=> [
                   'aClass'=> 'color-red',
                   'permission'=> 'system.marketing-message.index',
                   'url'=> route('system.marketing-message.index',['withTrashed'=>1]),
                   'text'=> __('Trashed Messages')
               ],



           ]
       ];

    $menu['Banks'] = [
           'permission'=> [
                'system.banks.index',
                'system.banks.show',
                'system.banks.create',
                'system.banks.edit'
           ],
           'class'=>'',
           'icon'=>'fa fa-university',
           'text'=>__('Banks'),
           'sub'=>[

               'View'=> [
                   'permission'=> 'system.banks.index',
                   'url'=> route('system.banks.index'),
                   'text'=> __('View Banks'),
               ],

               'Create'=> [
                   'permission'=> 'system.banks.create',
                   'url'=> route('system.banks.create'),
                   'text'=> __('Create Bank'),
               ],

               [
                   'aClass'=> 'color-red',
                   'permission'=> 'system.banks.index',
                   'url'=> route('system.banks.index',['withTrashed'=>1]),
                   'text'=> __('Trashed Banks')
               ],

           ]
       ];

    $menu['News'] = [
           'permission'=> [
                    'system.news-category.index',
                    'system.news-category.create',
                    'system.news-category.edit',
                    ],
           'class'=>'',
           'icon'=>'fa fa-newspaper-o',
           'text'=> __('News'),
           'sub'=>[
               'NewsCategory'=> [
                   'permission'=> [
                       'system.news-category.index',
                       'system.news-category.create'
                   ],
                   'icon'=>'fa fa-sitemap',
                   'text'=> __('Category'),
                   'sub'=>[
                       [
                           'permission'=> 'system.news-category.index',
                           'url'=> route('system.news-category.index'),
                           'text'=> __('View Categories')
                       ],
                       [
                           'permission'=> 'system.news-category.create',
                           'url'=> route('system.news-category.create'),
                           'text'=> __('Create Category')
                       ],
                       [
                           'aClass'=> 'color-red',
                           'permission'=> 'system.news-category.index',
                           'url'=> route('system.news-category.index',['withTrashed'=>1]),
                           'text'=> __('Trashed Categories')
                       ],
                   ]
               ],

               'News'=> [
                   'permission'=> [
                       'system.news.index',
                       'system.news.create'
                   ],
                   'icon'=>'fa fa-file-text',
                   'text'=> __('Articles'),
                   'sub'=>[
                       [
                           'permission'=> 'system.news.index',
                           'url'=> route('system.news.index'),
                           'text'=> __('View Articles')
                       ],
                       [
                           'permission'=> 'system.news.create',
                           'url'=> route('system.news.create'),
                           'text'=> __('Create Article')
                       ],
                       [
                           'aClass'=> 'color-red',
                           'permission'=> 'system.news.index',
                           'url'=> route('system.news.index',['withTrashed'=>1]),
                           'text'=> __('Trashed Articles')
                       ],

                   ]
               ],

           ]
       ];

       $menu['tickets'] = [
           'permission'=> [
                    'system.tickets.index',
                    'system.tickets.create',
                    'system.tickets.edit',
                    'system.tickets.comment',
                    'system.tickets.status',
                    ],
           'class'=>'',
           'icon'=>'fa fa-phone',
           'text'=>__('Call-center Tickets'),
           'sub'=>[

               'View'=> [
                   'permission'=> 'system.tickets.index',
                   'url'=> route('system.tickets.index'),
                   'text'=> __('View Tickets'),
               ],

               'Create'=> [
                   'permission'=> 'system.tickets.create',
                   'url'=> route('system.tickets.create'),
                   'text'=> __('Create ticket'),
               ]
           ]
       ];

       $menu['calltracking'] = [
           'permission'=> [
                    'system.call-tracking.index',
                    'system.call-tracking.create',
                    'system.call-tracking.edit',
                    ],
           'class'=>'',
           'icon'=>'fa fa-phone',
           'text'=>__('Call-tracking'),
           'sub'=>[

               'View'=> [
                   'permission'=> 'system.call-tracking.index',
                   'url'=> route('system.call-tracking.index'),
                   'text'=> __('View tracking'),
               ],

               'Create'=> [
                   'permission'=> 'system.call-tracking.create',
                   'url'=> route('system.call-tracking.create'),
                   'text'=> __('Create call track'),
               ]
           ]
       ];

    $menu['Advertisements'] = [
           'permission'=> [
                    'system.advertisement.index',
                    'system.advertisement.create',
                    'system.advertisement.edit',
                    ],
           'class'=>'',
           'icon'=>'fa fa-film',
           'text'=>__('Advertisements'),
           'sub'=>[

               'View'=> [
                   'permission'=> 'system.advertisement.index',
                   'url'=> route('system.advertisement.index'),
                   'text'=> __('View Advertisements'),
               ],

               'Create'=> [
                   'permission'=> 'system.advertisement.create',
                   'url'=> route('system.advertisement.create'),
                   'text'=> __('Create Advertisement'),
               ],

               [
                   'aClass'=> 'color-red',
                   'permission'=> 'system.advertisement.index',
                   'url'=> route('system.advertisement.index',['withTrashed'=>1]),
                   'text'=> __('Trashed Advertisements')
               ],

           ]
       ];









@endphp

@foreach($menu as $onemenu)
    {!! generateMenu($onemenu) !!}
@endforeach