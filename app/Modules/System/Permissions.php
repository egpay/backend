<?php

return [

    /*
    * merchant.merchants
    */
    [
        'name' => __('merchant Permissions'),
        'description' => __('Manage merchant accounts and point of sale accounting'),
        'permissions' => [
            'view-merchant'=>['merchant.merchant.index'],
            'view-one-merchant'=>['merchant.merchant.show'],
            'delete-one-merchant'=>['merchant.merchant.destroy'],
            'review-temporarily-merchants'=>['merchant.merchant.review'],
            'create-merchant'=>['merchant.merchant.create','merchant.merchant.store'],
            'fast-create-merchant'=>['merchant.merchant.fast-create','merchant.merchant.fast-create.store'],
            'update-merchant'=>['merchant.merchant.edit','merchant.merchant.update'],
        ]
    ],


    /*
    * merchant.categorys
    */
    [
        'name' => __('category Permissions'),
        'description' => __('Merchant category management, ex (Clothes, shoes, Misc...etc)'),
        'permissions' => [
            'view-category'=>['merchant.category.index'],
            'view-one-category'=>['merchant.category.show'],
            'delete-one-category'=>['merchant.category.destroy'],
            'create-category'=>['merchant.category.create','merchant.category.store'],
            'update-category'=>['merchant.category.edit','merchant.category.update'],
        ]
    ],


    /*
    * merchant.contracts
    */
    [
        'name' => __('contract Permissions'),
        'description' => __('Manage merchant and point of sale contracts'),
        'permissions' => [
            'view-contract'=>['merchant.contract.index'],
            'view-one-contract'=>['merchant.contract.show'],
            'delete-one-contract'=>['merchant.contract.destroy'],
            'create-contract'=>['merchant.contract.create','merchant.contract.store'],
            'update-contract'=>['merchant.contract.edit','merchant.contract.update'],
        ]
    ],


    /*
    * merchant.product-categorys
    */
    [
        'name' => __('Merchant management Permissions'),
        'description' => __('Merchant account management (branches, product categories and products)'),
        'permissions' => [

            //Branches
            'view-branch'=>['merchant.branch.index'],
            'view-one-branch'=>['merchant.branch.show'],
            'delete-one-branch'=>['merchant.branch.destroy'],
            'create-branch'=>['merchant.branch.create','merchant.branch.store'],
            'update-branch'=>['merchant.branch.edit','merchant.branch.update'],




            'view-product-category'=>['merchant.product-category.index'],
            'view-one-product-category'=>['merchant.product-category.show'],
            'delete-one-product-category'=>['merchant.product-category.destroy'],
            'create-product-category'=>['merchant.product-category.create','merchant.product-category.store'],
            'update-product-category'=>['merchant.product-category.edit','merchant.product-category.update'],


            //Products

            'view-product'=>['merchant.product.index'],
            'view-one-product'=>['merchant.product.show'],
            'delete-one-product'=>['merchant.product.destroy'],
            'create-product'=>['merchant.product.create','merchant.product.store'],
            'update-product'=>['merchant.product.edit','merchant.product.update'],


            //Product Attributes
            'view-pro-Attr-categories'=>['merchant.product-attributes-category.index'],
            'view-pro-Attr-category'=>['merchant.product-attributes-category.show'],
            'delete-pro-Attr-category'=>['merchant.product-attributes-category.destroy'],
            'create-pro-Attr-category'=>['merchant.product-attributes-category.create','merchant.product-attributes-category.store'],
            'update-pro-Attr-category'=>['merchant.product-attributes-category.edit','merchant.product-attributes-category.update'],

            'view-product-attributes'=>['merchant.product-attributes.index'],
            'view-one-product-attributes'=>['merchant.product-attributes.show'],
            'delete-one-product-attributes'=>['merchant.product-attributes.destroy'],
            'create-product-attributes'=>['merchant.product-attributes.create','merchant.product-attributes.store'],
            'update-product-attributes'=>['merchant.product-attributes.edit','merchant.product-attributes.update'],


            // staff groups
            'view-staff-group'=>['merchant.staff-group.index'],
            'view-one-staff-group'=>['merchant.staff-group.show'],
            'delete-one-staff-group'=>['merchant.staff-group.destroy'],
            'create-staff-group'=>['merchant.staff-group.create','merchant.staff-group.store'],
            'update-staff-group'=>['merchant.staff-group.edit','merchant.staff-group.update'],

            //coupons
            'view-coupon'=>['merchant.coupon.index'],
            'view-one-coupon'=>['merchant.coupon.show'],
            'delete-one-coupon'=>['merchant.coupon.destroy'],
            'create-coupon'=>['merchant.coupon.create','merchant.coupon.store'],
            'update-coupon'=>['merchant.coupon.edit','merchant.coupon.update'],

        ]
    ],


    /*
    * merchant.plans
    */
    [
        'name' => __('plan Permissions'),
        'description' => __('Merchant contracts plans, for merchants and point of sales'),
        'permissions' => [
            'view-plan'=>['merchant.plan.index'],
            'view-one-plan'=>['merchant.plan.show'],
            'delete-one-plan'=>['merchant.plan.destroy'],
            'create-plan'=>['merchant.plan.create','merchant.plan.store'],
            'update-plan'=>['merchant.plan.edit','merchant.plan.update'],
        ]
    ],


    /*
    * merchant.staffs
    */
    [
        'name' => __('staff Permissions'),
        'description' => __('Merchant employee management'),
        'permissions' => [
            'view-merchant-staff'=>['merchant.staff.index'],
            'view-one-merchant-staff'=>['merchant.staff.show'],
            'delete-one-merchant-staff'=>['merchant.staff.destroy'],
            'create-merchant-staff'=>['merchant.staff.create','merchant.staff.store'],
            'update-merchant-staff'=>['merchant.staff.edit','merchant.staff.update'],
        ]
    ],


    /*
    * merchant.orders
    */
    [
        'name' => __('order Permissions'),
        'description' => __('Managing order permissions'),
        'permissions' => [
            'view-order'=>['merchant.order.index'],
            'view-one-order'=>['merchant.order.show'],
            'delete-one-order'=>['merchant.order.destroy'],
            'create-order'=>['merchant.order.create','merchant.order.store'],
            'update-order'=>['merchant.order.edit','merchant.order.update'],
            'generate-QRcode'=>['merchant.order.qrcode'],
        ]
    ],


    /*
    * payment.sdks
    */
    [
        'name' => __('sdk Permissions'),
        'description' => __('Managing payment sdk Permissions'),
        'permissions' => [
            'payment-status'=> ['payment.payment.index'],
            'payment-summary-report'=> ['payment.payment.summary'],
            'view-sdk'=>['payment.sdk.index'],
            'view-one-sdk'=>['payment.sdk.show'],
            'delete-one-sdk'=>['payment.sdk.destroy'],
            'create-sdk'=>['payment.sdk.create','payment.sdk.store'],
            'update-sdk'=>['payment.sdk.edit','payment.sdk.update'],
        ]
    ],


    /*
    * payment.service-apis
    */
    [
        'name' => __('service-api Permissions'),
        'description' => __('Payment service-api Permissions'),
        'permissions' => [
            'view-service-api'=>['payment.service-api.index'],
            'view-one-service-api'=>['payment.service-api.show'],
            'delete-one-service-api'=>['payment.service-api.destroy'],
            'create-service-api'=>['payment.service-api.create','payment.service-api.store'],
            'update-service-api'=>['payment.service-api.edit','payment.service-api.update'],
        ]
    ],


    /*
    * payment.service-api-parameterss
    */
    [
        'name' => __('service-api-parameters Permissions'),
        'description' => __('Payment service-api-parameters Permissions'),
        'permissions' => [
            'view-all-service-api-parameters'=>['payment.service-api-parameters.index'],
            'view-one-service-api-parameters'=>['payment.service-api-parameters.show'],
            'delete-one-service-api-parameters'=>['payment.service-api-parameters.destroy'],
            'create-service-api-parameters'=>['payment.service-api-parameters.create','payment.service-api-parameters.store'],
            'update-service-api-parameters'=>['payment.service-api-parameters.edit','payment.service-api-parameters.update'],
        ]
    ],


    /*
    * payment.servicess
    */
    [
        'name' => __('services Permissions'),
        'description' => __('Managing payment services Permissions'),
        'permissions' => [
            'view-all-services'=>['payment.services.index'],
            'view-one-services'=>['payment.services.show'],
            'delete-one-services'=>['payment.services.destroy'],
            'create-services'=>['payment.services.create','payment.services.store'],
            'update-services'=>['payment.services.edit','payment.services.update'],
        ]
    ],


    /*
    * payment.service-providers
    */
    [
        'name' => __('service-providers Permissions'),
        'description' => __('Payment service-providers Permissions'),
        'permissions' => [
            'view-all-service-providers'=>['payment.service-providers.index'],
            'view-one-service-provider'=>['payment.service-providers.show'],
            'delete-one-service-provider'=>['payment.service-providers.destroy'],
            'create-service-provider'=>['payment.service-providers.create','payment.service-providers.store'],
            'update-service-provider'=>['payment.service-providers.edit','payment.service-providers.update'],
        ]
    ],


    /*
    * payment.service-provider-categories
    */
    [
        'name' => __('service-provider-categories Permissions'),
        'description' => __('Payment service-provider-categories Permissions'),
        'permissions' => [
            'view-all-service-provider-categories'=>['payment.service-provider-categories.index'],
            'view-one-service-provider-category'=>['payment.service-provider-categories.show'],
            'delete-one-service-provider-category'=>['payment.service-provider-categories.destroy'],
            'create-service-provider-category'=>['payment.service-provider-categories.create','payment.service-provider-categories.store'],
            'update-service-provider-category'=>['payment.service-provider-categories.edit','payment.service-provider-categories.update'],
        ]
    ],


    /*
    * payment.outputs
    */
    [
        'name' => __('output Permissions'),
        'description' => __('Payment output Permissions Description'),
        'permissions' => [
            'view-all-output'=>['payment.output.index'],
            'view-one-output'=>['payment.output.show'],
            'delete-one-output'=>['payment.output.destroy'],
            'create-output'=>['payment.output.create','payment.output.store'],
            'update-output'=>['payment.output.edit','payment.output.update'],
        ]
    ],


    /*
    * payment.invoices
    */
    [
        'name' => __('invoice Permissions'),
        'description' => __('Payment invoice Permissions Description'),
        'permissions' => [
            'view-all-invoice'=>['payment.invoice.index'],
            'view-one-invoice'=>['payment.invoice.show'],
            'delete-invoice'=>['payment.invoice.destroy'],
            'create-invoice'=>['payment.invoice.create','payment.invoice.store'],
            'update-invoice'=>['payment.invoice.edit','payment.invoice.update'],
            'change-invoice-status'=>['payment.invoice.change-status'],
            'payment-transactions-list'=>['payment.transactions.list'],
            'payment-transactions-details'=>['payment.transactions.ajax-details'],
        ]
    ],


    /*
    * commission-lists
    */
    [
        'name' => __('invoice Permissions'),
        'description' => __('commission-list Permissions Description'),
        'permissions' => [
            'view-all-commission-list'=>['system.commission-list.index'],
            'view-one-commission-list'=>['system.commission-list.show'],
            'delete-one-commission-list'=>['system.commission-list.destroy'],
            'create-commission-list'=>['system.commission-list.create','system.commission-list.store'],
            'update-commission-list'=>['system.commission-list.edit','system.commission-list.update'],
        ]
    ],


    /*
    * system-knowledges
    */
    [
        'name' => __('system-knowledge Permissions'),
        'description' => __('system-knowledge Permissions Description'),
        'permissions' => [
            'view-all-knowledge-base'=>['system.system-knowledge.index'],
            'view-one-knowledge-base'=>['system.system-knowledge.show'],
            'delete-one-knowledge-base'=>['system.system-knowledge.destroy'],
            'create-knowledge-base'=>['system.system-knowledge.create','system.system-knowledge.store'],
            'update-knowledge-base'=>['system.system-knowledge.edit','system.system-knowledge.update'],
            'search-knowledge-base'=>['system.system-knowledge.search'],
        ]
    ],


    /*
    * permission-groups
    */
    [
        'name' => __('permission-groups Permissions'),
        'description' => __('permission-group Permissions Description'),
        'permissions' => [
            'view-all-permission-groups'=>['system.permission-group.index'],
            'view-one-permission-groups'=>['system.permission-group.show'],
            'delete-one-permission-groups'=>['system.permission-group.destroy'],
            'create-permission-groups'=>['system.permission-group.create','system.permission-group.store'],
            'update-permission-groups'=>['system.permission-group.edit','system.permission-group.update'],
        ]
    ],


    /*
    * system-tickets
    */
    [
        'name' => __('Tickets Permissions'),
        'description' => __('Ticket permissions Description'),
        'permissions' => [
            'view-all-tickets'=>['system.system-ticket.index'],
            'view-one-ticket'=>['system.system-ticket.show'],
            'delete-one-ticket'=>['system.system-ticket.destroy'],
            'create-ticket'=>['system.system-ticket.create','system.system-ticket.store'],
            'update-ticket'=>['system.system-ticket.edit','system.system-ticket.update'],
        ]
    ],


    /*
    * Call center-tickets
    */
    [
        'name' => __('Call center Tickets Permissions'),
        'description' => __('Call center Ticket permissions Description'),
        'permissions' => [
            'view-all-tickets'=>['system.tickets.index'],
            'view-one-ticket'=>['system.tickets.show'],
            'comment-one-ticket'=>['system.tickets.comment'],
            'change-status'=>['system.tickets.status'],
            'delete-one-ticket'=>['system.tickets.destroy'],
            'create-ticket'=>['system.tickets.create','system.tickets.store'],
            'update-ticket'=>['system.tickets.edit','system.tickets.update'],
        ]
    ],

    /*
    * Call tracking
    */
    [
        'name' => __('Call tracking Permissions'),
        'description' => __('Call tracking permissions Description'),
        'permissions' => [
            'view-all-tracking'=>['system.call-tracking.index'],
            'view-one-track'=>['system.call-tracking.show'],
            'delete-one-tracking'=>['system.call-tracking.destroy'],
            'create-call-tracking'=>['system.call-tracking.create','system.call-tracking.store'],
            'update-tracking'=>['system.call-tracking.edit','system.call-tracking.update'],
        ]
    ],



    /*
    * users
    */
    [
        'name' => __('Users Permissions'),
        'description' => __('Users permissions Description'),
        'permissions' => [
            'view-all-users'=>['system.users.index'],
            'view-one-user'=>['system.users.show'],
            'delete-one-user'=>['system.users.destroy'],
            'create-user'=>['system.users.create','system.users.store'],
            'update-user'=>['system.users.edit','system.users.update'],
        ]
    ],


    /*
    * staff
    */
    [
        'name' => __('Staff Permissions'),
        'description' => __('Staff Permissions Description'),
        'permissions' => [
            'view-all-staff'    =>['system.staff.index'],
            'view-one-staff'    =>['system.staff.show'],
            'delete-one-staff'  =>['system.staff.destroy'],
            'create-staff'      =>['system.staff.create','system.staff.store'],
            'update-staff'      =>['system.staff.edit','system.staff.update'],
            'add-managed-staff' =>['system.staff.add-managed-staff'],
            'delete-managed-staff' =>['system.staff.delete-managed-staff'],
            'show-tree-users-data' => ['show-tree-users-data'],
        ]
    ],


    [
        'name' => __('Staff Target Permissions'),
        'description' => __('Staff Target Permissions Description'),
        'permissions' => [
            'view-staff-target'    =>['system.staff-target.index'],
            'view-one-staff-target'    =>['system.staff-target.show'],
            'delete-one-staff-target'  =>['system.staff-target.destroy'],
            'create-staff-target'      =>['system.staff-target.create','system.staff-target.store'],
            'update-staff-target'      =>['system.staff-target.edit','system.staff-target.update']
        ]
    ],



    [
        'name' => __('Sender Permissions'),
        'description' => __('Sender Permissions Description'),
        'permissions' => [
            'view-all-send-data'    =>['system.sender.index'],
            'view-one-send'    =>['system.sender.show'],
            'delete-one-send'  =>['system.sender.destroy'],
            'send-message'      =>['system.sender.create','system.sender.store'],
        ]
    ],


    /*
    * area-types
    */
    [
        'name' => __('Area types Permissions'),
        'description' => __('Area types Permissions Description'),
        'permissions' => [
            'view-all-area-type'=>['system.area-type.index'],
            'view-one-area-type'=>['system.area-type.show'],
            'delete-one-area-type'=>['system.area-type.destroy'],
            'create-area-type'=>['system.area-type.create','system.area-type.store'],
            'update-area-type'=>['system.area-type.edit','system.area-type.update'],
        ]
    ],


    /*
    * areas
    */
    [
        'name' => __('invoice Permissions'),
        'description' => __('Area Permissions Description'),
        'permissions' => [
            'view-all-areas'=>['system.area.index'],
            'view-one-area'=>['system.area.show'],
            'delete-one-area'=>['system.area.destroy'],
            'create-area'=>['system.area.create','system.area.store'],
            'update-area'=>['system.area.edit','system.area.update'],
        ]
    ],


    /*
    * advertisements
    */
    [
        'name' => __('invoice Permissions'),
        'description' => __('Advertisement Permissions Description'),
        'permissions' => [
            'view-all-advertisements'=>['system.advertisement.index'],
            'view-one-advertisement'=>['system.advertisement.show'],
            'delete-one-advertisement'=>['system.advertisement.destroy'],
            'create-advertisement'=>['system.advertisement.create','system.advertisement.store'],
            'update-advertisement'=>['system.advertisement.edit','system.advertisement.update'],
        ]
    ],


    /*
    * newss
    */
    [
        'name' => __('News Permissions'),
        'description' => __('News Permissions Description'),
        'permissions' => [
            'view-all-news'=>['system.news.index'],
            'view-one-news'=>['system.news.show'],
            'delete-news'=>['system.news.destroy'],
            'create-news'=>['system.news.create','system.news.store'],
            'update-news'=>['system.news.edit','system.news.update'],
        ]
    ],


    /*
    * news-categories
    */
    [
        'name' => __('News categories Permissions'),
        'description' => __('News categories Permissions Description'),
        'permissions' => [
            'view-all-news-categories'=>['system.news-category.index'],
            'view-one-news-category'=>['system.news-category.show'],
            'delete-one-news-category'=>['system.news-category.destroy'],
            'create-news-category'=>['system.news-category.create','system.news-category.store'],
            'update-news-category'=>['system.news-category.edit','system.news-category.update'],
        ]
    ],


    /*
    * banks
    */
    [
        'name' => __('Banks Permissions'),
        'description' => __('banks Permissions Description'),
        'permissions' => [
            'view-banks'=>['system.banks.index'],
            'view-one-bank'=>['system.banks.show'],
            'delete-one-bank'=>['system.banks.destroy'],
            'create-bank'=>['system.banks.create','system.banks.store'],
            'update-bank'=>['system.banks.edit','system.banks.update'],
        ]
    ],


    /*
    * marketing-messages
    */
    [
        'name' => __('Marketing message Permissions'),
        'description' => __('marketing-message Permissions Description'),
        'permissions' => [
            'view-all-marketing-messages'=>['system.marketing-message.index'],
            'view-one-marketing-message'=>['system.marketing-message.show'],
            'delete-one-marketing-message'=>['system.marketing-message.destroy'],
            'create-marketing-message'=>['system.marketing-message.create','system.marketing-message.store'],
            'update-marketing-message'=>['system.marketing-message.edit','system.marketing-message.update'],
        ]
    ],


    /*
    * loyalty-program-ignores
    */
    [
        'name' => __('Loyalty program ignore Permissions'),
        'description' => __('loyalty-program-ignore Permissions Description'),
        'permissions' => [
            'view-all-loyalty-programs-ignores'=>['system.loyalty-program-ignore.index'],
            'view-one-loyalty-program-ignores'=>['system.loyalty-program-ignore.show'],
            'delete-one-loyalty-program-ignores'=>['system.loyalty-program-ignore.destroy'],
            'create-loyalty-program-ignores'=>['system.loyalty-program-ignore.create','system.loyalty-program-ignore.store'],
            'update-loyalty-program-ignores'=>['system.loyalty-program-ignore.edit','system.loyalty-program-ignore.update'],
        ]
    ],


    /*
    * loyalty-programss
    */
    [
        'name' => __('Loyalty programs Permissions'),
        'description' => __('Loyalty programs Permissions Description'),
        'permissions' => [
            'view-all-loyalty-programs'=>['system.loyalty-programs.index'],
            'view-one-loyalty-programs'=>['system.loyalty-programs.show'],
            'delete-one-loyalty-programs'=>['system.loyalty-programs.destroy'],
            'create-loyalty-programs'=>['system.loyalty-programs.create','system.loyalty-programs.store'],
            'update-loyalty-programs'=>['system.loyalty-programs.edit','system.loyalty-programs.update'],
        ]
    ],

    /*
    * activity-log
    */
    [
        'name' => __('System activity log Permissions'),
        'description' => __('System activity log Permissions Description'),
        'permissions' => [
            'view-activity-log'=>['system.activity-log.show'],
            ]
    ],

    [
        'name' => __('wallets & Transactions Permissions'),
        'description' => __('Wallet Permissions Description'),
        'permissions' => [
                'view-all-transactions'=>['system.wallet.transactions'],
                'view-one-transaction'=>['system.wallet.transactions.show'],
                'main-wallets'=>['system.wallet.main-wallets'],
                'supervisor-transfer-money'=>['system.wallet.transfer-money-supervisor','system.wallet.transfer-money-supervisor.post'],
                'staff-transfer-money'=>['system.wallet.transfer-money-staff','system.wallet.transfer-money-staff.post'],
                'main-wallets-transfer-money'=>['system.wallet.transfer-money-main-wallets','system.wallet.transfer-money-main-wallets.post'],
                'View-wallets'=>['system.wallet.index'],
                'view-one-wallet'=>['system.wallet.show'],
                'view-all-loyalty-wallet'=>['system.loyalty-wallet.index'],
                'view-one-loyalty-wallet'=>['system.loyalty-wallet.show'],
                'view-request-recharge-wallets'=>['system.wallet.requestRechargeWallet'],
                'can-approve-request-recharge-wallets'=> ['transfer-money-main-wallets-without-approval'],
                'transfer-between-all-wallets'=> ['system.wallet.transferMoneyWallets','system.wallet.transferMoneyWalletsPost'],
                'transfer-from-any-wallet-to-any-wallet'=> ['system.wallet.transferMoneyTwoWallets','system.wallet.transferMoneyTwoWalletsPost']
        ]
    ],


    /*
    * Settlement
    */
    [
        'name' => __('system.settlement.generate-report Permissions'),
        'description' => __('Settlement Permissions Description'),
        'permissions' => [
            'generate-report'=>['system.settlement.generate-report','system.settlement.generate-report-port','system.settlement.generate-report-ajax'],
            'view-all-settlement'=>['system.settlement.index'],
            'view-one-settlement'=>['system.settlement.show'],
            'view-all-audio-messages'=>['system.audio-messages.index'],
            'view-one-audio-messages'=>['system.audio-messages.show'],
            ]
    ],

    /*
    * System Setting
    */
    [
        'name' => __('System Permissions'),
        'description' => __('System settings Permissions Description'),
        'permissions' => [
            'system-settings'=>['system.setting.index','system.setting.update'],
            'activity-log'=>['system.activity-log.index','system.activity-log.show']
        ]
    ],


    /*
    * loyalty-programss
    */
    [
        'name' => __('Appointment Permissions'),
        'description' => __('appointment Permissions Description'),
        'permissions' => [
            'view-all-appointment'=>['system.appointment.index'],
            'view-one-appointment'=>['system.appointment.show'],
            'delete-appointment'=>['system.appointment.destroy'],
            'system-appointment-change-status'=>['system.appointment.change-status'],
            'change-appointment-datetime'=>['system.appointment.change-appointment-datetime'],
            ]
    ],

    /*
    * Chat
    */
    [
        'name' => __('system.chat.index Permissions'),
        'description' => __('Chat Permissions Description'),
        'permissions' => [
            'system-chat'=>['system.chat.index','system.chat.get-conversation'],
            ]
    ],

    /*
    * Access Data
    */
    [
        'name' => __('Access data.index Permissions'),
        'description' => __('Access data Permissions Description'),
        'permissions' => [
            'system-access-data-index'=>['system.access-data.index'],
            ]
    ],


];