<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Ramsey\Uuid\Uuid;
use App\Models\InventoryAdjustmentFactorCategory;
use App\Models\InventoryAdjustmentFactorType;
use App\Models\InventoryAdjustmentFactor;
use App\Models\InventoryAdjustment;
use App\Models\InventoryAdjustmentNumber;
use App\Models\InventoryAdjustmentBatch;
use App\Models\InventoryItemBatch;
use App\Models\InventoryStockOnHand;
use App\Models\InventoryStockOnHandByBatch;
use App\Models\InventoryTransaction;
use App\Models\InventoryTransactionAdjustment;
use App\Models\InventoryStore;
use App\Models\InventoryItem;
use App\Models\User;

class InventoryStockAdjustmentsController extends Controller
{
    //
    /**
    * check available item batches and its stock
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function itemBatches(Request  $request){
        $itemId=$request->itemId;
        $Store = InventoryStore::where('uuid','=',session('storeUuid'))->get()->first();
        $stockOnHandByBatch=DB::table('inventory_stock_on_hand_by_batches as isohb')
        ->join('inventory_item_batches as iib','iib.bath_reference_id','isohb.batch')
        ->join('inventory_items as ii','ii.inv_item_id','iib.item')
        ->select('iib.bath_reference_id as batchId','iib.batch_no as batchNo','isohb.quantity as avilableQty','iib.item as itemId','isohb.store as store')
        ->where('isohb.store',$Store->store_id)
        ->where('iib.item',$itemId)
        ->get();
        return $stockOnHandByBatch;
    }

        /**
    * select a factor type
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function TypeFactors(Request  $request){
        $typeId=$request->factorType;
        $factors=InventoryAdjustmentFactor::where('voided',0)->where('type',$typeId)->get();
        return $factors;
    }
    /**
     * viewAdjustments.
     * Total Sales Collection By Collector
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title="View Adjustments";
        $viewAdjustments="View Adjustments";
        $users = User::get();
        $items=InventoryItem::get()->where('voided',0);
        $batches=InventoryItemBatch::get()->where('voided',0);
        $store = InventoryStore::where('uuid','=',session('storeUuid'))->get()->first();
        $adjustments=DB::table('inventory_adjustments as ia')
        ->join('inventory_adjustment_numbers as ian','ian.adjustment','ia.adjustment_id')
        ->Join('inventory_stores as iss', 'iss.store_id', 'ia.store')
        ->select('ia.*','iss.name as storeName','ian.value as adjustmentNumber')
        ->where('ia.voided',0)
        ->where('ian.source',1)
        ->where('ia.store',$store->store_id)
        ->get();
        // $adjustmentBatches=InventoryAdjustmentBatch::get()->where('voided',0);
        // $adjustmentNumbers=InventoryAdjustmentNumber::get()->where('voided',0);
        $adjustmentBatches=DB::table('inventory_adjustment_batches as iab')
        ->join('inventory_adjustment_factors as iaf', 'iaf.adjustment_factor_id','iab.factor')
        ->join('inventory_item_batches as iib','iib.bath_reference_id','iab.batch')
        ->join('inventory_items as ii','ii.inv_item_id','iib.item')
        ->join('item_units as iu','iu.unit_id','ii.units')
        ->select('iab.*','iib.batch_no as batchNo','iib.expire_date as expireDate','ii.name as itemName','iu.name as uOM','iaf.name as factorName')
        ->where('iab.voided',0)
        ->get();
        
        return view('inventory.adjustments',compact(
            'viewAdjustments','items','title','adjustments','adjustmentBatches','users'
        ));
    }
    /**
     * creatorCollections.
     * Total Sales Collection By Collector
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title="Create Adjustments";
        $users = User::get();
        $createAdjustments="Create Adjustments";
        $items=InventoryItem::get()->where('voided',0);
        return view('inventory.adjustments',compact(
            'createAdjustments','items','title'
        ));
    }
    /**
     * Adjustment Factors 
     *
     * @return \Illuminate\Http\Response
     */
    public function indexAdjFactors()
    {
        $tittle="Adjustments Factors";
        $users = User::get();
        $categories = InventoryAdjustmentFactorCategory::get()->where('retired',0);
        $types=InventoryAdjustmentFactorType::get()->where('retired',0);
        $factors=InventoryAdjustmentFactor::get()->where('voided',0);
        return view('inventory.adjFactors',compact(
            'tittle','users','categories','types','factors'
        ));
    }
    /**
     *SAve Adjustment Factors
     *
     * @return \Illuminate\Http\Response
     * @param  \Illuminate\Http\Request  $request
     */
    public function saveAdjFactors(Request  $request)
    {
        try {
            //code...
            DB::beginTransaction();
            $name=$request->name;
            $description=$request->Description;
            $type=$request->type;
            $category=$request->category;
            $uuid=(string)Str::uuid();
            InventoryAdjustmentFactor::create([
                'name' => $name,
                'description' => $description,
                'category' => $category,
                'type' => $type,
                'created_by'=>Auth::user()->id,
                'uuid' => $uuid
            ]);
            DB::commit();
            $notification=array(
                'message'=>"Adjustment Factor has been Saved",
                'alert-type'=>'success',
            );

        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            $notification=array(
                'message'=>$th->getMessage(),
                'alert-type'=>'danger',
            );
            return redirect()->route('adjFactors')->with($notification);
        }
        return redirect()->route('adjFactors')->with($notification);
    }


