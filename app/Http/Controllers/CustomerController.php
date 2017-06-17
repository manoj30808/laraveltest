<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use App\Vendor;
use DB;

class CustomerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $vendor_name;
    public function __construct()
    {
        $this->vendor_name = Vendor::pluck('name','id');
        \View::share('vendor_name',$this->vendor_name);
        $this->middleware('auth');
    }

    public function manageVue()
    {
        return view('manage-vue');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = Customer::latest()->paginate(5);

     /*   foreach ($items as $key => $value) {
            $items->vendor_name = getVendorName($this->vendor_name,$value->vendor_id);
            //$items->vendor_name = 'aasa';
        }*/
        $response = [
            'pagination' => [
                'total' => $items->total(),
                'per_page' => $items->perPage(),
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'from' => $items->firstItem(),
                'to' => $items->lastItem()
            ],
            'data' => $items
        ];

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = $request->all();
        $this->validate($request, [
            'name' => 'required',
            'vendor_id' => 'required',
        ]);

        $data = array_except($inputs,array('_token','vendor_id'));
        $data['vendor_id'] = implode(',', $inputs['vendor_id']); 

        $create = Customer::create($data);

        return response()->json($create);
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
        $inputs = $request->all();
        $this->validate($request, [
            'name' => 'required',
            'vendor_id' => 'required',
        ]);

        $data = array_except($inputs,array('_token','vendor_id'));
        $data['vendor_id'] = implode(',', $inputs['vendor_id']); 

        $edit = Customer::find($id)->update($data);

        return response()->json($edit);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Customer::find($id)->delete();
        return response()->json(['done']);
    }
}
