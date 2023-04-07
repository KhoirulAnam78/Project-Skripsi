<?php

namespace App\Http\Controllers;

use App\Models\Narasumber;
use App\Http\Requests\StoreNarasumberRequest;
use App\Http\Requests\UpdateNarasumberRequest;

class NarasumberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StoreNarasumberRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreNarasumberRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Narasumber  $narasumber
     * @return \Illuminate\Http\Response
     */
    public function show(Narasumber $narasumber)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Narasumber  $narasumber
     * @return \Illuminate\Http\Response
     */
    public function edit(Narasumber $narasumber)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateNarasumberRequest  $request
     * @param  \App\Models\Narasumber  $narasumber
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateNarasumberRequest $request, Narasumber $narasumber)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Narasumber  $narasumber
     * @return \Illuminate\Http\Response
     */
    public function destroy(Narasumber $narasumber)
    {
        //
    }
}
