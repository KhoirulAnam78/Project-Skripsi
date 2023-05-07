<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Angkatan;
use App\Models\Kegiatan;
use Livewire\WithPagination;
use App\Models\TahunAkademik;
use Livewire\WithFileUploads;
use App\Models\JadwalKegiatan;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\JadwalKegiatanExport;
use App\Imports\JadwalKegiatanImport;

class TabelJadwalKegiatan extends Component
{
    use WithFileUploads;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $file;
    public $tahun_akademik_id;

    public $filterAngkatan = '';
    public $hari, $waktu_mulai, $waktu_berakhir, $angkatan_id, $kegiatan_id;
    public $jadwal_edit_id, $jadwal_delete_id;

    public function mount()
    {
        $this->tahun_akademik_id = TahunAkademik::where('status', 'aktif')->first()->id;
        $this->filterAngkatan = Angkatan::where('status', 'belum lulus')->first()->id;
    }

    public function rules()
    {
        if ($this->jadwal_edit_id) {
            return [
                'kegiatan_id' => 'required',
                'angkatan_id' => 'required',
                'waktu_mulai' => 'required|date_format:H:i',
                'waktu_berakhir' => 'required|date_format:H:i|after:waktu_mulai',
                'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Setiap Hari|unique:jadwal_kegiatans,hari,' . $this->jadwal_edit_id . ',id,angkatan_id,' . $this->angkatan_id . ',kegiatan_id,' . $this->kegiatan_id
            ];
        } else {
            return [
                'kegiatan_id' => 'required',
                'angkatan_id' => 'required',
                'waktu_mulai' => 'required|date_format:H:i',
                'waktu_berakhir' => 'required|date_format:H:i|after:waktu_mulai',
                'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Setiap Hari|unique:jadwal_kegiatans,hari,NULL,id,angkatan_id,' . $this->angkatan_id . ',kegiatan_id,' . $this->kegiatan_id . ',tahun_akademik_id,' . $this->tahun_akademik_id
            ];
        }
    }

    //Custom Errror messages for validation
    protected $messages = [
        'kegiatan_id.required' => 'Field kegiatan wajib diisi !',
        'angkatan_id.required' => 'Field angkatan wajib diisi !',
        'waktu_mulai.required' => 'Waktu mulai wajib diisi !',
        'waktu_mulai.date_format' => 'Waktu mulai hanya diperbolehkan format waktu !',
        'waktu_berakhir.required' => 'Waktu berakhir wajib diisi !',
        'waktu_berakhir.date_format' => 'Waktu berakhir hanya diperbolehkan format waktu !',
        'waktu_berakhir.after' => 'Waktu berakhir harus lebih besar dari waktu mulai !',
        'hari.required' => 'Hari wajib diisi !',
        'hari.unique' => 'Telah ada jadwal pada hari, kegiatan dan angkatan yang dipilih !',
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
        $this->angkatan_id = null;
        $this->kegiatan_id = null;
        $this->waktu_mulai = null;
        $this->waktu_berakhir = null;
        $this->hari = null;
        $this->resetErrorBag();
        $this->resetValidation();
    }


    public function save()
    {
        $this->validate();
        JadwalKegiatan::create([
            'hari' => $this->hari,
            'angkatan_id' => $this->angkatan_id,
            'waktu_mulai' => $this->waktu_mulai,
            'waktu_berakhir' => $this->waktu_berakhir,
            'kegiatan_id' => $this->kegiatan_id,
            'tahun_akademik_id' => $this->tahun_akademik_id
        ]);
        session()->flash('message', 'Data berhasil ditambahkan !');
        $this->empty();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function edit($id)
    {
        $jadwal = JadwalKegiatan::find($id);
        $this->angkatan_id = $jadwal->angkatan_id;
        // dd($this->tahun_akademik_id);
        $this->hari = $jadwal->hari;
        $this->kegiatan_id = $jadwal->kegiatan_id;
        $this->waktu_mulai = substr($jadwal->waktu_mulai, 0, -3);
        $this->waktu_berakhir = substr($jadwal->waktu_berakhir, 0, -3);
        $this->jadwal_edit_id = $id;
        $this->dispatchBrowserEvent('show-edit-modal');
    }

    public function update()
    {
        $this->validate();
        JadwalKegiatan::where('id', $this->jadwal_edit_id)->update([
            'hari' => $this->hari,
            'kegiatan_id' => $this->kegiatan_id,
            'waktu_mulai' => $this->waktu_mulai,
            'waktu_berakhir' => $this->waktu_berakhir,
            'angkatan_id' => $this->angkatan_id,
        ]);
        session()->flash('message', 'Data berhasil diedit !');
        $this->empty();
        $this->dispatchBrowserEvent('close-edit-modal');
    }


    public function deleteConfirmation($id)
    {
        $this->jadwal_delete_id = $id; //tahun_akademik id

        $this->dispatchBrowserEvent('show-delete-confirmation-modal');
    }

    //Delete data
    public function deleteJadwalData()
    {
        $jadwal = JadwalKegiatan::where('id', $this->jadwal_delete_id)->first();
        try {
            $jadwal->delete();
            session()->flash('message', 'Data berhasil dihapus');
        } catch (\Throwable $th) {
            session()->flash('error', 'Data gagal dihapus karena digunakan di dalam sistem');
        }

        $this->dispatchBrowserEvent('close-modal-delete');

        $this->jadwal_delete_id = '';
    }


    public function updatingfilterAngkatan()
    {
        $this->resetPage();
    }


    public function updatingFilterHari()
    {
        $this->resetPage();
    }

    public function updatedFile()
    {
        $this->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);
    }

    public function import()
    {
        $this->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new JadwalKegiatanImport(), $this->file);
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
        return Excel::download(new JadwalKegiatanExport($this->tahun_akademik_id, $this->filterAngkatan), 'Jadwal Kegiatan SMAN Titian Teras' . '.xlsx');
    }

    public function render()
    {
        $statusTahunAkademik = TahunAkademik::find($this->tahun_akademik_id)->status;
        if ($statusTahunAkademik === 'aktif') {
            $allow = true;
            $angkatan = Angkatan::where('status', 'belum lulus')->get()->all();
        } else {
            $allow = false;
            $angkatan = Angkatan::get()->all();
        }
        return view('livewire.tabel-jadwal-kegiatan', [
            'jadwalKegiatan' => JadwalKegiatan::where('angkatan_id', 'like', '%' . $this->filterAngkatan . '%')->where('tahun_akademik_id', $this->tahun_akademik_id)->latest()->paginate(5),
            'allow' => $allow,
            'angkatan' => $angkatan,
            'kegiatan' => Kegiatan::all(),
            'tahunAkademik' => TahunAkademik::all()
        ]);
    }
}
