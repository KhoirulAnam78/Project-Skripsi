<?php

namespace App\Http\Controllers;

use App\Models\MonitoringKegiatan;
use App\Http\Requests\StoreMonitoringKegiatanRequest;
use App\Http\Requests\UpdateMonitoringKegiatanRequest;

class MonitoringKegiatanController extends Controller
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
     * @param  \App\Http\Requests\StoreMonitoringKegiatanRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMonitoringKegiatanRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MonitoringKegiatan  $monitoringKegiatan
     * @return \Illuminate\Http\Response
     */
    public function show(MonitoringKegiatan $monitoringKegiatan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MonitoringKegiatan  $monitoringKegiatan
     * @return \Illuminate\Http\Response
     */
    public function edit(MonitoringKegiatan $monitoringKegiatan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMonitoringKegiatanRequest  $request
     * @param  \App\Models\MonitoringKegiatan  $monitoringKegiatan
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMonitoringKegiatanRequest $request, MonitoringKegiatan $monitoringKegiatan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MonitoringKegiatan  $monitoringKegiatan
     * @return \Illuminate\Http\Response
     */
    public function destroy(MonitoringKegiatan $monitoringKegiatan)
    {
        //
    }
}
