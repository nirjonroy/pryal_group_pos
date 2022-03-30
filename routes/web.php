<?php
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;



Route::get('admin/project/get-project', function () {
    return 'ok';
});


Route::get('/clear', function() {
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    $exitCode = Artisan::call('clear-compiled');
    return 'DONE'; //Return anything
});

Route::get('/',function(){

    return redirect('/login');
});



Route::group(['prefix'=>'admin','middleware' => ['auth']], function(){
    Route::resource('bank-in', 'BankHistoryController');
    Route::resource('bank-out', 'BankHistoryOutController');
    Route::resource('project-type', 'ProjectTypeController');
    Route::resource('company-type', 'CompanyTypeController');
    Route::resource('supplier-type', 'SupplierTypeController');
    
    Route::resource('expense-type', 'ExpenseTypeController');
    Route::resource('unit', 'UnitController');
    Route::resource('info', 'InfoController');
    Route::resource('expense-category', 'ExpenseCategoryController');
    Route::resource('role', 'RoleController');
    Route::resource('user', 'UserController');
    Route::resource('permission', 'PermissionController');
    Route::resource('stores', 'StoreController');
    Route::resource('stock_returns', 'StockReturnController');
    Route::get('/customer-wise-product', 'StockReturnController@customerWiseProduct');

    Route::get('/stock-returns/get-product', 'StockReturnController@getProduct');
    Route::get('/stock-returns/cart-update', 'StockReturnController@cartUpdate');
    Route::get('/stock-returns/remove-cart-single', 'StockReturnController@removeSingleCart');
    Route::get('/stock-returns/remove-cart-all', 'StockReturnController@removeAllCart');
    
    


    Route::get('bank-statement', 'BankHistoryController@bankStatement')->name('statement');
    
    Route::get('bank-statement-by-day', 'BankHistoryController@bankStatementByDay')->name('statement-by-day');

    Route::get('get-project','BankHistoryController@getProject');
    Route::get('get-supplier','BankHistoryController@getsupplier');
    Route::get('get-company','BankHistoryController@getCompany');
    
     Route::get('get-project-new','BankHistoryController@getProjectNew');
    Route::get('get-supplier-new','BankHistoryController@getsupplierNew');
     Route::get('get-customer-new','BankHistoryController@getcustomerNew');
    Route::get('get-company-new','BankHistoryController@getCompanyNew');

    //payment list
     Route::get('received-payment-delete/{id}','PaymentController@receivePaymentDelete');
     Route::get('received-payment-details/{id}','PaymentController@receivePaymentDetails');
     Route::get('received-payment-edit/{id}','PaymentController@receivedPaymentEdit');
     Route::get('received-payment-list','PaymentController@receivedPaymentList');
     Route::post('received-payment-update','PaymentController@receivedPaymentUpdate');

     Route::get('supplier-payment-list','PaymentController@supplierPaymentList');
     Route::get('customer-payment-list','PaymentController@customerPaymentList');
     Route::get('supplier-payment-edit/{id}','PaymentController@supplierPaymentEdit');
     Route::get('customer-payment-edit/{id}','PaymentController@customerPaymentEdit');
     Route::get('supplier-payment-details/{id}','PaymentController@supplierPaymentDetails');
     Route::get('customer-payment-details/{id}','PaymentController@customerPaymentDetails');
     Route::get('supplier-payment-delete/{id}','PaymentController@supplierPaymentDelete');
     Route::get('customer-payment-delete/{id}','PaymentController@customerPaymentDelete');
     Route::post('supplier-payment-update','PaymentController@supplierPaymentUpdate');
     Route::post('customer-payment-update','PaymentController@customerPaymentUpdate');
});

    Route::group(['middleware' => ['auth'],'namespace' => 'Backend'], function(){
        /*# Admin #*/
        Route::group(['as'=> 'admin.', 'prefix'=>'admin' , 'namespace' => 'Admin'], function(){

            

            /*# Company #*/
            Route::group(['namespace' => 'Company'], function(){
                Route::resource('company', 'CompanyController');
            });
            /*# project #*/
            Route::group(['namespace' => 'Project'], function(){
                
                 Route::get('project/get-project', 'ProjectController@getProject');
                 Route::get('project/received-payment', 'ProjectController@receivedPayment');
                 Route::post('project/update-status', 'ProjectController@updateStatus')->name('updateStatus');
                 Route::get('project/status-status/{id}', 'ProjectController@getStatusModal')->name('StatusModal');

                 Route::get('get-payment-modal/{id}', 'ProjectController@getPaymentModal');
                 Route::get('get-details-modal/{id}', 'ProjectController@getDetailsModal');
                 Route::post('project-payment', 'ProjectController@projectPayment');
                Route::resource('project', 'ProjectController');
            });
            /*# Supplier #*/
            Route::group(['namespace' => 'Supplier'], function(){

                Route::post('supplier/purchase-payment', 'SupplierController@purchasePayment');
                Route::get('supplier/supplier-payment', 'SupplierController@supplierPayment');
                Route::get('supplier/payment-modal/{id}', 'SupplierController@getPaymentModal')->name('getSupplierPaymentModal');
                Route::resource('supplier', 'SupplierController');
                

                Route::get('supplier/get-data-by-day/{id}', 'SupplierController@getByDay');
                Route::get('supplier/last-3-month/{id}', 'SupplierController@getByMonth');
                Route::get('admin-supplier-payment', 'SupplierController@supplier_payment')->name('admin_supplier_payment');
                // Route::get('supplier/onetimeUpdate/{id}', 'SupplierController@updateSupplierPurchase');
            });
            
            
            
            /*# Product #*/
            Route::group(['namespace' => 'Product'], function(){
                Route::resource('product', 'ProductController');
            });
            /*# Purchase #*/
            Route::group(['namespace' => 'Purchase'], function(){
                Route::get('purchase/payment-modal/{id}','PurchaseController@getPaymetModal');
                Route::post('purchase/payment','PurchaseController@Payment')->name('payment');


                Route::resource('purchase', 'PurchaseController');
                Route::resource('stocks', 'AddStockController');
                
                
                Route::get('purchase/product/add/to/cart/default/loading', 'PurchaseController@addToCartProductDefaultLoading')->name('addToCartProductDefaultLoading');

                Route::get('purchase/product/add/to/cart', 'PurchaseController@addToCartProduct')->name('addToCartProduct');

                Route::get('purchase/product/add/to/cart/update', 'PurchaseController@addToCartProductUpdateQtyPrice')->name('addToCartProductUpdateQtyPrice');
                Route::get('purchase/product/add/to/cart/remove/single', 'PurchaseController@addToCartProductRemoveSingle')->name('addToCartProductRemoveSingle');
                Route::get('purchase/product/add/to/cart/remove/all', 'PurchaseController@addToCartProductRemoveAll')->name('addToCartProductRemoveAll');
                Route::post('purchase/payment/bill', 'PurchaseController@paymentBill')->name('paymentBill');
            });
            /*# Expense #*/
            Route::group(['namespace' => 'Expense'], function(){
                Route::resource('expense', 'ExpenseController');
                Route::get('expense/get/project/by/company/id', 'ExpenseController@getProjectByCompanyId')->name('getProjectByCompanyId');
            });
        });

// report 
        Route::group(['prefix'=>'reports'], function(){

            Route::get('daily-statement', 'ReportController@dailyStatement');
            Route::get('daily-statement-last-3-days', 'ReportController@dailyStatementLastThreeDay');

             Route::get('daily-statement-last-5-days', 'ReportController@dailyStatementLastFiveDay');

              Route::get('daily-statement-last-10-days', 'ReportController@dailyStatementLastTenDay');

            Route::get('product-wise', 'ReportController@productWise');
            Route::get('company-wise', 'ReportController@companyWise');
            Route::get('company-wise-complete', 'ReportController@companyWiseComplete');
            // new 
            Route::get('company-wise-work', 'ReportController@companyWiseWork');
            Route::get('company-wise-partner', 'ReportController@companyWisePartner');
            Route::get('yearly-project', 'ReportController@yearlyProject');
            Route::get('yearly-project-details/{year}', 'ReportController@yearWiseProject');
            // end new
            Route::get('project-wise', 'ReportController@ProjectWise');
            Route::get('project-wise', 'ReportController@ProjectWise');
            Route::get('supplier-wise', 'ReportController@supplierWise');
            Route::get('customer-wise', 'ReportController@customerWise');

            Route::get('supplier-wise-report-view/{id}', 'ReportController@supplierWiseView');
            Route::get('customer-wise-report-view/{id}', 'ReportController@customerWiseView');

              Route::get('supplier-wise-report/get-data-by-day/{id}', 'ReportController@getByDay');
              Route::get('supplier-wise-report/last-3-month/{id}', 'ReportController@getByMonth');

            Route::get('purchase-wise', 'ReportController@purchaseWise');
            Route::get('purchase-wise-report-view/{id}', 'ReportController@purchaseWiseReportshow');
            Route::get('purchase-wise-product/{id}', 'ReportController@purchaseWiseProduct');
            Route::get('company-running-details/{id}', 'ReportController@companyRunningDetails');
            Route::get('company-complete-details/{id}', 'ReportController@companyCompleteDetails');
            Route::get('company-work-done-details/{id}', 'ReportController@companyWorkdoneDetails');
            Route::get('company-details/{id}', 'ReportController@companyDetails');
            Route::get('project-details/{id}', 'ReportController@projectDetails');
        });

});


