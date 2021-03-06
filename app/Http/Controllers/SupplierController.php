<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Supplier;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Transformers\SupplierTransformer;

class SupplierController extends RestController
{
    protected $transformer = SupplierTransformer::class;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suppliers = Supplier::all();
        $response = $this->generateCollection($suppliers);
        return $this->sendResponse($response);
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
        $this->validate($request,[
            'supplier_name' => 'required',
            'supplier_address' => 'required',
            'supplier_phone_number' => 'required',
        ]);   

        try {

                $supplier = new Supplier;

                $supplier->supplier_name=$request->get('supplier_name');
                $supplier->supplier_address=$request->get('supplier_address');
                $supplier->supplier_phone_number=$request->get('supplier_phone_number');
                $supplier->save();

                $response = $this->generateItem($supplier);

                return $this->sendResponse($response, 201);

        } catch (\Exception $e) {
            return $this->sendIseResponse($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $supplier=Supplier::find($id);
            $response = $this->generateItem($supplier);
            return $this->sendResponse($response);
        } catch (ModelNotFoundException $e) {
            return $this->sendNotFoundResponse('supplier_not_found');
        } catch (\Exception $e) {
            return $this->sendIseResponse($e->getMessage());
        }
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
        $this->validate($request,[
            'supplier_name' => 'required',
            'supplier_address' => 'required',
            'supplier_phone_number' => 'required',
        ]); 
        
        try {

            $supplier=Supplier::find($id);

            $supplier->supplier_name=$request->get('supplier_name');
            $supplier->supplier_address=$request->get('supplier_address');
            $supplier->supplier_phone_number=$request->get('supplier_phone_number');
            $supplier->save();

            $response = $this->generateItem($supplier);

            return $this->sendResponse($response, 201);

        }catch (ModelNotFoundException $e) {
            return $this->sendNotFoundResponse('supplier_not_found');
        }
        catch (\Exception $e) {
            return $this->sendIseResponse($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $supplier=Supplier::find($id);
            $supplier->delete();
            return response()->json('Success',200);
        } catch (ModelNotFoundException $e) {
            return $this->sendNotFoundResponse('supplier_not_found');
        } catch (\Exception $e) {
            return $this->sendIseResponse($e->getMessage());
        }
    }
}
