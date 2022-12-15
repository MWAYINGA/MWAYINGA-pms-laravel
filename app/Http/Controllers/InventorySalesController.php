<?php

namespace App\Http\Controllers;

use App\Models\InventoryCustomer;
use App\Models\InventoryDispensingOrder;
use Illuminate\Http\Request;
use App\Models\InventoryItem;
use App\Models\InventoryItemPrice;
use App\Models\InventoryPosOrder;
use App\Models\InventorySaleQuote;
use App\Models\InventorySaleQuoteLine;
use App\Models\InventorySaleQuoteReferenceMap;
use App\Models\InventorySaleOrderByQuote;
use App\Models\InventorySaleOrderByQuoteLine;
use App\Models\InventoryStore;
use App\Models\InventoryStockOnHand;
use App\Models\ItemPriceType;
use App\Models\ItemUnits;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use PhpParser\Node\Stmt\TryCatch;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Ramsey\Uuid\Uuid;

class InventorySalesController extends Controller
{
    /**
     * Display a listing of the resource.
     * Index for Normal Sales
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $createNormalSales="createNormalSales";
        $title="Normal Sales";
        $users = User::get();
        $items=InventoryItem::where('voided','=',0)->get();
        $units=ItemUnits::where('voided','=',0)->get();
        return view('inventory.pos',compact(
            'createNormalSales','title','items','units','users'
        ));
    }

    /**
     * Display a listing of the resource.
     * Index for Insurance Sales
     *
     * @return \Illuminate\Http\Response
     */
    public function posInsurance()
    {
        //
        $createInsuranceSales="createInsuranceSales";
        $title="Insurance Sales";
        $users = User::get();
        $keyword="Insurance";
        $insuranceType=ItemPriceType::where('name','LIKE','%'.$keyword.'%')->get();
        $items=InventoryItem::where('voided','=',0)->get();
        $units=ItemUnits::where('voided','=',0)->get();
        return view('inventory.pos',compact(
            'createInsuranceSales','title','items','units','users','insuranceType'
        ));
    }


    /**
     * Store a newly created resource in storage.
     * Check Available items quantity and its particaular price
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function itemPriceStockAvailable(Request $request){
        $sessionType = $request->sessionType;
        $item=$request->itemId;
        $insuranceType=$request->insuranceType;
        $Store = InventoryStore::where('uuid','=',session('storeUuid'))->get()->first();

        $itemPrice=0;
        $stockOnHand=InventoryStockOnHand::where('item',$item)
        ->where('store',$Store->store_id)
        ->get()->first();
        if ($sessionType == "createNormalSales") {
            # code...
            $itemPrice=InventoryItemPrice::where('item',$item)
            ->where('price_type',2)
            ->where('voided',0)
            ->get()->first();
        }
        if ($sessionType == "createInsuranceSales") {
            # code...
            $itemPrice=InventoryItemPrice::where('item',$item)
            ->where('price_type',$insuranceType)
            ->where('voided',0)
            ->get()->first();
        }
        return json_encode(array("available"=>$stockOnHand->quantity, "price"=>$itemPrice));

    }
    /**
     * Display a listing of the resource.
     * Index for List of Quotations
     *
     * @return \Illuminate\Http\Response
     */
    public function quotations()
    {
        //
        $createInsuranceSales="createInsuranceSales";
        $title="Quotations";
        $users = User::get();
        $items=InventoryItem::where('voided','=',0)->get();
        $units=ItemUnits::where('voided','=',0)->get();
        $quotations=InventorySaleQuoteLine::get();
        $quotationLines=InventorySaleQuoteLine::get();
        return view('inventory.quotation',compact(
            'createInsuranceSales','title','items','units','users','quotations','quotationLines'
        ));
    }
    public function searchQuotations($quote)
    {
        //
        $createInsuranceSales="createInsuranceSales";
        $title="Quotations";
        $users = User::get();
        $items=InventoryItem::where('voided','=',0)->get();
        $units=ItemUnits::where('voided','=',0)->get();
        // $quotations=InventorySaleQuoteLine::get();
        $quotations = InventorySaleQuote::where('quote_id',$quote)->get();
        $quotationLines=InventorySaleQuoteLine::where('quote',$quote)->get();
        // $quotationLines=InventorySaleQuoteLine::get();
        return view('inventory.quotation',compact(
            'createInsuranceSales','title','items','units','users','quotations','quotationLines'
        ));
    }

    
    /**
     * Display a listing of the resource.
     * Index for List of Orders
     *
     * @return \Illuminate\Http\Response
     */
    public function orders()
    {
        //
        $createInsuranceSales="createInsuranceSales";
        $title="Orders";
        $users = User::get();
        $items=InventoryItem::where('voided','=',0)->get();
        $units=ItemUnits::where('voided','=',0)->get();
        $quotationLines=InventorySaleQuoteLine::get();
        $QuotationOrders=InventorySaleOrderByQuote::get();
        $quotationOrderLines=InventorySaleOrderByQuoteLine::get();
        return view('inventory.orders',compact(
            'createInsuranceSales','title','items','units','users','quotationOrders','quotationOrderLines','quotationLines'
        ));
    }

