<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use Illuminate\Support\Facades\Route;

Route::get('/', 'CatalogCategoryController@index');
Route::get('/p/{upi_id}/{slug?}', 'ProductController@showForGetMethod')
    ->where(['upi_id' => '[0-9]+']);
Route::get('/p/{slug}/{token}', 'ProductController@viewProductByToken');

// for product url
Route::get('product/{upi_id}/{slug?}', [
    'as'   => 'product.url',
    'uses' => 'ProductController@showForGetMethod'
]);

Route::post('product/{upi_id}/{product}', [
    'as'   => 'product.url',
    'uses' => 'ProductController@show'
]);

Route::post('product/dop-products', 'ProductController@dopProducts');

Route::get('upi/{upi_id_route}', 'ProductController@showByUpi');
// for product url

Route::get('catalog/{categorySlug}', 'CatalogCategoryController@show');

Route::get('login/callback/{provider}', 'Auth\AuthController@getSocialAuthCallback');

// http://allfor2.com/login/callback_deauthorize/facebook
Route::get('logout/callback_deauthorize/{provider}', 'Auth\AuthController@getSocialDeauthCallback');
// http://allfor2.com/login/callback_delete/facebook
Route::get('logout/callback_delete/{provider}', 'Auth\AuthController@getSocialDeleteCallback');

Route::get('login/{provider}', 'Auth\AuthController@getSocialAuth');

Route::get('read/{slug}', 'CatalogArticlesController@showArticleshowArticle');

Route::get('order/success', 'OrderController@successOrder');

Route::get('ie8', 'AdminController@forIe8');


Route::post('contacts', function () {
    return view('showContacts');
});

Route::post('deliveryInfo', function () {
    return view('deliveryInfo');
});

Route::post('payment', function () {
    return view('paymentInfo');
});

Route::post('aboutUs', function () {
    return view('aboutUs');
});


// Cart Page
Route::group(
    [
        'prefix' => 'cart'
    ],
    static function () {
        get('/', [
            'as'   => 'cart',
            'uses' => 'CartController@index'
        ]);

        get('clean', 'CartController@clean');

        get('/get_city', 'CartController@getCity');

        get('/get-state', 'CartController@getState');
    }
);


Route::group(['prefix' => 'chat'], function () {
    get('/', ['as' => 'chat', 'middleware' => 'auth.admin', 'uses' => 'ChatController@index']);

    post('create', ['as' => 'chat.create', 'uses' => 'ChatController@create']);

    post('get-chats', ['middleware' => 'auth.admin', 'uses' => 'ChatController@getChats']);

    get('{id}', ['as' => 'chat.read', 'uses' => 'ChatController@show']);

    post('getNewMessages/{id}', 'ChatController@getNewMessages');

    post('new-message/{id}', ['as' => 'chat.new.message', 'uses' => 'ChatController@createNewMessage']);
});


Route::group(['middleware' => 'auth', 'prefix' => 'messages'], function () {
    get('/', ['as' => 'messages', 'uses' => 'MessagesController@index']);

    get('new', ['as' => 'messages.new', 'uses' => 'MessagesController@getNewThreads']);

    get('trashed', ['as' => 'messages.trashed', 'uses' => 'MessagesController@getTrashedThreads']);

    get('create', ['as' => 'messages.create', 'uses' => 'MessagesController@create']);

    post('/', ['as' => 'messages.store', 'uses' => 'MessagesController@store']);

    get('{id}', ['as' => 'messages.show', 'uses' => 'MessagesController@show']);

    post('update/{id}', ['as' => 'messages.update', 'uses' => 'MessagesController@updateAjax']);

    post('delete', ['as' => 'messages.delete', 'uses' => 'MessagesController@delete']);

    post('force-delete', ['as' => 'messages.forceDelete', 'uses' => 'MessagesController@forceDelete']);

    post('get-between', ['as' => 'messages.getBetween', 'uses' => 'MessagesController@getThreadsBetween']);

    post('getNewMessages/{id}', 'MessagesController@getNewMessages');
});

