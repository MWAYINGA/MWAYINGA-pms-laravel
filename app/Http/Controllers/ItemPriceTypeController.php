<?php

namespace App\Http\Controllers;

use App\Models\ItemPriceType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ItemPriceTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $title = "Item Price Type";
        $users = User::get();
        $itemPriceTypes = ItemPriceType::where('retired','=',0)->get();
        return view('inventory.items-price-type',compact(
            'title','users','itemPriceTypes',
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request,[
            'name'=>'required|max:100',
        ]);
        $uuid=(string)Str::uuid();
        $userId=Auth::user()->id;
        ItemPriceType::create([
            'name'=>$request->name,
            'description'=>$request->description,
            'uuid'=>$uuid,
            'created_by'=>$userId
        ]);
        $notification=array(
            'message'=>"Price Type has been added",
            'alert-type'=>'success',
        );
        return redirect()->route('priceType')->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ItemPriceType  $itemPriceType
     * @return \Illuminate\Http\Response
     */
    public function show(ItemPriceType $itemPriceType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ItemPriceType  $itemPriceType
     * @return \Illuminate\Http\Response
     */
    public function edit(ItemPriceType $itemPriceType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ItemPriceType  $itemPriceType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ItemPriceType $itemPriceType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ItemPriceType  $itemPriceType
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        $this->validate($request,['reason'=>'required|max:100']);
        $itemPriceType = ItemPriceType::where('price_type_id','=',$request->id);
        $itemPriceType->update([
            'retired'=>1,
            'retired_date'=>Carbon::now(),
            'retired_reason'=>$request->reason,
            'retired_by'=>Auth::user()->id
        ]);

        $notification = array(
            'message'=>"Item Unit has been deleted",
            'alert-type'=>'success'
        );
        return back()->with($notification);
    }
}