Auth::routes();

Route::get('store/{id}', 'StoreController@destroy_store')->name('stores.delete');


Route::get('client-types', 'ClientController@types')->name('client.name');

Route::get('/home', 'HomeController@index')->name('home');
//transfer
Route::get('sell', 'SellController@index')->name('admin.sell');
Route::get('create-sell', 'SellController@create')->name('admin.sell.create');
Route::post('sell/store', 'SellController@store')->name('store.sell');
Route::get('sell.show/{id}', 'SellController@show')->name('sell.show');
Route::get('sell.edit/{id}', 'SellController@edit')->name('sell.edit');
Route::post('sell.update/{id}', 'SellController@update')->name('sell.update');
Route::get('sell_destroy/{id}', 'SellController@destroy_sell')->name('sell_destroy');
Route::get('sell-payment/{id}', 'SellController@sellPayment')->name('sell-payment');
route::post('sell-payment-update/{id}', 'SellController@update_payment')->name('sell_paymentUpdate');

Route::get('purchase-payments/{id}', 'SellController@purchase_payment')->name('purchase-payment');

Route::get('purchase-payments/{id}', 'Backend\Admin\Purchase\AddStockController@purchasePayments')->name('purchase-payments');
Route::post('purchase-payment-update/{id}', 'Backend\Admin\Purchase\AddStockController@update_payment')->name('purchase_paymentUpdate');
//customer
Route::get('customer', 'CustomerController@index')->name('admin.customer');
Route::get('create-customer', 'CustomerController@create')->name('admin.customer.create');
Route::post('store-customer', 'CustomerController@store')->name('store-customer');
Route::get('customer-edit/{id}', 'CustomerController@edit')->name('admin.customer.edit');
Route::post('customer-update/{id}', 'CustomerController@update')->name('admin.customer.update');
Route::get('customer-delete/{id}', 'CustomerController@destroy_customer')->name('admin.customer.delete');
Route::get('customer/customer-payment', 'CustomerController@customerPayment');
 Route::get('customer/payment-modal', 'CustomerController@getPaymentModal');
