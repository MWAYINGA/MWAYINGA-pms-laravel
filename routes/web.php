<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\InventoryInvoiceController;
use App\Http\Controllers\InventoryItemController;
use App\Http\Controllers\InventoryItemPriceController;
use App\Http\Controllers\InventoryStoreController;
use App\Http\Controllers\ItemCategoryController;
use App\Http\Controllers\ItemGroupController;
use App\Http\Controllers\ItemPriceTypeController;
use App\Http\Controllers\ItemUnitsController;
use App\Http\Controllers\CreateSessionController;
use App\Http\Controllers\InventorySupplierController;
use App\Http\Controllers\InventoryRequestController;
use App\Http\Controllers\InventorySalesController;
use App\Http\Controllers\IssuingController;
use App\Http\Controllers\StocksReportsController;
use Doctrine\DBAL\Schema\Index;
use GuzzleHttp\Promise\Create;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['middleware'=>['guest']],function (){
    Route::get('login',[LoginController::class,'index'])->name('login');
    Route::post('login',[LoginController::class,'login']);
    Route::get('register',[RegisterController::class,'index'])->name('register');
    Route::post('register',[RegisterController::class,'store']); 

   

    Route::get('forgot-password',[ForgotPasswordController::class,'index'])->name('forgot-password');
    Route::post('forgot-password',[ForgotPasswordController::class,'reset']);
});

