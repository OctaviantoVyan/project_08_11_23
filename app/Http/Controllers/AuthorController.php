<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class AuthorControllerr extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $model;
    public function index()
    {
        //
        $items= $this->model->with('books')->get();
        return response(['data'=>$items,'status'=>200]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'identifier'=>'required|unique,authors|min:3',
            'fname'=>'required',
            'lname'=>'required',
        ]);
                $this->model->create($request->all());
                return $this->index();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        try {
            $item =$this->model ->with('books')->findOrFail('id');
            // $item->update($request->all());
        return response(['data'=>$item,'status'=>200]);
            }
            catch (ModelNotFoundException $e){
        return response(['message'=>'item not found','status'=>404]);

            }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $item = $this->model->with('books')->findOrFail($id);
            $item->books()->detach();
            $item->delete();
            return $this->index();
        } catch (ModelNotFoundException $e) {
            return response(['message' => 'Item Not Found!', 'status' => 404]);
        }
    }
}
