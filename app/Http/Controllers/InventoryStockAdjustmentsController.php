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
use App\Models\InventoryItem;
use App\Models\User;

class InventoryStockAdjustmentsController extends Controller
{
    //
    /**
     * viewAdjustments.
     * Total Sales Collection By Collector
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tittle="Stock Adjustments";
        $viewAdjustments="viewAdjustments";
        $items=InventoryItem::get()->where('voided',0);
        return view('inventory.adjustments',compact(
            'viewAdjustments','items','tittle'
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
        $tittle="Stock Adjustments";
        $createAdjustments="createAdjustments";
        $items=InventoryItem::get()->where('voided',0);
        return view('inventory.adjustments',compact(
            'createAdjustments','items','tittle'
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
                'message'=>"Request has been Saved",
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

}