// post for ajax
Route::group(
    ['group_title' => 'post'],
    function () {
        post('/', 'CatalogCategoryController@index');

        post('catalog/{categorySlug}', 'CatalogCategoryController@show');

        post('cart/delete', 'CartController@delete');

        post('cart/update/{coupon_value?}', 'CartController@update');

        post('delivery/getcity', 'CartController@getCity');

        post('addlike/product', 'ProductController@addLike');

        post('cart/add', 'CartController@add');

        post('delivery/save', 'OrderController@create');

        post('order/charge', 'OrderController@charge');

        post('delivery/{hash_order_id}/{order_id}', 'OrderController@orderInStatusPaidWFP');

        post('delivery/answer/{order_id}', 'OrderController@orderAnswerWFP');

        post('invalid-payment-wfp/{order_id}', 'OrderController@invalidPaymentWFP');

        post('save-delivery-user', 'OrderController@saveUserAddress');

        post('send/mail', 'UserController@sendMail');

        post('search', 'CatalogCategoryController@searchProducts');

        post('sub-search', 'CatalogCategoryController@searchProductsAjax');

        post('reviews/store', 'ProductController@storeReviewOrQA');

        post('set-adult', 'CatalogCategoryController@setAdult');

        post('uploadImage/{type}', 'AdminController@uploadImage');

        post('deleteImage/{type}', 'AdminController@deleteImage');

        post('waiting-for-product', 'AdminController@waitForProductSave');

        post('waiting-for-product-auth', 'AdminController@waitForProductSaveAuth');

        post('call-me-save', 'AdminController@callMeSave');
    }
);
// post for ajax

//Payments
Route::group(['group_title' => 'payments'],
    function () {
        post('payment/paypal', [
            'as'   => 'payment',
            'uses' => 'Payment\PayPalController@postPayment',
        ]);

        get('payment/paypal/status', [
            'as'   => 'payment.status',
            'uses' => 'Payment\PayPalController@getPaymentStatus',
        ]);

        get('payment/wallet-one/status', [
            'as'   => 'wallet.one',
            'uses' => 'Payment\WalletOneController@getPaymentStatus',
        ]);

        post('payment/webmoney/status', [
            'as'   => 'paymentWebMoney',
            'uses' => 'Payment\WebMoneyController@getPaymentStatus',
        ]);

        post('payment/liqpay/status', 'Payment\LiqPayController@getPaymentStatus');

        get('order/fail', [
            'as'   => 'orderFail',
            'uses' => 'OrderController@failOrder'
        ]);
    });
//Payments

// User Page
Route::group(
    ['middleware' => 'auth', 'prefix' => 'user'],
    function () {
        get('/', [
            'uses' => 'UserController@getUserProfile'
        ]);

        get('setting', [
            'uses' => 'UserController@getUserSetting'
        ]);

        get('likes', [
            'uses' => 'UserController@getUserProfileProductLikes'
        ]);

        get('visited', 'UserController@getUserProfileProductVisited');

        get('orders/{status}', [
            'uses' => 'UserController@getUserProfileProductByStatus'
        ]);

        post('order/add-review/{product_id}', 'ProductController@addReview');


        post('setting/save', 'UserController@saveUserSetting');

        post('not-paid/delete', 'UserController@deleteUserProfileProductNotPaid');

        post('cart/delete', 'UserController@deleteUserProfileProductCart');

        post('add-to-cart', 'UserController@addToCartUserProfile');

        post('likes/delete', 'UserController@deleteUserProfileProductLikes');

        get('set-as-support', 'UserController@setAsSupport');

        get('getQa', ['as' => 'user.getQa', 'uses' => 'UserController@getQa']);

        get('getQa/trashed', ['as' => 'user.getQaTrashed', 'uses' => 'UserController@getQaTrashed']);

        get('showQa/{id}', ['as' => 'user.showQa', 'uses' => 'UserController@showQa']);

        post('updateQa/{id}', ['as' => 'user.updateQa', 'uses' => 'UserController@updateQa']);

        post('delete-qa', ['as' => 'qa.delete', 'uses' => 'UserController@deleteQa']);
    }
);

//Api
Route::group(
    ['prefix' => 'api'],
    function () {
        post(
            'getCatalogCategories',
            'ApiController@callApiImportCategories'
        );

        post(
            'getCategoriesProducts',
            'ApiController@callApiImportProducts'
        );
        post(
            'getOrders',
            'ApiController@getOrders'
        );
        post(
            'confirmOrders',
            'ApiController@confirmOrders'
        );
    }
);

