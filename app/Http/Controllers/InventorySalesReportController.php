<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventoryCustomer;
use App\Models\InventoryDispensingOrder;
use App\Models\InventoryItem;
use App\Models\InventoryItemPrice;
use App\Models\InventoryPosOrder;
use App\Models\InventorySaleQuote;
use App\Models\InventorySaleQuoteLine;
use App\Models\InventorySaleQuoteReferenceMap;
use App\Models\InventorySaleOrderByQuote;
use App\Models\InventorySaleOrderByQuoteLine;
use App\Models\InventorySaleStatus;
use App\Models\InventoryItemBatch;
use App\Models\InventoryStockOnHand;
use App\Models\InventoryStockOnHandByBatch;
use App\Models\InventoryTransaction;
use App\Models\InventoryStore;
use App\Models\InventoryTransactionDispense;
use App\Models\ItemPriceType;
use App\Models\ItemUnits;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventorySalesReportController extends Controller
{
    //

    public function index(){
        $title="Pharmaceutical Sales Report";
        $reportJson=
            '[    
                { 
                    "reportsId":"reports.byCreator",
                    "name":"Total Collection By Collectors",
                    "route":"creator-collections",
                    "class":"fe fe-money"
                },
                {
                    "reportsId":"reports.ByTransaction",
                    "name":"Total Sales Collection per Transactions",
                    "route":"total-collections-by-transaction",
                    "class":"fe fe-money"
                },
                {
                    "reportsId":"reports.ByCollectors",
                    "name":"Total Sales Collection per Collectors",
                    "route":"collectors-collections",
                    "class":"fe fe-money"
                }
            ]';
        // $reportJson=json_encode($reportJson);
        return view('inventory.reports',compact(
            'title','reportJson'
        ));
    }


    /**
     * creatorCollections.
     * Total Sales Collection By Collector
     *
     * @return \Illuminate\Http\Response
     */
    public function todayCollections(){
        $title="Today Collections";
        $route="today-collections";
        $todayCollections="todayCollections";
        // $today_sales = InventorySaleOrderByQuote::whereBetween('date_created',[ Carbon::now()->startOfDay(),Carbon::now()->endOfDay()])->sum('paid_amount');
        // $today_transaction = InventorySaleOrderByQuote::whereBetween('date_created',[ Carbon::now()->startOfDay(),Carbon::now()->endOfDay()])->count();
        $sales= InventorySaleOrderByQuote::whereBetween('date_created',[ Carbon::now()->startOfDay(),Carbon::now()->endOfDay()])->get();
        $orderQuoteLines=DB::table('inventory_sale_order_by_quote_lines as isobql')
        ->join('inventory_sale_order_by_quotes as isobq','isobq.soq_no','isobql.sale_order_quote')
        ->join('inventory_sale_quote_lines as isql','isql.quote_line_id','isobql.quote_line')
        ->join('inventory_items as ii','ii.inv_item_id','isql.item')
        ->select('isobql.*','ii.name as itemName','isobq.soq_no as saleOrderQuote','isql.quantity as quantity','isql.payable_amount as payable_amount','isobq.payment_methods as paymentMethods')
        ->get();
        $collections["totalAmount"]=$sales->sum('paid_amount');
        $collections["totalTransaction"]=$sales->count();
        foreach($sales as $sale){
            $orderLines=$orderQuoteLines->where('saleOrderQuote',$sale->soq_no);
            $collections["salesLines"][]=$orderLines;
        }

        return view('inventory.view-reports',compact(
            'title','route','todayCollections','collections'
        ));
    }
    /**
     * creatorCollections.
     * Total Sales Collection By Collector
     *
     * @return \Illuminate\Http\Response
     */
    public function creatorCollections(){
        $title="Collection by Collector";
        $route="creator-collections";
        return view('inventory.view-reports',compact(
            'title','route'
        ));
    }
    /**
     * creatorCollections.
     * Total Sales Collection By Collector
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function creatorCollectionsSearch(Request  $request){
        $title="Collection by Collector";
        $route="creator-collections";
        $creatorCollections="creatorCollections";
        $startDate=$request->startDate;
        $endDate=$request->endDate;
        $orderQuoteLines=DB::table('inventory_sale_order_by_quote_lines as isobql')
        ->join('inventory_sale_order_by_quotes as isobq','isobq.soq_no','isobql.sale_order_quote')
        ->join('inventory_sale_quote_lines as isql','isql.quote_line_id','isobql.quote_line')
        ->join('inventory_items as ii','ii.inv_item_id','isql.item')
        ->select('isobql.*','ii.name as itemName','isql.quantity as quantity','isql.payable_amount as payable_amount','isobq.payment_methods as paymentMethods')
        ->whereBetween('isobql.date_created',[$startDate,$endDate])
        ->where('isobql.debt_amount',0)
        ->where('isobq.created_by',Auth::user()->id)->get();
        return view('inventory.view-reports',compact(
            'title','route','creatorCollections','orderQuoteLines'
        ));
        // return redirect()->route($route,compact(
        //     'title','route','creatorCollections','orderQuoteLines'
        // ));
    }


    /**
     * Total Sales Collection by Collectors.
     * Total Sales Collection By Collector
     *
     * @return \Illuminate\Http\Response
     */
    public function totalSalesCollectionByCollectors(){
        $title="Total Sales Collection by Collectors";
        $route="collectors-collections";
        return view('inventory.view-reports',compact(
            'title','route'
        ));
    }
    /**
     * totalSalesCollectionByCollectors.
     * Total Sales Collection By Collector
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function totalSalesCollectionByCollectorsSearch(Request $request){
        $totalSalesCollectionByCollectors="totalSalesCollectionByCollectors";
        $startDate=$request->startDate;
        $endDate=$request->endDate;
        $title="Total Sales Collection by Collectors";
        $route="collectors-collections";
        $collectorsCollections=DB::select('SELECT 	u.name as Collectors,
            		SUM(COALESCE(CASE WHEN isobq.payment_methods="Cash" THEN isobq.paid_amount ELSE 0 END, 0)) AS CASH,
					SUM(COALESCE(CASE WHEN isobq.payment_methods="Insurance" THEN isobq.debt_amount ELSE 0 END, 0)) AS INSURANCE
                    FROM inventory_sale_order_by_quotes isobq
                    INNER JOIN users u on  u.id = isobq.created_by
                    WHERE isobq.date_created between ? and ?
                    GROUP BY isobq.created_by ',[$startDate,$endDate]);
        return view('inventory.view-reports',compact(
            'title','route','collectorsCollections','totalSalesCollectionByCollectors'
        ));

    }


    /**
     * Total Sales Collection per Transactions.
     * Total Sales Collection By Collectors
     *
     * @return \Illuminate\Http\Response
     */
    public function totalSalesCollectionPerTransactions(){
        $title="Total Sales Collection per Transactions";
        $route="total-collections-by-transaction";
        return view('inventory.view-reports',compact(
            'title','route'
        ));
    }
    /**
     * Total Sales Collection per Transactions.
     * Tatol Sales Collection per transaction
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function totalSalesCollectionPerTransactionsSearch(Request $request){

        $title="Total Sales Collection per Transactions";
        $route="total-collections-by-transaction";
        $totalSalesCollectionPerTransactions="totalSalesCollectionPerTransactions";
        $startDate=$request->startDate;
        $endDate=$request->endDate;
        $cashOrder=InventorySaleOrderByQuote::whereBetween('date_created',[$startDate,$endDate])
        ->where('payment_methods','Cash')
        ->where('paid_amount','>',0)
        ->get();
        $insuranceOrder=InventorySaleOrderByQuote::whereBetween('date_created',[$startDate,$endDate])
        ->where('payment_methods','Insurance')
        ->where('debt_amount','<',0)
        ->get();
        $orderQuotes=$cashOrder->union($insuranceOrder);
        $orderQuoteLines=DB::table('inventory_sale_order_by_quote_lines as isobql')
        ->join('inventory_sale_order_by_quotes as isobq','isobq.soq_no','isobql.sale_order_quote')
        ->join('inventory_sale_quote_lines as isql','isql.quote_line_id','isobql.quote_line')
        ->join('inventory_items as ii','ii.inv_item_id','isql.item')
        ->select('isobql.*','ii.name as itemName','isobq.soq_no as saleOrderQuote','isql.quantity as quantity','isql.payable_amount as payable_amount','isobq.payment_methods as paymentMethods')
        ->whereBetween('isobql.date_created',[$startDate,$endDate])->get();
        // $orderJson[]=;
        $quote=DB::table('inventory_sale_quotes as isq')
        ->join('inventory_customers as ic','ic.customer_id','isq.customer')
        ->select('isq.*','ic.full_name as fullName')
        ->get();

        foreach ($orderQuotes as $order) {
            # code...
            $orderLines=$orderQuoteLines->where('saleOrderQuote',$order->soq_no);
            $quotes=$quote->where('quote_id',$order->sale_quote)->first();
             $orderJson[]=[
                'soq_no' =>$order->soq_no ,
                'quote' =>$quotes,
                'customer' =>$quotes->fullName,
                'paymentCategory' =>$order->payment_category,
                'datedSaleId' =>$order->dated_sale_id ,
                'paymentMethods' =>$order->payment_methods,
                'payableAmount' =>$order->payable_amount ,
                'paidAmount' =>$order->paid_amount ,
                'debtAmount' =>$order->debt_amount , 
                'createdBy' =>$order->created_by , 
                'dateCreated' =>$order->date_created , 
                'orderLines' => $orderLines
             ];
        }
        $orderJson=json_encode($orderJson);
        return view('inventory.view-reports',compact(
            'title','route','orderJson','totalSalesCollectionPerTransactions'
        ));
        // return redirect()->route('pos-normal')->with(compact('orderJson','items','totalSalesCollectionPerTransactions','startDate','endDate'));
    }



}