Route::post('customer/purchase-payment', 'CustomerController@purchasePayment');
Route::get('admin-customer-view/{id}', 'CustomerController@view')->name('admin.customer.view');
Route::get('customer/payment-modal/{id}', 'CustomerController@getPaymentModal')->name('getCustomerPaymentModal');

Route::get('supplier-delete/{id}', 'SupplierController@destroy')->name('admin.supplier.delete');
// Customer Type
Route::get('customer-type', 'CustomerTypeController@index')->name('customer-type.index');
Route::get('customer-type-create', 'CustomerTypeController@create')->name('customer-type.create');
Route::post('customer-type-store', 'CustomerTypeController@store')->name('customer-type.store');
Route::get('customer-type-edit/{id}', 'CustomerTypeController@edit')->name('customer-type.edit');
Route::post('customer-type-update/{id}', 'CustomerTypeController@update')->name('customer-type.update');
Route::get('customer-type-delete/{id}', 'CustomerTypeController@destroy')->name('customer-type.destroy');




Route::get('sell/loading', 'SellController@ProductDefaultLoading')->name('ProductDefaultLoading');
Route::get('sell/cart', 'SellController@addProductsell')->name('addProductsell');

Route::get('sell/update', 'SellController@sellUpdateQtyPrice')->name('sellUpdateQtyPrice');
Route::get('sell/remove/single', 'SellController@ProductRemoveSingle')->name('ProductRemoveSingle');
Route::get('sell/remove/all', 'SellController@sellProductRemoveAll')->name('sellProductRemoveAll');
Route::post('sell/payment/bill', 'SellController@sellpaymentBill')->name('sellpaymentBill');


Route::get('/store-wise-product', 'SellController@storeWiseProduct')->name('storeWiseProduct');


Route::get('product-stock', 'Backend\Admin\Product\ProductController@product_stock')->name('product-stock');
  // {{ route('admin.purchase.update',$purchase->id) }}

Route::get('product-stock', 'Backend\Admin\Product\ProductController@product_stock')->name('product-stock');


  
Route::get('store-products', 'StoreController@store_product')->name('store_product');  
  