    /**
     * Store a newly created resource in storage.
     *
     * 
     * @return \Illuminate\Http\Response
     */
    public function createAdjustmentNumber(){
        $adjustmentsNumber = InventoryAdjustmentNumber::where('source',1)->get();
        if (!$adjustmentsNumber->isEmpty()) {
            # code...
            $adjustmentsNumber = $adjustmentsNumber->last();
            $values = explode("/",$adjustmentsNumber->value);
                if ($values[0] == "RECO.".Carbon::now()->format('ym')) {
                    # code...
                    if ($values[2] >= 999) {
                        # code...
                        $new_middle_number=sprintf("%'03d", ($values[1]+1));
                        $new_last_number=substr(sprintf("%'03d", ($values[2]+2)),-3);
                        $new_first_string=$values[0];
                        $adjustments_number=$new_first_string.'/'.$new_middle_number.'/'.$new_last_number;
                    }else{
                        # code...
                        $new_middle_number=sprintf("%'03d", ($values[1]));
                        $new_last_number=sprintf("%'03d", ($values[2]+1));
                        $new_first_string=$values[0];
                        $adjustments_number=$new_first_string.'/'.$new_middle_number.'/'.$new_last_number;
                    }
                }else {
                    # code...
                    $values[0] =="RECO.".Carbon::now()->format('ym');
                    $new_middle_number=sprintf("%'03d", (1));
                    $new_last_number=sprintf("%'03d", (1));
                    $new_first_string=$values[0];
                    $adjustments_number=$new_first_string.'/'.$new_middle_number.'/'.$new_last_number;
                }
        } else {
            # code...
            $new_middle_number=sprintf("%'03d", (1));
            $new_last_number=sprintf("%'03d", (1));
            $new_first_string="RECO.".Carbon::now()->format('ym');
            $adjustments_number=$new_first_string.'/'.$new_middle_number.'/'.$new_last_number;
        }
        return $adjustments_number;
    }
    /**
     * Store an adjustment / Reconsiliation per stock
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * 
     */
    public function store(Request $request)
    {
        //
        try {
            //code...
            DB::beginTransaction();
            $item=$request->item;
            $batch=$request->batchNo;
            $scount=$request->batchQty;
            $pcount=$request->pcount;
            $difference=$request->difference;
            $factor=$request->factor;
            $remark=$request->remark;
            $adjustmentsNumber=$request->adjustmentsNumber;
            $store = InventoryStore::where('uuid','=',session('storeUuid'))->get()->first();
            $adjUuid=(string)Str::uuid();
            InventoryAdjustment::create([
                'store'=>$store->store_id,
                'created_by'=>Auth::user()->id,
                'uuid'=>$adjUuid
            ]);
            $adjustment=InventoryAdjustment::where('uuid',$adjUuid)->get()->first();
            if ($adjustmentsNumber != "" ) {
                # code...
                InventoryAdjustmentNumber::create([
                    'adjustment'=>$adjustment->adjustment_id,
                    'source'=>1,
                    'value'=>$this->createAdjustmentNumber(),
                    'created_by'=>Auth::user()->id,
                    'uuid'=>$adjUuid
                ]);
                InventoryAdjustmentNumber::create([
                    'adjustment'=>$adjustment->adjustment_id,
                    'source'=>2,
                    'value'=>$adjustmentsNumber,
                    'created_by'=>Auth::user()->id,
                    'uuid'=>(string)Str::uuid()
                ]);
            } else {
                # code...
                InventoryAdjustmentNumber::create([
                    'adjustment'=>$adjustment->adjustment_id,
                    'source'=>1,
                    'value'=>$this->createAdjustmentNumber(),
                    'created_by'=>Auth::user()->id,
                    'uuid'=>$adjUuid
                ]);
            }
            foreach($batch as $k => $v){
                $uuid=(string)Str::uuid();
                InventoryAdjustmentBatch::create([
                    'adjustment'=>$adjustment->adjustment_id,
                    'factor'=>$factor[$k],
                    'batch'=>$batch[$k],
                    'quantity'=>$pcount[$k],
                    'remarks'=>$remark[$k],
                    'created_by'=>Auth::user()->id,
                    'uuid'=>$uuid
                ]);
            }
            DB::commit();
            $notification=array(
                'message'=>"Adjustments has been Saved",
                'alert-type'=>'success',
            );
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            $notification=array(
                'message'=>$th->getMessage(),
                'alert-type'=>'danger',
            );
            return redirect()->route('adjustments')->with($notification);
        }
        return redirect()->route('adjustments')->with($notification);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  $batch
     * @param  $store
     * @param  $quantity
     * @param  $category
     * @return \Illuminate\Http\Response
     */
    public function transact($batch,$store,$quantity,$category){
        //
        $itemBatch=InventoryItemBatch::where('bath_reference_id',$batch)->where('voided',0)->get()->first();
        $stockOnHand=InventoryStockOnHand::where('store',$store)->where('item',$itemBatch->item);
        $stockOnBatch=InventoryStockOnHandByBatch::where('store',$store)->where('batch',$batch);
        $transactions=InventoryTransaction::where('store',$store)->where('batch',$batch);
        $uuid=(string)Str::uuid();
            $oldStockOnBatch=$stockOnBatch->get()->first();
            $oldStockOnHand=$stockOnHand->get()->first();
            $transactions = $transactions->get()->last();
            switch($category){
                case '2':
                    $onhandDifference=$oldStockOnBatch->quantity-$quantity;
                    $OnBatchquantity_after=$quantity;
                    $OnHandquantity_after=$oldStockOnHand->quantity-$onhandDifference;

                    $type=2;
                    $quantity_after= $quantity;
                    $transactQty=$transactions->quantity_after-$quantity;
                    break;
                case '1':
                    $onhandDifference=$quantity-$oldStockOnBatch->quantity;
                    $OnHandquantity_after=$oldStockOnHand->quantity+$onhandDifference;
                    $OnBatchquantity_after=$quantity;

                    $type=1;
                    $quantity_after=$quantity;
                    $transactQty=$quantity-$transactions->quantity_after;
                    break;
                default:
                    break;
            }
            $stockOnHand->update([
                'quantity'=>$OnHandquantity_after
            ]);
            $stockOnBatch->update([
                'quantity'=>$OnBatchquantity_after
            ]);
            InventoryTransaction::create([
                'batch'=>$batch,
                'store'=>$store,
                'source'=>4,
                'type'=>$type,
                'quantity'=>$transactQty,
                'quantity_before'=>$transactions->quantity_after,
                'quantity_after'=>$quantity_after,
                'created_by'=>Auth::user()->id,
                'uuid'=>$uuid
            ]); 
        $transaction_id=InventoryTransaction::where('uuid',$uuid)->get()->first()->transaction_id;
        return $transaction_id;
    }
    /**
     * approve the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function approve(Request $request)
    {
        //
        try {
            //code...
            DB::beginTransaction();
            $adjustmentId = $request->adjustmentId;
            $approvalMarks=$request->remark;
            $store = InventoryStore::where('uuid','=',session('storeUuid'))->get()->first();
            $adjustment=InventoryAdjustment::where('adjustment_id',$adjustmentId)->where('store',$store->store_id)->where('approved',0);

            if ($adjustment->get()->isEmpty()) {
                # code...
                $notification=array(
                    'message'=>"Please Change The store Location",
                    'alert-type'=>'info',
                );
                return redirect()->route('adjustments')->with($notification);
            }else {
                # code...
                $adjustment->update([
                    'approved'=>1,
                    'approved_by'=>Auth::user()->id,
                    'approved_remarks'=>$approvalMarks,
                    'date_approved'=>Carbon::now()
                ]);
                $adjustment=InventoryAdjustment::where('adjustment_id',$adjustmentId)->where('store',$store->store_id)->where('approved',1)->get();
                $adjustingStore=$adjustment->first()->store;

                $adjustmentBatches=InventoryAdjustmentBatch::where('adjustment',$adjustmentId)->get();
                foreach ($adjustmentBatches as $adjustmentBatch) {
                    # code...
                    $uuid=(string)Str::uuid();
                    $type=(integer)InventoryAdjustmentFactor::where('adjustment_factor_id',$adjustmentBatch->factor)->get()->first()->type;
                    $transaction_id=$this->transact($adjustmentBatch->batch,$adjustingStore,$adjustmentBatch->quantity,$type);
                    InventoryTransactionAdjustment::create([
                        'transaction'=>(integer)$transaction_id,
                        'adjustment_batch'=>$adjustmentBatch->adjustment_batch_id,
                        'created_by'=>Auth::user()->id,
                        'uuid'=>$uuid
                    ]);
                }
                DB::commit();
                $notification=array(
                    'message'=>"Adjustments has been Approved",
                    'alert-type'=>'success',
                );
            }
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            $notification=array(
                'message'=>$th->getMessage(),
                'alert-type'=>'danger',
            );
            return redirect()->route('adjustments')->with($notification);
        }
        return redirect()->route('adjustments')->with($notification);
    }

    


    /**
     * reject the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function reject(Request $request){
        //
        try {
            //code...
            DB::beginTransaction();
            $adjustmentId = $request->adjustmentId;
            $rejectReason=$request->remark;
            $store = InventoryStore::where('uuid','=',session('storeUuid'))->get()->first();
            $adjustment=InventoryAdjustment::where('adjustment_id',$adjustmentId)->where('store',$store->store_id)->where('approved',0);

            if ($adjustment->get()->isEmpty()) {
                # code...
                $notification=array(
                    'message'=>"Please Change The store Location",
                    'alert-type'=>'info',
                );
                return redirect()->route('adjustments')->with($notification);
            }else {
                # code...
                $adjustment->update([
                    'rejected'=>1,
                    'rejected_by'=>Auth::user()->id,
                    'rejected_reason'=>$rejectReason,
                    'date_rejected'=>Carbon::now()
                ]);
                DB::commit();
                $notification=array(
                    'message'=>"Adjustments has been Rejected",
                    'alert-type'=>'success',
                );
            }
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            $notification=array(
                'message'=>$th->getMessage(),
                'alert-type'=>'danger',
            );
            return redirect()->route('adjustments')->with($notification);
        }
        return redirect()->route('adjustments')->with($notification);
    }

}