Route::group(['middleware'=>['auth']],function (){

    Route::post('createSessions',[CreateSessionController::class,'index'])->name('createSessions');


    Route::get('dashboard',[DashboardController::class,'index'])->name('dashboard');
    Route::get('logout',[LogoutController::class,'index'])->name('logout');

    Route::get('categories',[CategoryController::class,'index'])->name('categories');
    Route::post('categories',[CategoryController::class,'store']);
    Route::put('categories',[CategoryController::class,'update']);
    Route::delete('categories',[CategoryController::class,'destroy']);

    Route::get('products',[ProductController::class,'index'])->name('products');
    Route::get('products/create',[ProductController::class,'create'])->name('add-product');
    Route::get('expired-products',[ProductController::class,'expired'])->name('expired');
    Route::get('products/{product}',[ProductController::class,'show'])->name('edit-product');
    Route::get('outstock-products',[ProductController::class,'outstock'])->name('outstock');
    Route::post('products/create',[ProductController::class,'store']);
    Route::post('products/{product}',[ProductController::class,'update']);
    Route::delete('products',[ProductController::class,'destroy']);

    Route::get('items',[InventoryItemController::class,'index'])->name('items');
    Route::get('items/create',[InventoryItemController::class,'create'])->name('add-item');
    Route::get('items/expired',[InventoryItemController::class,'expired'])->name('expired');
    Route::get('items/outstock',[InventoryItemController::class,'outstock'])->name('outstock');
    Route::get('items/{item}',[InventoryItemController::class,'show'])->name('edit-item');
    Route::post('items/create',[InventoryItemController::class,'store']);
    Route::post('items/{item}',[InventoryItemController::class,'update']);
    Route::delete('items',[InventoryItemController::class,'destroy']);

    Route::get('suppliers',[SupplierController::class,'index'])->name('suppliers');
    Route::get('add-supplier',[SupplierController::class,'create'])->name('add-supplier');
    Route::post('add-supplier',[SupplierController::class,'store']);
    Route::get('suppliers/{supplier}',[SupplierController::class,'show'])->name('edit-supplier');
    Route::delete('suppliers',[SupplierController::class,'destroy']);
    Route::put('suppliers/{supplier}}',[SupplierController::class,'update'])->name('edit-supplier');


    Route::get('invoices',[InventoryInvoiceController::class,'index'])->name('invoices');
    Route::get('invoices/create',[InventoryInvoiceController::class,'create'])->name('add-invoice');
    Route::post('invoices/create',[InventoryInvoiceController::class,'store']);
    Route::post('invoices/approve',[InventoryInvoiceController::class,'update'])->name('invoice-approval');

    Route::get('supplier',[InventorySupplierController::class,'index'])->name('supplier');
    Route::get('supplier/create',[InventorySupplierController::class,'create'])->name('new-supplier');
    Route::post('supplier/create',[InventorySupplierController::class,'store']);

    Route::get('stocks',[InventoryRequestController::class,'index'])->name('requisition');
    Route::get('stocks/requisition',[InventoryRequestController::class,'create'])->name('new-request');
    Route::post('stocks/requisition',[InventoryRequestController::class,'store']);
    Route::post('stocks/requisition',[InventoryRequestController::class,'approval'])->name('requisition-approval');
    Route::get('stocks/issuing',[IssuingController::class,'index'])->name('issuing');
    Route::post('stocks/issuing',[IssuingController::class,'store']);
    Route::post('stocks/bincard',[StocksReportsController::class,'bincard'])->name('binCards');
    Route::post('stocks/ledger',[StocksReportsController::class,'ledger'])->name('ledger');

    Route::get('stores',[InventoryStoreController::class,'index'])->name('stores');
    Route::get('stores/create',[InventoryStoreController::class,'create'])->name('add-store');
    Route::post('stores/create',[InventoryStoreController::class,'store']);

    Route::get('purchases',[PurchaseController::class,'index'])->name('purchases');
    Route::get('add-purchase',[PurchaseController::class,'create'])->name('add-purchase');
    Route::post('add-purchase',[PurchaseController::class,'store']);
    Route::get('purchases/{purchase}',[PurchaseController::class,'show'])->name('edit-purchase');
    Route::put('purchases/{purchase}',[PurchaseController::class,'update']);
    Route::delete('purchases',[PurchaseController::class,'destroy']);

    Route::get('sales',[SalesController::class,'index'])->name('sales');
    Route::post('sales',[SalesController::class,'store']);
    Route::put('sales',[SalesController::class,'update']);
    Route::delete('sales',[SalesController::class,'destroy']);

    Route::get('pos',[InventorySalesController::class,'index'])->name('pos-normal');
    Route::post('pos',[InventorySalesController::class,'store']);
    Route::get('pos/quotations',[InventorySalesController::class,'quotations'])->name('pos-quotations');
    Route::post('pos/quotations',[InventorySalesController::class,'saveQuotations']);
    Route::get('pos/orders',[InventorySalesController::class,'orders'])->name('pos-orders');
    Route::post('pos/orders',[InventorySalesController::class,'saveOrders']);
    Route::post('pos/stockPrice',[InventorySalesController::class,'itemPriceStockAvailable'])->name('item-price-stock-available');
    Route::get('pos/insurance',[InventorySalesController::class,'posInsurance'])->name('pos-insurance');
    Route::post('pos/insurance',[InventorySalesController::class,'storeInsurance']);
    Route::post('pos',[InventorySalesController::class,'checkStatus']);
    // Route::delete('sales',[InventorySalesController::class,'destroy']);

    Route::get('itemUnits',[ItemUnitsController::class,'index'])->name('itemUnits');
    Route::get('item-price',[InventoryItemPriceController::class,'index'])->name('item-price');
    Route::post('itemUnits',[ItemUnitsController::class,'store']);
    Route::put('itemUnits',[ItemUnitsController::class,'update']);
    Route::delete('itemUnits',[ItemUnitsController::class,'destroy']);

    Route::get('itemGroups',[ItemGroupController::class,'index'])->name('itemGroups');
    Route::post('itemGroups',[ItemGroupController::class,'store']);
    Route::put('itemGroups',[ItemGroupController::class,'update']);
    Route::delete('itemGroups',[ItemGroupController::class,'destroy']);

    Route::get('itemCategory',[ItemCategoryController::class,'index'])->name('itemCategory');
    Route::post('itemCategory',[ItemCategoryController::class,'store']);
    Route::put('itemCategory',[ItemCategoryController::class,'update']);
    Route::delete('itemCategory',[ItemCategoryController::class,'destroy']);


    Route::get('priceType',[ItemPriceTypeController::class,'index'])->name('priceType');
    Route::post('priceType',[ItemPriceTypeController::class,'store']);
    Route::put('priceType',[ItemPriceTypeController::class,'update']);
    Route::delete('priceType',[ItemPriceTypeController::class,'destroy']);


    Route::get('permissions',[PermissionController::class,'index'])->name('permissions');
    Route::post('permissions',[PermissionController::class,'store']);
    Route::put('permissions',[PermissionController::class,'update']);
    Route::delete('permissions',[PermissionController::class,'destroy']);

    Route::get('roles',[RoleController::class,'index'])->name('roles');
    Route::post('roles',[RoleController::class,'store']);
    Route::put('roles',[RoleController::class,'update']);
    Route::delete('roles',[RoleController::class,'destroy']);

    Route::get('users',[UserController::class,'index'])->name('users');
    Route::post('users',[UserController::class,'store']);
    Route::put('users',[UserController::class,'update']);
    Route::delete('users',[UserController::class,'destroy']);

    Route::get('profile',[UserController::class,'profile'])->name('profile');
    Route::post('profile',[UserController::class,'updateProfile']);
    Route::put('profile',[UserController::class,'updatePassword'])->name('update-password');

    Route::get('settings',[SettingController::class,'index'])->name('settings');

    Route::get('notification',[NotificationController::class,'markAsRead'])->name('mark-as-read');
    Route::get('notification-read',[NotificationController::class,'read'])->name('read');

    Route::get('reports',[ReportController::class,'index'])->name('reports');
    Route::post('reports',[ReportController::class,'getData']);

    Route::get('backup',[BackupController::class,'index'])->name('backup-app');
    Route::get('backup-app',[BackupController::class,'database'])->name('backup-db');
});

Route::get('/', function () {
    return redirect()->route('dashboard');
});
