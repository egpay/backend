<?php

return [
    /*
    * merchant details
    */
    [
        'name' => __('Merchant details'),
        'description' => __('Merchant details Permissions Description <br>Merchant details Employee Permissions Description'),
        'permissions' => [
            'update-merchant-details'=>['panel.merchant.edit','panel.merchant.update'],
        ]
    ],

    /*
    * Sub merchants
    */
    [
        'name' => __('Sub-merchant Permissions'),
        'description' => __('Sub-merchant Permissions Description <br>Sub-merchant Employee Permissions Description'),
        'permissions' => [
            'view-all-sub-merchants'=>['panel.merchant.sub-merchant.index'],
            'view-one-sub-merchant'=>['panel.merchant.sub-merchant.show'],
            'delete-sub-merchant'=>['panel.merchant.sub-merchant.destroy'],
            'create-sub-merchant'=>['panel.merchant.sub-merchant.create','panel.merchant.sub-merchant.store'],
            'update-sub-merchant'=>['panel.merchant.sub-merchant.edit','panel.merchant.sub-merchant.update'],

            'view-non-reviewed-merchants'=>['panel.merchant.sub-merchant.requested'],
            'edit-non-reviewed-merchants'=>['panel.merchant.sub-merchant.requested.edit','panel.merchant.sub-merchant.requested.update'],

            'view-all-sub-merchant-employees'=>['panel.merchant.sub-merchant-employee.index'],
            'view-one-sub-merchant-employee'=>['panel.merchant.sub-merchant-employee.show'],
            'delete-sub-merchant-employee'=>['panel.merchant.sub-merchant-employee.destroy'],
            'create-sub-merchant-employee'=>['panel.merchant.sub-merchant-employee.create','panel.merchant.sub-merchant-employee.store'],
            'update-sub-merchant-employee'=>['panel.merchant.sub-merchant-employee.edit','panel.merchant.sub-merchant-employee.update'],
        ]
    ],


    /*
    * Merchant branches
    */
    [
        'name' => __('Branches Permissions'),
        'description' => __('Branches Permissions Description <br>Branches Permissions Description'),
        'permissions' => [
            'view-all.branches'=>['panel.merchant.branch.index'],
            'view-one-branch'=>['panel.merchant.branch.show'],
            'delete-branch'=>['panel.merchant.branch.destroy'],
            'create-branch'=>['panel.merchant.branch.create','panel.merchant.branch.store'],
            'update-branch'=>['panel.merchant.branch.edit','panel.merchant.branch.update'],
        ]
    ],


    /*
    * permissions & groups
    */
    [
        'name' => __('Permissions & Groups Permissions'),
        'description' => __('panel merchant staff-group Permissions Description <br>merchant.staff-group Permissions Description'),
        'permissions' => [
            'view-all-group'=>['panel.merchant.staff-group.index'],
            'view-one-group'=>['panel.merchant.staff-group.show'],
            'delete-one-group'=>['panel.merchant.staff-group.destroy'],
            'create-group'=>['panel.merchant.staff-group.create','panel.merchant.staff-group.store'],
            'update-group'=>['panel.merchant.staff-group.edit','panel.merchant.staff-group.update'],

            'view-all-employee'=>['panel.merchant.employee.index'],
            'view-one-employee'=>['panel.merchant.employee.show'],
            'delete-employee'=>['panel.merchant.employee.destroy'],
            'create-employee'=>['panel.merchant.employee.create','panel.merchant.employee.store'],
            'update-employee'=>['panel.merchant.employee.edit','panel.merchant.employee.update'],
        ]
    ],

    /*
    * E-Payment
    */
    [
        'name' => __('E-payment Permissions'),
        'description' => __('panel merchant product-attribute Permissions Description <br>panel.merchant.payment.index Permissions Description'),
        'permissions' => [
            'view-all-epayment-services'=>['panel.merchant.payment.index','panel.merchant.payment.service','panel.merchant.payment.getUserServiceByTransaction'],
            'process-epayment-services'=>['panel.merchant.payment.service.payment','panel.merchant.payment.service.inquiry','panel.merchant.payment.service.prepaid','panel.merchant.payment.service.totalamount'],

            //Invoice
            'view-all-epayment-invoices'=>['panel.merchant.payment.invoice.index'],
            'view-one-epayment-invoice'=>['panel.merchant.payment.transactions.ajax-details','panel.merchant.payment.invoice.show'],

            //Transaction
            'view-all-epayment-transactions'=>['panel.merchant.payment.transactions.list','panel.merchant.payment.transactions.ajax-details'],

            //transfer
            'transfer-credit-to-merchants'=>['panel.merchant.payment.transfer','panel.merchant.payment.transfer.do'],
        ]
    ],

    /*
    * E-Commerce
    */
    [
        'name' => __('E-commerce Permissions'),
        'description' => __('product categories Permissions Description <br>merchant.product-category Permissions Description'),
        'permissions' => [
            'view-all-categories'=>['panel.merchant.product-category.index'],
            'view-one-category'=>['panel.merchant.product-category.show'],
            'delete-one-category'=>['panel.merchant.product-category.destroy'],
            'create-category'=>['panel.merchant.product-category.create','panel.merchant.product-category.store'],
            'update-category'=>['panel.merchant.product-category.edit','panel.merchant.product-category.update'],

            'view-all-product'=>['panel.merchant.product.index'],
            'view-one-product'=>['panel.merchant.product.show'],
            'delete-product'=>['panel.merchant.product.destroy'],
            'create-product'=>['panel.merchant.product.create','panel.merchant.product.store'],
            'update-product'=>['panel.merchant.product.edit','panel.merchant.product.update'],
            'approve-product'=>['panel.merchant.product.approve'],

            /*
            'view-all-product-attribute'=>['panel.merchant.product-attribute.index'],
            'view-one-product-attribute'=>['panel.merchant.product-attribute.show'],
            'delete-product-attribute'=>['panel.merchant.product-attribute.destroy'],
            'create-product-attribute'=>['panel.merchant.product-attribute.create','panel.merchant.product-attribute.store'],
            'update-product-attribute'=>['panel.merchant.product-attribute.edit','panel.merchant.product-attribute.update'],
            */

            'view-merchant.order'=>['panel.merchant.order.index'],
            'view-one-merchant.order'=>['panel.merchant.order.show','panel.merchant.order.qrcode'],
            'delete-one-merchant.order'=>['panel.merchant.order.destroy'],
            'create-merchant.order'=>['panel.merchant.order.create','panel.merchant.order.store'],
            'update-merchant.order'=>['panel.merchant.order.edit','panel.merchant.order.update'],
        ]
    ],


    /*
   * Wallet
   */
    [
        'name' => __('Wallet Permissions'),
        'description' => __('Wallet Permissions Description <br>Wallet Permissions Description'),
        'permissions' => [
            'view-wallets'=>['panel.merchant.wallet.index'],
            'view-all-wallet-transactions'=>['panel.merchant.wallet.transactions'],
            'view-one-wallet-transactions'=>['panel.merchant.wallet.transactions.show'],
            ]
    ],

    /*
    * Merchant Bank accounts
    */
    [
        'name' => __('Bank Accounts Permissions'),
        'description' => __('panel merchant bank Permissions Description <br>merchant.bank Permissions Description'),
        'permissions' => [
            'view-all-bank-accounts'=>['panel.merchant.bank.index'],
            'view-one-bank'=>['panel.merchant.bank.show'],
            'delete-bank'=>['panel.merchant.bank.destroy'],
            'create-bank'=>['panel.merchant.bank.create','panel.merchant.bank.store'],
            'update-bank'=>['panel.merchant.bank.edit','panel.merchant.bank.update'],
        ]
    ],


    /*
    * Mail
    */
    [
        'name' => __('System Tickets Permissions'),
        'description' => __('panel merchant mail Permissions Description <br>merchant.mail Permissions Description'),
        'permissions' => [
            'view-system-tickets'=>['panel.merchant.mail.index','panel.merchant.mail.show','panel.merchant.mail.destroy','panel.merchant.mail.create',
                    'panel.merchant.mail.store','panel.merchant.mail.edit','panel.merchant.mail.update']
        ]
    ],


    /*
    * panel.merchant.merchant-knowledges
    */
    [
        'name' => __('Knowledge base Permissions'),
        'description' => __('panel merchant merchant-knowledge Permissions Description <br>merchant.merchant-knowledge Permissions Description'),
        'permissions' => [
            'view-all-knowledge-base-items'=>['panel.merchant.merchant-knowledge.index'],
            'view-one-knowledge-base-item'=>['panel.merchant.merchant-knowledge.show'],
            'delete-knowledge-base-item'=>['panel.merchant.merchant-knowledge.destroy'],
            'create-knowledge-base-item'=>['panel.merchant.merchant-knowledge.create','panel.merchant.merchant-knowledge.store'],
            'update-knowledge-base-item'=>['panel.merchant.merchant-knowledge.edit','panel.merchant.merchant-knowledge.update'],
            'search-knowledge-base'=>['panel.merchant.merchant-knowledge.search'],
        ]
    ],


    /*
    * panel.merchant.advertisements
    */
    [
        'name' => __('merchant.advertisement Permissions'),
        'description' => __('panel merchant advertisement Permissions Description <br>merchant.advertisement Permissions Description'),
        'permissions' => [
            'view-all-advertisements'=>['panel.merchant.advertisement.index'],
            'view-one-advertisement'=>['panel.merchant.advertisement.show'],
            'delete-advertisement'=>['panel.merchant.advertisement.destroy'],
            'create-advertisement'=>['panel.merchant.advertisement.create','panel.merchant.advertisement.store'],
            'update-advertisement'=>['panel.merchant.advertisement.edit','panel.merchant.advertisement.update'],
        ]
    ],



    /*
    * panel.merchant.get-appointments
    */
    [
        'name' => __('General permissions'),
        'description' => __('panel merchant product-attribute Permissions Description <br>panel.merchant.get-appointment Permissions Description'),
        'permissions' => [
            'get-appointment'=>['panel.merchant.get-appointment'],
            ]
    ],


];