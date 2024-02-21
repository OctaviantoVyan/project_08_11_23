<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class BookControllerr extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $model;
    public function index()
    {
                $items= $this->model->with('authors','publisher')->get();
        return response(['data'=>$items,'status'=>200]);}
    public function create()
    {    }
    public function store(Request $request)
    {    // var (['satu','dua','tiga'])
            $request->validate([
            'isbn'=>'required|digits:3|integer|unique:books,isbn',
            'name'=>'required|min:3',
            'year'=>'required|integer|digits:4',
            'page'=>'required|integer',
            'publisher_id'=>'exists:Publisher,id',
            'Author'=>'array',
            'Author.*'=>'exists:authors,id'
            ]);
                $item= $this->model->create($request->all());
                $authors=$request->get('authors');
                $item->authors()->synsc($authors);
            return $this->index();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $item =$this->model ->with('authors','publisher')->findOrFail('id');
            // $item->update($request->all());
        return response(['data'=>$item,'status'=>200]);
            }
            catch (ModelNotFoundException $e){
        return response(['message'=>'item not found','status'=>404]);

            }
        //
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
        try {
            $item = $this->model->with('authors', 'publisher')->findOrFail($id);
            $item->update($request->all());

            $authors = $request->get('authors');
            $item->authors()->sync($authors);

            return response(['data' => $item, 'status' => 200]);
        } catch (ModelNotFoundException $e) {
            return response(['message' => 'Item Not Found!', 'status' => 404]);
        }
    }
    public function destroy(string $id)
    {
         //
        try {
            $item = $this->model->with('authors','publisher')->findOrFail($id);
            $item->authors()->detach();
            $item->delete();
            return $this->index();
        } catch (ModelNotFoundException $e) {
            return response(['message' => 'Item Not Found!', 'status' => 404]);
        }
    }
    }

