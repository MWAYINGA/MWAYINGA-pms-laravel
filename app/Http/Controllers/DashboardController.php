<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sales;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use App\Models\InventoryStore;
use Illuminate\Http\Request;
use App\Notifications\StockAlert;
use App\Events\ProductReachedLowStock;
use App\Models\InventoryItemBatch;
use App\Models\InventorySaleOrderByQuote;
use App\Models\InventoryStockOnHand;
use Illuminate\Support\Carbon as SupportCarbon;

class DashboardController extends Controller
{
    public function index(){   
        $title = "dashboard";
        
        $total_purchases = Purchase::where('expiry_date','=',Carbon::now())->count();
        $total_categories = Category::count();
        $total_suppliers = Supplier::count();
        $total_sales = Sales::count();
        $Stores= InventoryStore::where('retired',0)->get();
        
        $pieChart = app()->chartjs
                ->name('pieChart')
                ->type('pie')
                ->size(['width' => 400, 'height' => 200])
                ->labels(['Total Purchases', 'Total Suppliers','Total Sales'])
                ->datasets([
                    [
                        'backgroundColor' => ['#FF6384', '#36A2EB','#7bb13c'],
                        'hoverBackgroundColor' => ['#FF6384', '#36A2EB','#7bb13c'],
                        'data' => [$total_purchases, $total_suppliers,$total_sales]
                    ]
                ])
                ->options([]);
        
                
        // $expireDateEnd=Carbon::now()->addMonths(3);
        $near_to_expired_products = InventoryItemBatch::whereBetween('expire_date',[ Carbon::now(),Carbon::now()->addMonths(3)])->count();
        $latest_sales = Sales::whereDate('created_at','=',Carbon::now())->get();
        $today_sales = InventorySaleOrderByQuote::whereBetween('date_created',[ Carbon::now()->startOfDay(),Carbon::now()->endOfDay()])->sum('paid_amount');
        $today_transaction = InventorySaleOrderByQuote::whereBetween('date_created',[ Carbon::now()->startOfDay(),Carbon::now()->endOfDay()])->count();
        $outOfStock=InventoryStockOnHand::where('quantity',0)->count();
        return view('dashboard',compact(
            'title','pieChart','near_to_expired_products',
            'latest_sales','today_sales','today_transaction','Stores','outOfStock'
        ));
    }
}
