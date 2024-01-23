<?php

namespace App\Http\Livewire;

use App\Models\Guru;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Carbon;
use App\Models\JadwalGuruPiket;
use App\Exports\ExportJadwalPiket;
use App\Imports\ImportJadwalPiket;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class TabelJadwalPiket extends Component
{

    use WithFileUploads;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $file;
    public $jadwal_edit_id, $jadwal_delete_id;
    public $guru_id, $hari, $waktu_mulai, $waktu_berakhir;

    public function rules()
    {
        if ($this->jadwal_edit_id) {
            return [
                'guru_id' => 'required',
                'waktu_mulai' => 'required|date_format:H:i|unique:jadwal_guru_pikets,waktu_mulai,' . $this->jadwal_edit_id . ',id,guru_id,' . $this->guru_id,
                'waktu_berakhir' => 'required|date_format:H:i|after:waktu_mulai',
                // 'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu'
                // |unique:jadwal_guru_pikets,hari,' . $this->jadwal_edit_id . ',id,guru_id,' . $this->guru_id
            ];
        } else {
            return [
                'file' => 'required|mimes:xlsx,xls',
                'guru_id' => 'required',
                'waktu_mulai' => 'required|date_format:H:i|unique:jadwal_guru_pikets,hari,NULL,id,guru_id,' . $this->guru_id,
                // |unique:jadwal_guru_pikets,waktu_mulai,NULL,id,hari,' . $this->hari,
                'waktu_berakhir' => 'required|date_format:H:i|after:waktu_mulai',
                // 'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu'
                // |unique:jadwal_guru_pikets,hari,NULL,id,guru_id,' . $this->guru_id
            ];
        }
    }

    //Custom Errror messages for validation
    protected $messages = [
        'guru_id.required' => 'Field guru wajib diisi !',
        'waktu_mulai.required' => 'Waktu mulai wajib diisi !',
        'waktu_mulai.date_format' => 'Waktu mulai hanya diperbolehkan format waktu !',
        'waktu_mulai.unique' => 'Jadwal piket pada waktu ini sudah ada !',
        'waktu_berakhir.required' => 'Waktu berakhir wajib diisi !',
        'waktu_berakhir.date_format' => 'Waktu berakhir hanya diperbolehkan format waktu !',
        'waktu_berakhir.after' => 'Waktu berakhir harus lebih besar dari waktu mulai !',
        'hari.required' => 'Hari wajib diisi !',
        'hari.unique' => 'Guru telah piket pada hari yang dipilih',
        'file.required' => 'File tidak boleh kosong',
        'file.mimes' => 'File harus memiliki format excel(.xlxs/.xls)'
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    //Mengosongkan inputan pada modal
    public function empty()
    {
        $this->file = null;
        $this->guru_id = null;
        $this->waktu_mulai = null;
        $this->waktu_berakhir = null;
        $this->hari = null;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate([
            'guru_id' => 'required',
            'waktu_mulai' => 'required|date_format:H:i|unique:jadwal_guru_pikets,hari,NULL,id,guru_id,' . $this->guru_id,
            // unique:jadwal_guru_pikets,waktu_mulai,NULL,id,hari,' . $this->hari,
            'waktu_berakhir' => 'required|date_format:H:i|after:waktu_mulai',
            // 'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu|unique:jadwal_guru_pikets,hari,NULL,id,guru_id,' . $this->guru_id
        ]);
        JadwalGuruPiket::create([
            'hari' => 'Setiap Hari',
            'guru_id' => $this->guru_id,
            'waktu_mulai' => $this->waktu_mulai,
            'waktu_berakhir' => $this->waktu_berakhir,
        ]);
        session()->flash('message', 'Data berhasil ditambahkan !');
        $this->empty();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function edit($id)
    {
        $jadwal = JadwalGuruPiket::find($id);
        $this->guru_id = $jadwal->guru_id;
        // dd($this->tahun_akademik_id);
        $this->hari = $jadwal->hari;
        $this->waktu_mulai = substr($jadwal->waktu_mulai, 0, -3);
        $this->waktu_berakhir = substr($jadwal->waktu_berakhir, 0, -3);
        $this->jadwal_edit_id = $id;
        $this->dispatchBrowserEvent('show-edit-modal');
    }

    //Update data
    public function update()
    {
        $this->validate();
        JadwalGuruPiket::where('id', $this->jadwal_edit_id)->update([
            'hari' => 'Setiap Hari',
            'waktu_mulai' => $this->waktu_mulai,
            'waktu_berakhir' => $this->waktu_berakhir,
            'guru_id' => $this->guru_id,
        ]);
        session()->flash('message', 'Data berhasil diedit !');
        $this->empty();
        $this->dispatchBrowserEvent('close-edit-modal');
    }

    //Show modal delete confirmation
    public function deleteConfirmation($id)
    {
        $this->jadwal_delete_id = $id; //tahun_akademik id

        $this->dispatchBrowserEvent('show-delete-confirmation-modal');
    }

    //Delete data
    public function deleteJadwalData()
    {
        $jadwal = JadwalGuruPiket::where('id', $this->jadwal_delete_id)->first();
        try {
            $jadwal->delete();
            session()->flash('message', 'Data berhasil dihapus');
        } catch (\Throwable $th) {
            session()->flash('error', 'Data gagal dihapus karena digunakan di dalam sistem');
        }

        $this->dispatchBrowserEvent('close-modal-delete');

        $this->jadwal_delete_id = '';
    }

    public function import()
    {
        $this->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new ImportJadwalPiket, $this->file);
            session()->flash('message', 'Data berhasil diimport');
            $this->file = '';
            $this->dispatchBrowserEvent('close-modal-import');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            session()->flash('importError', $failures);
            $this->file = '';
            $this->dispatchBrowserEvent('close-modal-import');
        }
    }

    public function export()
    {
        return Excel::download(new ExportJadwalPiket, 'Jadwal Piket Guru SMAN Titian Teras.xlsx');
    }

    public function render()
    {
        // dd(Carbon::now()->isoFormat('dddd, D MMMM Y'));
        return view('livewire.tabel-jadwal-piket', [
            'jadwalPiket' => DB::table('jadwal_guru_pikets as a')
            ->join('gurus as b', 'b.id','a.guru_id')
            ->where('b.nama', 'like', '%' . $this->search . '%')
            ->select('a.waktu_mulai','a.hari','a.waktu_berakhir','b.nama','b.kode_guru','a.id')
            ->paginate(10),
            'guru' => Guru::all()
        ]);
    }
}