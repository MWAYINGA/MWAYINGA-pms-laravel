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
        $items=InventoryItem::with('uoms')->where('voided','=',0)->get();
        $units=ItemUnits::where('voided','=',0)->get();
        
        // return view('inventory.pos',compact(
        //     'createNormalSales','title','items','units','users'
        // ));
        $lastTransaction = InventorySaleOrderByQuote::get();
        return view('inventory.singlePos',compact(
            'createNormalSales','title','items','units','users','lastTransaction'
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
        $items=InventoryItem::with('uoms')->where('voided','=',0)->get();
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
        return json_encode(array("available"=>$stockOnHand->quantity, "price"=>$itemPrice->price));

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
        $quotations=InventorySaleQuote::get();
        $quotations=DB::table('inventory_sale_quotes as isq')
        ->join('inventory_customers as ic','ic.customer_id','isq.customer')
        ->join('inventory_sale_statuses as iss','iss.status_id','isq.status')
        ->select('isq.*','ic.full_name as customerName','iss.name as quoteStatus')
        ->orderBy('isq.quote_id','desc')
        ->get();
        $quoteStatuses=InventorySaleStatus::get();
        // $quotationLines=InventorySaleQuoteLine::get();
        $quotationLines=DB::table('inventory_sale_quote_lines as isql')
        ->join('inventory_items as ii','ii.inv_item_id','isql.item')
        ->join('item_units as iu','iu.unit_id','ii.units')
        ->join('inventory_sale_statuses as iss','iss.status_id','isql.status')
        ->select('isql.*','ii.name as itemName','iu.name as uOM','iss.name as lineStatus')
        ->get();
        return view('inventory.quotation',compact(
            'createInsuranceSales','title','items','units','users','quotations','quotationLines','quoteStatuses'
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
        // $quotationLines=InventorySaleQuoteLine::get();
        // $QuotationOrders=InventorySaleOrderByQuote::get();
        $quotationOrders=DB::table('inventory_sale_order_by_quotes as isoq')
        ->join('inventory_sale_quotes as isq','isoq.sale_quote','isq.quote_id')
        ->join('inventory_customers as ic','ic.customer_id','isq.customer')
        ->join('inventory_sale_statuses as iss','iss.status_id','isq.status')
        ->select('isoq.*','ic.full_name as customerName','iss.name as quoteStatus')
        ->orderBy('isoq.soq_no','desc')
        ->get();
        // $quotationOrderLines=InventorySaleOrderByQuoteLine::get();
        $orderLines=DB::table('inventory_sale_order_by_quote_lines as isoql')
        ->join('inventory_sale_quote_lines as isql','isql.quote_line_id','isoql.quote_line')
        ->join('inventory_items as ii','ii.inv_item_id','isql.item')
        ->join('item_units as iu','iu.unit_id','ii.units')
        ->join('inventory_sale_statuses as iss','iss.status_id','isql.status')
        ->select('isoql.*','isql.payable_amount as payable_amount','ii.name as itemName','iu.name as uOM','iss.name as lineStatus')
        ->get();
        return view('inventory.orders',compact(
            'createInsuranceSales','title','items','units','users','quotationOrders','orderLines'
        ));
    }

    /**
     * Store a newly created resource in storage.
     * Save Normal Sales
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function singlePos(Request $request)
    {
        //
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
            $tamount=$request->tamount;
            $totalSalesAmount=array_sum($amount);
            $Store = InventoryStore::where('uuid','=',session('storeUuid'))->get()->first();
                if ($totalSalesAmount == $tamount) {
                    # code...
                    $customerUuid=(string)Str::uuid();
                    if ($customerName){
                        $customer = InventoryCustomer::where('full_name',$customerName)->first();
                        if ($customer){
                            $customerId = $customer->customer_id;
                        }else{
                            InventoryCustomer::create([
                                'full_name'=>$customerName,
                                'created_by'=>Auth::user()->id,
                                'uuid'=>$customerUuid
                            ]);
                            $customer = InventoryCustomer::where('uuid',$customerUuid)->first();
                            $customerId = $customer->customer_id;
                        }
                    }else{
                        $customerName="Pharmaceutical Customer";
                        $customer = InventoryCustomer::where('full_name',"$customerName")->first();
                        if ($customer){
                            $customerId = $customer->customer_id;
                        }else{
                            InventoryCustomer::create([
                                'full_name'=>"Pharmaceutical Customer",
                                'created_by'=>Auth::user()->id,
                                'uuid'=>$customerUuid
                            ]);
                            $customer = InventoryCustomer::where('uuid',$customerUuid)->first();
                            $customerId = $customer->customer_id;
                        }
                    }
                    $uuidQuote=(string)Str::uuid();
                    InventorySaleQuote::create([
                        'total_quote'=>$totalSalesAmount,
                        'payable_amount'=>$totalSalesAmount,
                        'customer'=>$customerId,
                        'status' =>3,
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
                            'units'=>$unit[$k],
                            'quantity'=>$qty[$k],
                            'created_by'=>Auth::user()->id,
                            'uuid'=>$uuid
                        ]);
                        $posOrders=InventoryPosOrder::where('uuid', $uuid)->get()->first();
                        InventoryDispensingOrder::create([
                            'pos_order'=>$posOrders->order_id,
                            'item'=>$item[$k],
                            'units'=>$unit[$k],
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
                            'payment_category'=>1,
                            'price_type'=>2,
                            'quoted_amount'=>$amount[$k],
                            'payable_amount'=>$amount[$k],
                            'status'=>6,
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
                    $payment_methods="Cash";
                    InventorySaleOrderByQuote::create([
                        'dated_sale_id'=>$dated_sale_id,
                        'sale_quote'=>$quote,
                        'payment_category'=>2,
                        'payment_methods'=>$payment_methods,
                        'payable_amount'=>$tamount,
                        'paid_amount'=>$tamount,
                        'debt_amount'=>0,
                        'created_by'=>Auth::user()->id,
                        'uuid'=>$uuidSaleOrder
                    ]);
                    $saleOrder=InventorySaleOrderByQuote::where('uuid',$uuidSaleOrder)->get()->first();
                    $quoteLines=InventorySaleQuoteLine::where('quote',$quote)->get();
                    foreach ($quoteLines as $quoteLine){
                        InventorySaleOrderByQuoteLine::create([
                            'sale_order_quote'=>$saleOrder->soq_no,
                            'quote_line'=>$quoteLine->quote_line_id,
                            'paid_amount'=>$quoteLine->payable_amount,
                            'debt_amount'=>0,
                            'uuid'=>(string)Str::uuid()
                        ]);
                        $reference=InventorySaleQuoteReferenceMap::where('quote_line',$quoteLine->quote_line_id)->get()->first()->reference_value;
                        $invDispense=InventoryDispensingOrder::where('dispensing_order_id',$reference);
                        $invDispense->update([
                            'dispensing_store'=>$Store->store_id,
                            'dipensed'=>1,
                            'dipensed_by'=>Auth::user()->id,
                            'date_dispensed'=>Carbon::now()
                        ]);
                        $stockOnHand=InventoryStockOnHand::where('store',$Store->store_id)->where('item',$quoteLine->item);
                        $oldStockOnHand=$stockOnHand->get()->first();
                        $OnHandquantity_after=$oldStockOnHand->quantity-$quoteLine->quantity;
                        $stockOnHand->update([
                            'quantity'=>$OnHandquantity_after
                        ]);
                        // $itemBatch=InventoryItemBatch::where('item',$$quoteLine->item)->where('voided',0)->get();

                        $itemBatches = DB::table('inventory_stock_on_hand_by_batches as isohb')
                        ->join('inventory_item_batches as iib','iib.bath_reference_id', 'isohb.batch')
                        ->select('isohb.*','iib.bath_reference_id as batch','iib.expire_date as expireDate')
                        ->where('isohb.store',$Store->store_id)
                        ->where('iib.item',$quoteLine->item)
                        ->where('isohb.quantity','>',0)
                        ->orderBy('iib.expire_date','desc')
                        ->get();
                        
                        $qtyToDeduct=$quoteLine->quantity;
                        foreach ($itemBatches as $batch) {
                            # code...
                            $uuidTransaction=(string)Str::uuid();
                            if($batch->quantity == 0){
                                continue;
                            }
                            else if ($batch->quantity >=$qtyToDeduct) {
                                # code...
                                $stockOnBatch=InventoryStockOnHandByBatch::where('store',$Store->store_id)->where('batch',$batch->batch);
                                $transactions=InventoryTransaction::where('store',$Store->store_id)->where('batch',$batch->batch);                        
                                    $oldStockOnBatch=$stockOnBatch->get()->first();       
                                    $OnBatchquantity_after=$oldStockOnBatch->quantity-$qtyToDeduct;
                                    $stockOnBatch->update([
                                        'quantity'=>$OnBatchquantity_after
                                    ]);
                                    $transactions = $transactions->get()->last();
                                    
                                    $quantity_after=$transactions->quantity_after-$qtyToDeduct;
                                    InventoryTransaction::create([
                                        'batch'=>$batch->batch,
                                        'store'=>$Store->store_id,
                                        'source'=>3,
                                        'type'=>2,
                                        'quantity'=>$qtyToDeduct,
                                        'quantity_before'=>$transactions->quantity_after,
                                        'quantity_after'=>$quantity_after,
                                        'created_by'=>Auth::user()->id,
                                        'uuid'=>$uuidTransaction
                                    ]); 
                                    // dd($itemBatches);
                                $transaction_id=InventoryTransaction::where('uuid',$uuidTransaction)->get()->first()->transaction_id;
                                InventoryTransactionDispense::create([
                                    'transaction'=>$transaction_id,
                                    'dispense_order'=>$reference,
                                    'created_by'=>Auth::user()->id,
                                    'uuid'=>(string)Str::uuid()
                                ]);
                                // dd($itemBatches);
                                break;
                            }else {
                                # code...
                                $minDeduct=$batch->quantity;
                                $qtyDifference = $qtyToDeduct-$minDeduct;
                                $stockOnBatch=InventoryStockOnHandByBatch::where('store',$Store->store_id)->where('batch',$batch->batch);
                                $transactions=InventoryTransaction::where('store',$Store->store_id)->where('batch',$batch->batch);                        
                                    $oldStockOnBatch=$stockOnBatch->get()->first();       
                                    $OnBatchquantity_after=$oldStockOnBatch->quantity-$minDeduct;
                                    $stockOnBatch->update([
                                        'quantity'=>$OnBatchquantity_after
                                    ]);
                                    $transactions = $transactions->get()->last();
                                    $quantity_after=$transactions->quantity_after-$minDeduct;
                                    InventoryTransaction::create([
                                        'batch'=>$batch,
                                        'store'=>$Store->store_id,
                                        'source'=>3,
                                        'type'=>2,
                                        'quantity'=>$minDeduct,
                                        'quantity_before'=>$transactions->quantity_after,
                                        'quantity_after'=>$quantity_after,
                                        'created_by'=>Auth::user()->id,
                                        'uuid'=>$uuidTransaction
                                    ]); 
                                $transaction_id=InventoryTransaction::where('uuid',$uuidTransaction)->get()->first()->transaction_id;
                                InventoryTransactionDispense::create([
                                    'transaction'=>$transaction_id,
                                    'dispense_order'=>$reference,
                                    'created_by'=>Auth::user()->id,
                                    'uuid'=>(string)Str::uuid()
                                ]);
                                $qtyToDeduct=$qtyDifference;
                                continue;
                            }
                        }
                    }
                }
            DB::commit();
            $notification=array(
                'message'=>"Saved Successfully with Payment",
                'alert-type'=>'success',
            );
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            $notification=array(
                'message'=>$th->getMessage()."  ",
                'alert-type'=>'danger',
            );
            return redirect()->route('pos-normal')->with($notification);
        }
        return redirect()->route('pos-normal')->with($notification);
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
                $customerUuid=(string)Str::uuid();
                if ($customerName){
                    $customer = InventoryCustomer::where('full_name',$customerName)->first();
                    if ($customer){
                        $customerId = $customer->customer_id;
                    }else{
                        InventoryCustomer::create([
                            'full_name'=>$customerName,
                            'created_by'=>Auth::user()->id,
                            'uuid'=>$customerUuid
                        ]);
                        $customer = InventoryCustomer::where('uuid',$customerUuid)->first();
                        $customerId = $customer->customer_id;
                    }
                }else{
                    $customerName="Pharmaceutical Customer";
                    $customer = InventoryCustomer::where('full_name',"$customerName")->first();
                    if ($customer){
                        $customerId = $customer->customer_id;
                    }else{
                        InventoryCustomer::create([
                            'full_name'=>"Pharmaceutical Customer",
                            'created_by'=>Auth::user()->id,
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
                    'customer'=>$customerId,
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
                        'units'=>$unit[$k],
                        'quantity'=>$qty[$k],
                        'created_by'=>Auth::user()->id,
                        'uuid'=>$uuid
                    ]);
                    $posOrders=InventoryPosOrder::where('uuid', $uuid)->get()->first();
                    InventoryDispensingOrder::create([
                        'pos_order'=>$posOrders->order_id,
                        'item'=>$item[$k],
                        'units'=>$unit[$k],
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
                        'payment_category'=>1,
                        'price_type'=>2,
                        'quoted_amount'=>$amount[$k],
                        'payable_amount'=>$amount[$k],
                        'status'=>1,
                        'uuid'=>$uuid
                    ]);
                    $line=InventorySaleQuoteLine::where('uuid',$uuid)->get()->first();
                    InventorySaleQuoteReferenceMap::create([
                        'quote_line'=>$line->quote_line_id,
                        'reference_value'=>$dispensingOrders->dispensing_order_id,
                        'reference_type'=>'DISPENSING_ORDER'
                    ]);
                }
                $savedQuotations = json_encode(array("quote"=>$quote, "quoteLine"=>InventorySaleQuoteLine::where('quote',$quote)->get()));
                $saved = $this->saveOrders($savedQuotations);
            }
            // $savedQuotations= array(
            //     'quote'=>$quote,
            //     'quoteLine'=>InventorySaleQuoteLine::where('quote',$quote)->get()
            // );
            DB::commit();
            $notification=array(
                'message'=>"Saved Successfully with STN. ".$saved,
                'alert-type'=>'success',
            );
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            $notification=array(
                'message'=>$th->getMessage(),
                'alert-type'=>'danger',
            );
            return redirect()->route('pos-normal')->with($notification);
        }
        return redirect()->route('pos-normal')->with($notification);
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
        //saveQuotations
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
                'debt_amount'=>$quote->payable_amount,
                'created_by'=>Auth::user()->id,
                'uuid'=>$uuidSaleOrder
            ]);
            $saleOrder=InventorySaleOrderByQuote::where('uuid',$uuidSaleOrder)->get()->first();
            foreach ($quoteLines as $k => $v){
                $quoteLine=InventorySaleQuoteLine::where('quote',$quote->quote_id)->where('quote_line_id',$quoteLines[$k])->get()->first();
                InventorySaleQuoteLine::where('quote',$quote->quote_id)->where('quote_line_id',$quoteLines[$k])->update([
                    'status'=>6
                ]);
                InventorySaleOrderByQuoteLine::create([
                    'sale_order_quote'=>$saleOrder->soq_no,
                    'quote_line'=>$quoteLine->quote_line_id,
                    'paid_amount'=>0,
                    'debt_amount'=>$quoteLine->payable_amount,
                    'uuid'=>(string)Str::uuid()
                ]);
            }
            $totalLines=InventorySaleQuoteLine::where('quote',$quote->quote_id)->get()->count('quote_line_id');
            $confirmedLines=InventorySaleQuoteLine::where('quote',$quote->quote_id)->where('status',6)->get()->count('quote_line_id');
            $status = ($totalLines == $confirmedLines) ? 3 : 2 ;
                InventorySaleQuote::where('quote_id',$quote_id)->update([
                    'status'=>$status
                ]);            
            $notification=array(
                'message'=>"Saved Successfully with Date id:  ".$dated_sale_id,
                'alert-type'=>'success',
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
     * @param  $quotations
     * @return \Illuminate\Http\Response
     */
    public function saveOrders($quotations)
    {
        //saveOrders
        try {
            //code...
            $quote_id=json_decode($quotations)->quote;
            $quoteLines=json_decode($quotations)->quoteLine;
            $uuidSaleOrder=(string)Str::uuid();
            $dated_sale_id=$this->createDatedSaleId();
            $quote = InventorySaleQuote::where('quote_id',$quote_id)->get()->first();
            InventorySaleOrderByQuote::create([
                'dated_sale_id'=>$dated_sale_id,
                'sale_quote'=>$quote->quote_id,
                'payable_amount'=>$quote->payable_amount,
                'payment_category'=>1,
                'paid_amount'=>0,
                'debt_amount'=>$quote->payable_amount,
                'created_by'=>Auth::user()->id,
                'uuid'=>$uuidSaleOrder
            ]);
            $saleOrder=InventorySaleOrderByQuote::where('uuid',$uuidSaleOrder)->get()->first();
            foreach ($quoteLines as $k => $v){
                $quoteLine=InventorySaleQuoteLine::where('quote',$quote->quote_id)->where('quote_line_id',$quoteLines[$k]->quote_line_id)->get()->first();
                InventorySaleQuoteLine::where('quote',$quote->quote_id)->where('quote_line_id',$quoteLines[$k]->quote_line_id)->update([
                    'status'=>6
                ]);
                InventorySaleOrderByQuoteLine::create([
                    'sale_order_quote'=>$saleOrder->soq_no,
                    'quote_line'=>$quoteLine->quote_line_id,
                    'paid_amount'=>0,
                    'debt_amount'=>$quoteLine->payable_amount,
                    'uuid'=>(string)Str::uuid()
                ]);
            }
            $totalLines=InventorySaleQuoteLine::where('quote',$quote->quote_id)->get()->count('quote_line_id');
            $confirmedLines=InventorySaleQuoteLine::where('quote',$quote->quote_id)->where('status',6)->get()->count('quote_line_id');
            $status = ($totalLines == $confirmedLines) ? 3 : 2 ;
                InventorySaleQuote::where('quote_id',$quote_id)->update([
                    'status'=>$status
                ]);            
            $notification=array(
                'message'=>"Saved Successfully with Date id:  ".$dated_sale_id,
                'alert-type'=>'success',
            );
            
        return $dated_sale_id;
        } catch (\Throwable $th) {
            $notification=array(
                'message'=>$th->getMessage(),
                'alert-type'=>'danger',
            );
            return $notification;
        }

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
                            'created_by'=>Auth::user()->id,
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
                    'customer'=>$customerId,
                    'status' =>3,
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
                        'units'=>$unit[$k],
                        'quantity'=>$qty[$k],
                        'created_by'=>Auth::user()->id,
                        'uuid'=>$uuid
                    ]);
                    $posOrders=InventoryPosOrder::where('uuid', $uuid)->get()->first();
                    InventoryDispensingOrder::create([
                        'pos_order'=>$posOrders->order_id,
                        'item'=>$item[$k],
                        'units'=>$unit[$k],
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
                        'payment_category'=>2,
                        'price_type'=>$insuranceType,
                        'quoted_amount'=>$amount[$k],
                        'payable_amount'=>$amount[$k],
                        'status'=>6,
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
                    'payment_category'=>2,
                    'payment_methods'=>"INSURANCE",
                    'payable_amount'=>$quote->payable_amount,
                    'paid_amount'=>0,
                    'debt_amount'=>$quote->payable_amount,
                    'created_by'=>Auth::user()->id,
                    'uuid'=>$uuidSaleOrder
                ]);
                $saleOrder=InventorySaleOrderByQuote::where('uuid',$uuidSaleOrder)->get()->first();
                $quoteLines=InventorySaleQuoteLine::where('quote',$quote->quote_id)->get();
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
                'message'=>"Saved Successfully with STN. \n".$quote->quote_id,
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
            return redirect()->route('pos-insurance')->with($notification);
        }
        return redirect()->route('pos-insurance')->with($notification);
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
        $datedSaleId=InventorySaleOrderByQuote::get();
        if (!$datedSaleId->isEmpty()) {
            # code...
            $saleNumber = $datedSaleId->last();
            $values = explode("-",$saleNumber->dated_sale_id);
            // $values[0]= $values[0] =='INV.'.Carbon::now()->format('Ym') ? $values[0] :
                if ($values[0] ==Carbon::now()->format('ymd')) {
                    # code...
                        $new_last_number=sprintf("%'04d", ($values[1]+1));
                        $new_first_string=$values[0];
                        $sale_number=$new_first_string.'-'.$new_last_number;
                }else {
                    # code...
                    $values[0] =Carbon::now()->format('ymd');
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


        /**
     * Update the specified resource in storage.
     *
     * @param  $batch
     * @param  $item
     * @param  $store
     * @param $quantity
     * @param $operator
     * @return \Illuminate\Http\Response
     */
    public function dispensingTransaction($item,$batch,$store,$quantity,$operator){


        $itemBatch=InventoryItemBatch::where('bath_reference_id',$batch)->where('voided',0)->get()->first();
        $stockOnHand=InventoryStockOnHand::where('store',$store)->where('item',$itemBatch->item);
        $stockOnBatch=InventoryStockOnHandByBatch::where('store',$store)->where('batch',$batch);
        $transactions=InventoryTransaction::where('store',$store)->where('batch',$batch);
        $uuid=(string)Str::uuid();

            $oldStockOnBatch=$stockOnBatch->get()->first();
            $oldStockOnHand=$stockOnHand->get()->first();

                    
            $OnBatchquantity_after=$oldStockOnBatch->quantity-$quantity;
            $OnHandquantity_after=$oldStockOnHand->quantity-$quantity;

            $stockOnHand->update([
                'quantity'=>$OnHandquantity_after
            ]);
            $stockOnBatch->update([
                'quantity'=>$OnBatchquantity_after
            ]);
            $transactions = $transactions->get()->last();
            $type=2;
            $quantity_after= $transactions->quantity_after-$quantity;
            InventoryTransaction::create([
                'batch'=>$batch,
                'store'=>$store,
                'source'=>1,
                'type'=>$type,
                'quantity'=>$quantity,
                'quantity_before'=>$transactions->quantity_after,
                'quantity_after'=>$quantity_after,
                'created_by'=>Auth::user()->id,
                'uuid'=>$uuid
            ]); 
        $transaction_id=InventoryTransaction::where('uuid',$uuid)->get()->first()->transaction_id;
        return $transaction_id;
    }

}
