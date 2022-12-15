<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use App\Models\ItemUnits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Ramsey\Uuid\Uuid;

class ItemUnitsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Item Units";
        $users = User::get();
        $units = ItemUnits::with('user')->where('voided','=',0)->get();
        //
        return view('item-units',compact(
            'title','units','users'
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
        ItemUnits::create([
            'name'=>$request->name,
            'uuid'=>$uuid,
            'created_by'=>$userId
        ]);
        $notification=array(
            'message'=>"Item Units has been added",
            'alert-type'=>'success',
        );
        return redirect()->route('itemUnits')->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ItemUnits  $itemUnits
     * @return \Illuminate\Http\Response
     */
    public function show(ItemUnits $itemUnits)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ItemUnits  $itemUnits
     * @return \Illuminate\Http\Response
     */
    public function edit(ItemUnits $itemUnits)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ItemUnits  $itemUnits
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ItemUnits $itemUnits)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ItemUnits  $itemUnits
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //

        $this->validate($request,['reason'=>'required|max:100']);
        $itemUnits = ItemUnits::where('unit_id','=',$request->id);
        // ('unit_id', '=' ,$request->id)->get();
        // $itemUnits->delete();

        $itemUnits->update([
            'voided'=>1,
            'voided_date'=>Carbon::now(),
            'voided_reason'=>$request->reason,
            'voided_by'=>Auth::user()->id
        ]);

        $notification = array(
            'message'=>"Item Unit has been deleted",
            'alert-type'=>'success'
        );
        return back()->with($notification);
    }
}
