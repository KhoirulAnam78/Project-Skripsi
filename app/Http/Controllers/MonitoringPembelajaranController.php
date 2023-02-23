<?php

namespace App\Http\Controllers;

use App\Models\MonitoringPembelajaran;
use App\Http\Requests\StoreMonitoringPembelajaranRequest;
use App\Http\Requests\UpdateMonitoringPembelajaranRequest;
use Carbon\Carbon;
use Illuminate\Support\Carbon as SupportCarbon;

class MonitoringPembelajaranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.presensi_pembelajaran', [
            'title' => 'Presensi Pembelajaran',
        ]);
    }

    public function daftarPertemuan()
    {
        return view('pages.daftar_pertemuan', [
            'title' => 'Daftar Pertemuan'
        ]);
    }

    public function validasi()
    {
        return view('pages.validasi_pembelajaran', [
            'title' => 'Validasi Pembelajaran'
        ]);
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
     * @param  \App\Http\Requests\StoreMonitoringPembelajaranRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMonitoringPembelajaranRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MonitoringPembelajaran  $monitoringPembelajaran
     * @return \Illuminate\Http\Response
     */
    public function show(MonitoringPembelajaran $monitoringPembelajaran)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MonitoringPembelajaran  $monitoringPembelajaran
     * @return \Illuminate\Http\Response
     */
    public function edit(MonitoringPembelajaran $monitoringPembelajaran)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMonitoringPembelajaranRequest  $request
     * @param  \App\Models\MonitoringPembelajaran  $monitoringPembelajaran
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMonitoringPembelajaranRequest $request, MonitoringPembelajaran $monitoringPembelajaran)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MonitoringPembelajaran  $monitoringPembelajaran
     * @return \Illuminate\Http\Response
     */
    public function destroy(MonitoringPembelajaran $monitoringPembelajaran)
    {
        //
    }
}