// Admin Page
Route::group(
    [
        'middleware' => 'auth.admin',
        'prefix'     => 'administrator'
    ],
    function () {
        get('', 'AdminController@index');

        //System errors
        get(
            'systemError',
            [
                'as'   => 'systemError',
                'uses' => 'SystemErrorController@errorsList'
            ]
        );

        post(
            'systemError/trace/{id}',
            ['uses' => 'SystemErrorController@showTrace']
        );

        get('systemError/delete', 'SystemErrorController@delete');

        // Reviews control
        get('reviews', 'AdminController@getReviews');

        get('reviews/archived', ['as' => 'reviews.archived', 'uses' => 'AdminController@getTrashedReviews']);

        Route::get('setRandViewSoldForProducts', 'AdminController@setRandViewSoldForProducts');

        post('reviewsQa/update', 'AdminController@updateReview');

        post('reviewsQa/delete', 'AdminController@deleteReview');

        post('reviewsQa/store', 'AdminController@storeReview');

        post('reviewsQa/archive', 'AdminController@archiveReview');

        post('reviewsQa/restore', 'AdminController@restoreReview');
        // Reviews control

        //User control
        get('users', 'AdminController@showUser');

        get('users/online', 'AdminController@showUsersOnline');

        get('users/add', 'AdminController@createUser');

        post(
            'users/store',
            ['as' => 'user.store', 'uses' => 'AdminController@storeUser']
        );

        get('users/{user}', 'AdminController@editUser');

        post(
            'users/update',
            ['as' => 'user.update', 'uses' => 'AdminController@updateUser']
        );

        post('users/update-active', 'AdminController@updateUserActive');

        post('users/delete', 'AdminController@deleteUser');
        //User control

        //Left menu
        resource(
            'menu',
            'MenuController',
            [
                'except' => 'destroy'
            ]
        );

        post('menu/update-sort-menu', 'MenuController@updateSortMenu');

        post('menu/delete', 'MenuController@delete');
        //Left menu

        //Articles
        resource(
            'articles',
            'CatalogArticlesController',
            [
                'except' => ['destroy', 'show']
            ]
        );

        post('articles/delete', 'CatalogArticlesController@delete');
        //Articles

        //Menu category images control
        post('menuCategory/update-sort-menu', 'AdminController@updateSortCategory');

        get('categoryMenu', 'AdminController@getCategoriesImageForMenu');

        get('editMenuCategory/{category}/edit', 'AdminController@editMenuCategory');

        post(
            'editMenuCategory/{id}/update',
            [
                'as'   => 'menuCategory.update',
                'uses' => 'AdminController@menuCategoryUpdate'
            ]
        );

        get('menuCategoryList', 'AdminController@menuCategoryList');

        //Menu category images control

        //Products control
        get('catalog', 'AdminController@getAllProducts');

        post('catalog/update', 'AdminController@updateProductActive');

        post('catalog/update-hidden', 'AdminController@updateProductHidden');

        post(
            'catalog',
            [
                'as'   => 'product.search',
                'uses' => 'AdminController@getSearchProduct'
            ]
        );

        get('catalog/product-search-log', 'AdminController@getSearchWordsLog');
        //Products control

        //Order control
        post('orders/delete', 'AdminController@deleteOrder');

        post('orders/delete-item', 'AdminController@deleteOrderItems');

        post('orders/update/{order}', [
            'as'   => 'order.update',
            'uses' => 'AdminController@updateOrder'
        ]);

        get('orders/{status?}', [
            'as'   => 'orders',
            'uses' => 'AdminController@orders'
        ]);

        get('orders/edit/{order}', [
            'as'   => 'orders.edit',
            'uses' => 'AdminController@editOrder'
        ]);

        post('orders/search', [
            'as'   => 'order.search',
            'uses' => 'AdminController@searchOrder'
        ]);

        get('check-order-liqpay', 'AdminController@checkOrderStatusOfLiqpayForm');

        post('check-order-liqpay', 'AdminController@checkOrderStatusOfLiqpay');
        //Order control

        //Settings
        get('settings', 'AdminController@listSettings');

        get('settings/add', 'AdminController@addSetting');

        post('settings/store', 'AdminController@storeSetting');

        get('settings/{setting}', 'AdminController@editSetting');

        post('settings/delete', 'AdminController@deleteSetting');

        post(
            'settings/{setting}/update',
            [
                'as'   => 'settings.update',
                'uses' => 'AdminController@updateSetting'
            ]
        );

        get('job-logs', 'AdminController@indexJobLog');

        get(
            'job/{jobName}',
            ['uses' => 'AdminController@showJobLog']
        );

        get('get-info-from-ukr-poshta', 'AdminController@getInfoFromUkrPoshta');

        post('clear-cache', 'AdminController@clearCache');

        post('product/delete-all', 'ProductController@deleteAll');

        // SCHEDULER
        get(
            'scheduler',
            [
                'as'         => 'scheduler',
                'uses'       => 'SchedulerController@index',
                'permission' => 'scheduler',
                'item_title' => 'Scheduled jobs'
            ]
        );
        get(
            'scheduler/job/{jobName}',
            ['uses' => 'SchedulerController@showForm']
        );
        post(
            'scheduler/job/{jobName}',
            ['uses' => 'SchedulerController@storeForm']
        );
        get(
            'scheduler/clearJobs',
            ['uses' => 'SchedulerController@clearJobs']
        );
        get(
            'scheduler/refreshJobs',
            ['uses' => 'SchedulerController@refreshJobs']
        );
        get(
            'scheduler/jobLog/{jobName}',
            ['uses' => 'SchedulerController@showJobLog']
        );
        // SCHEDULER

        get('call-me', 'AdminController@callMeIndex');

        post('call-me/set-completed', 'AdminController@callMeSetCompleted');

        get('orders_xls', 'AdminController@exportOrdersToXLS');


        Route::group(
            ['prefix' => 'mail'],
            function () {
                get('create', ['as' => 'mail.create', 'uses' => 'AdminController@mailCreate']);

                post('store', ['as' => 'mail.store', 'uses' => 'AdminController@mailStore']);

                get('/', ['as' => 'mail', 'uses' => 'AdminController@mailIndex']);

                post('update/{id}', ['as' => 'mail.update', 'uses' => 'AdminController@mailUpdate']);

                get('show/{id}', ['as' => 'mail.show', 'uses' => 'AdminController@mailShow']);

                get('archived', ['as' => 'mail.archived', 'uses' => 'AdminController@getTrashedMails']);

                post('archive', ['as' => 'mail.archive', 'uses' => 'AdminController@archiveMail']);

                post('restore', ['as' => 'mail.restore', 'uses' => 'AdminController@restoreMail']);

                post('delete', ['as' => 'mail.delete', 'uses' => 'AdminController@deleteMail']);
            }
        );


        Route::group([
            'prefix' => 'catalog-category'
        ], function () {
            Route::get(
                '/',
                [
                    'as'   => 'catalogCategory',
                    'uses' => 'Admin\CategoryController@index'
                ]
            );

            Route::get('getTree', 'Admin\CategoryController@getTree');

            Route::post(
                'moveNode',
                ['uses' => 'Admin\CategoryController@moveNode']
            );
            Route::post(
                'getNode',
                ['uses' => 'Admin\CategoryController@getNode']
            );
            Route::post(
                'update/{id}',
                ['uses' => 'Admin\CategoryController@update']
            );
            Route::post(
                'addForm/{direct}/{target_node_id}',
                ['uses' => 'Admin\CategoryController@addForm']
            );
            Route::post(
                'add/{direct}/{target_node_id}',
                ['uses' => 'Admin\CategoryController@add']
            );
            Route::post(
                'delete/{id}',
                ['uses' => 'Admin\CategoryController@deleteNode']
            );
        });


        Route::post('uploadImage/{type}', 'Admin\ProductController@uploadImage');

        Route::post('deleteImage/{type}', 'Admin\ProductController@deleteImage');

        //Products
        Route::group([
            'prefix' => 'products'
        ], function () {
            Route::post('get-category-children/{category_id}', 'Admin\CategoryController@getCategoryChildren');

            Route::get('create', 'Admin\ProductController@create');

            Route::post('store', 'Admin\ProductController@store');

            Route::get('edit/{product_id}', 'Admin\ProductController@edit');

            Route::post('update/{product_id}', 'Admin\ProductController@update');

            Route::get('assignAllToCategory/{category_id}', 'Admin\ProductController@assignAllToCategory');

            Route::post('action', 'Admin\ProductController@actionOnEntry');

            //Route::get('rebuildOldImages', 'Admin\ProductController@rebuildOldImages');

            // THIS MUST BE LAST!!!!
            Route::get('{type?}', 'Admin\ProductController@index');
        });

        //AdvertisingCampaign

        Route::group([
            'prefix' => 'advertisingCampaign'
        ], function () {
            Route::get('/', 'Admin\AdvertisingCampaignController@index');

            Route::get('filter', 'Admin\AdvertisingCampaignController@filter');

            Route::get('create', 'Admin\AdvertisingCampaignController@create');

            Route::post('store', 'Admin\AdvertisingCampaignController@store');

            Route::get('edit/{id}', 'Admin\AdvertisingCampaignController@edit');

            Route::post('update/{id}', 'Admin\AdvertisingCampaignController@update');

            Route::post('action', 'Admin\AdvertisingCampaignController@actionOnEntry');

            Route::get('view/{id}', 'Admin\AdvertisingCampaignController@view');
        });

        Route::group(['prefix' => 'coupon', 'namespace' => 'Admin'], function () {
            Route::get('/', ['as' => 'coupon.index', 'uses' => 'CouponController@index']);
            Route::get('/create', ['as' => 'coupon.create', 'uses' => 'CouponController@create']);
            Route::post('/create', ['as' => 'coupon.store', 'uses' => 'CouponController@store']);
            Route::get('/show/{id}', ['as' => 'coupon.show', 'uses' => 'CouponController@show'])
                ->where('id', '[0-9]+');
            Route::get('/edit/{id}', ['as' => 'coupon.edit', 'uses' => 'CouponController@edit'])
                ->where('id', '[0-9]+');
            Route::post('/edit/{id}', ['as' => 'coupon.update', 'uses' => 'CouponController@update'])
                ->where('id', '[0-9]+');
        });
    }
);

Route::controllers(
    [
        'auth'     => 'Auth\AuthController',
        'password' => 'Auth\PasswordController',
    ]
);

// activate user
Route::get('activate', 'UserController@getActivate');
