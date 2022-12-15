<?php

namespace App\Http\Controllers;

use App\Models\ItemGroup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ItemGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $title = "Item Group";
        $users = User::get();
        $groups = ItemGroup::where('voided','=',0)->get();
        //
        return view('item-group',compact(
            'title','groups','users'
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
        ItemGroup::create([
            'name'=>$request->name,
            'uuid'=>$uuid,
            'created_by'=>$userId
        ]);
        $notification=array(
            'message'=>"Item Group has been added",
            'alert-type'=>'success',
        );
        return redirect()->route('itemGroups')->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ItemGroup  $itemGroup
     * @return \Illuminate\Http\Response
     */
    public function show(ItemGroup $itemGroup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ItemGroup  $itemGroup
     * @return \Illuminate\Http\Response
     */
    public function edit(ItemGroup $itemGroup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ItemGroup  $itemGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ItemGroup $itemGroup)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ItemGroup  $itemGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        $this->validate($request,['reason'=>'required|max:100']);
        $itemGroup = ItemGroup::where('group_id','=',$request->id);
        // ('unit_id', '=' ,$request->id)->get();
        // $itemUnits->delete();

        $itemGroup->update([
            'voided'=>1,
            'voided_date'=>Carbon::now(),
            'voided_reason'=>$request->reason,
            'voided_by'=>Auth::user()->id
        ]);

        $notification = array(
            'message'=>"Item Group has been deleted",
            'alert-type'=>'success'
        );
        return back()->with($notification);
    }
}