    /**
     * Store a newly created resource in storage.
     * Save Normal Sales
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //storeNormalSales
        try {
            //code...
            DB::beginTransaction();
            $sessionType = $request->sessionType;
            $item=$request->item;
            $customerName=$request->customerName;
            $qty=$request->qty;
            $unit=$request->unit;
            $price=$request->price;
            $amount=$request->amount;
            $insuranceType=$request->insuranceType;
            $Store = InventoryStore::where('uuid','=',session('storeUuid'))->get()->first();
    
            if ($sessionType == "createNormalSales") {
                # code...
                if ($customerName){
                    $customerUuid=(string)Str::uuid();
                    $customer = InventoryCustomer::where('full_name',$customerName)->first();
                    if ($customer){
                        $customerId = $customer->customer_id;
                    }else{
                        InventoryCustomer::create([
                            'full_name'=>$customerName,
                            'create'=>Auth::user()->id,
                            'uuid'=>$customerUuid
                        ]);
                        $customer = InventoryCustomer::where('uuid',$customerUuid)->first();
                        $customerId = $customer->customer_id;
                    }
                }
                $totalSalesAmount=array_sum($amount);
                $uuidQuote=(string)Str::uuid();
                InventorySaleQuote::create([
                    'total_quote'=>$totalSalesAmount,
                    'payable_amount'=>$totalSalesAmount,
                    'status' =>1,
                    'created_by'=>Auth::user()->id,
                    'uuid' =>$uuidQuote
                ]);
                $quote = InventorySaleQuote::where('uuid',$uuidQuote)->get()->first()->quote_id;
                foreach ($item as $k => $v) {
                    # code...
                    $uuid=(string)Str::uuid();
                    InventoryPosOrder::create([
                        'source'=>2,
                        'item'=>$item[$k],
                        'unit'=>$unit[$k],
                        'quantity'=>$qty[$k],
                        'created_by'=>Auth::user()->id,
                        'uuid'=>$uuid
                    ]);
                    $posOrders=InventoryPosOrder::where('uuid', $uuid)->get()->first();
                    InventoryDispensingOrder::create([
                        'pos_order'=>$posOrders->order_id,
                        'item'=>$item[$k],
                        'unit'=>$unit[$k],
                        'quantity'=>$qty[$k],
                        'equivalent_quantity'=>$qty[$k],
                        'quantifying_store'=>$Store->store_id,
                        'created_by'=>Auth::user()->id,
                        'uuid'=>$uuid
                    ]);
                    $dispensingOrders=InventoryDispensingOrder::where('uuid', $uuid)->get()->first();
                    InventorySaleQuoteLine::create([
                        'quote'=>$quote,
                        'item'=>$item[$k],
                        'units'=>$unit[$k],
                        'quantity'=>$qty[$k],
                        'quoted_amount'=>$amount[$k],
                        'payable_amount'=>$amount[$k],
                        'status'=>5,
                        'uuid'=>$uuid
                    ]);
                    $line=InventorySaleQuoteLine::where('uuid',$uuid)->get()->first();
                    InventorySaleQuoteReferenceMap::create([
                        'quote_line'=>$line->quote_line_id,
                        'reference_value'=>$dispensingOrders->dispensing_order_id,
                        'reference_type'=>'DISPENSING_ORDER'
                    ]);
                }
            }
            DB::commit();
            $notification=array(
                'message'=>"Saved Successfully with STN. \n".$quote,
                'alert-type'=>'success',
            );
        } catch (\Throwable $th) {
            //throw $th;
            //throw $th;
            DB::rollBack();
            $notification=array(
                'message'=>$th->getMessage(),
                'alert-type'=>'danger',
            );
            return redirect()->route('pos')->with($notification);
        }
        return redirect()->route('pos')->with($notification);
    }

    
    /**
     * Store a newly created resource in storage.
     * Saves Sales Quotations
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveQuotations(Request $request)
    {
        //saveQuotations saveOrders
        try {
            //code...
            $quote_id=$request->quote;
            $quoteLines=$request->quoteLine;
            DB::beginTransaction();
            $uuidSaleOrder=(string)Str::uuid();
            $dated_sale_id=$this->createDatedSaleId();
            $quote = InventorySaleQuote::where('quote_id',$quote_id)->get()->first();
            InventorySaleOrderByQuote::create([
                'dated_sale_id'=>$dated_sale_id,
                'sale_quote'=>$quote->quote_id,
                'payable_amount'=>$quote->payable_amount,
                'payment_category'=>1,
                'paid_amount'=>0,
                'dept_amount'=>$quote->payable_amount,
                'created_by'=>Auth::user()->id,
                'uuid'=>$uuidSaleOrder
            ]);
            $saleOrder=InventorySaleOrderByQuote::where('uuid',$uuidSaleOrder)->get()->first();
            foreach ($quoteLines as $k => $v){
                $quoteLine=InventorySaleQuoteLine::where('quote',$quote)->where('quote_line_id',$quoteLines[$k])->get()->first();
                InventorySaleOrderByQuoteLine::create([
                    'sale_order_quote'=>$saleOrder->soq_no,
                    'quote_line'=>$quoteLine->quote_line_id,
                    'paid_amount'=>0,
                    'dept_amount'=>$quoteLine->payable_amount,
                    'uuid'=>(string)Str::uuid()
                ]);
            }
            $notification=array(
                'message'=>"Saved Successfully with Date id ".$dated_sale_id,
                'alert-type'=>'danger',
            );
            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            $notification=array(
                'message'=>$th->getMessage(),
                'alert-type'=>'danger',
            );
            return redirect()->route('pos-quotations')->with($notification);
        }
        return redirect()->route('pos-quotations')->with($notification);
    }

        
        /**
     * Store a newly created resource in storage.
     * Save Sales Orders
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveOrders(Request $request)
    {
        //saveOrders
    }

        /**
     * Store a newly created resource in storage.
     * Save Insurance Sales
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeInsurance(Request $request)
    {
        //storeInsurance
        try {
            //code...
            DB::beginTransaction();
            $sessionType = $request->sessionType;
            $item=$request->item;
            $customerName=$request->customerName;
            $qty=$request->qty;
            $unit=$request->unit;
            $price=$request->price;
            $amount=$request->amount;
            $insuranceType=$request->insuranceType;
            $Store = InventoryStore::where('uuid','=',session('storeUuid'))->get()->first();
    
            if ($sessionType == "createInsuranceSales") {
                # code...
                if ($customerName){
                    $customerUuid=(string)Str::uuid();
                    $customer = InventoryCustomer::where('full_name',$customerName)->first();
                    if ($customer){
                        $customerId = $customer->customer_id;
                    }else{
                        InventoryCustomer::create([
                            'full_name'=>$customerName,
                            'create'=>Auth::user()->id,
                            'uuid'=>$customerUuid
                        ]);
                        $customer = InventoryCustomer::where('uuid',$customerUuid)->first();
                        $customerId = $customer->customer_id;
                    }
                }
                $totalSalesAmount=array_sum($amount);
                $uuidQuote=(string)Str::uuid();
                InventorySaleQuote::create([
                    'total_quote'=>$totalSalesAmount,
                    'payable_amount'=>$totalSalesAmount,
                    'status' =>1,
                    'created_by'=>Auth::user()->id,
                    'uuid' =>$uuidQuote
                ]);
                $quote = InventorySaleQuote::where('uuid',$uuidQuote)->get()->first();
                foreach ($item as $k => $v) {
                    # code...
                    $uuid=(string)Str::uuid();
                    InventoryPosOrder::create([
                        'source'=>2,
                        'item'=>$item[$k],
                        'unit'=>$unit[$k],
                        'quantity'=>$qty[$k],
                        'created_by'=>Auth::user()->id,
                        'uuid'=>$uuid
                    ]);
                    $posOrders=InventoryPosOrder::where('uuid', $uuid)->get()->first();
                    InventoryDispensingOrder::create([
                        'pos_order'=>$posOrders->order_id,
                        'item'=>$item[$k],
                        'unit'=>$unit[$k],
                        'quantity'=>$qty[$k],
                        'equivalent_quantity'=>$qty[$k],
                        'quantifying_store'=>$Store->store_id,
                        'created_by'=>Auth::user()->id,
                        'uuid'=>$uuid
                    ]);
                    $dispensingOrders=InventoryDispensingOrder::where('uuid', $uuid)->get()->first();
                    InventorySaleQuoteLine::create([
                        'quote'=>$quote->quote_id,
                        'item'=>$item[$k],
                        'units'=>$unit[$k],
                        'quantity'=>$qty[$k],
                        'quoted_amount'=>$amount[$k],
                        'payable_amount'=>$amount[$k],
                        'status'=>5,
                        'uuid'=>$uuid
                    ]);
                    $line=InventorySaleQuoteLine::where('uuid',$uuid)->get()->first();
                    InventorySaleQuoteReferenceMap::create([
                        'quote_line'=>$line->quote_line_id,
                        'reference_value'=>$dispensingOrders->dispensing_order_id,
                        'reference_type'=>'DISPENSING_ORDER'
                    ]);
                }
                $uuidSaleOrder=(string)Str::uuid();
                $dated_sale_id=$this->createDatedSaleId();
                InventorySaleOrderByQuote::create([
                    'dated_sale_id'=>$dated_sale_id,
                    'sale_quote'=>$quote->quote_id,
                    'payment_category'=>$insuranceType,
                    'payment_methods'=>"INSURANCE",
                    'payable_amount'=>$quote->payable_amount,
                    'paid_amount'=>0,
                    'dept_amount'=>$quote->payable_amount,
                    'created_by'=>Auth::user()->id,
                    'uuid'=>$uuidSaleOrder
                ]);
                $saleOrder=InventorySaleOrderByQuote::where('uuid',$uuidSaleOrder)->get()->first();
                $quoteLines=InventorySaleQuoteLine::where('quote',$quote)->get();
                foreach ($quoteLines as $quoteLine){
                    InventorySaleOrderByQuoteLine::create([
                        'sale_order_quote'=>$saleOrder->soq_no,
                        'quote_line'=>$quoteLine->quote_line_id,
                        'paid_amount'=>0,
                        'debt_amount'=>$quoteLine->payable_amount,
                        'uuid'=>(string)Str::uuid()
                    ]);
                }  
            }
            DB::commit();
            $notification=array(
                'message'=>"Saved Successfully with STN. \n".$quote,
                'alert-type'=>'success',
            );
        } catch (\Throwable $th) {
            //throw $th;
            //throw $th;
            DB::rollBack();
            $notification=array(
                'message'=>$th->getMessage(),
                'alert-type'=>'danger',
            );
            return redirect()->route('pos')->with($notification);
        }
        return redirect()->route('pos')->with($notification);

    }



    /**
     * Display the specified resource.
     *
     * 
     * @return \Illuminate\Http\Response
     */
    public function createDatedSaleId()
    {
        //
        $datedSaleId=InventorySaleOrderByQuote::where('source',1)->get();
        if (!$datedSaleId->isEmpty()) {
            # code...
            $saleNumber = $datedSaleId->last();
            $values = explode("-",$saleNumber->value);
            // $values[0]= $values[0] =='INV.'.Carbon::now()->format('Ym') ? $values[0] :
                if ($values[0] ==Carbon::now()->format('ymd')) {
                    # code...
                        $new_last_number=sprintf("%'04d", ($values[1]+1));
                        $new_first_string=$values[0];
                        $sale_number=$new_first_string.'-'.$new_last_number;
                }else {
                    # code...
                    $values[0] ==Carbon::now()->format('ymd');
                    $new_last_number=sprintf("%'04d", (1));
                    $new_first_string=$values[0];
                    $sale_number=$new_first_string.'-'.$new_last_number;
                }
        } else {
            # code...
            $new_last_number=sprintf("%'04d", (1));
            $new_first_string=Carbon::now()->format('ymd');
            $sale_number=$new_first_string.'-'.$new_last_number;
        }
        
        return $sale_number;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
