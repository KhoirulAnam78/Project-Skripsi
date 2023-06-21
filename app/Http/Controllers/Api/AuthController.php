<?php

namespace App\Http\Controllers\Api;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\MataPelajaran;
use App\Models\TahunAkademik;
use App\Http\Controllers\Controller;
use App\Models\JadwalGuruPiket;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public $kelasAktif = [];
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('username', 'password'))) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
        $user = User::where('username', $request->username)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;
        // $data = PersonalAccessToken::findToken($token)->tokenable();
        if ($user->role === 'admin') {
            return response()->json([
                'message' => 'Login success',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'role' => auth('sanctum')->user()->role,
            ]);
        } else if ($user->role === 'guru' or $user->role === 'pimpinan') {
            //Get Kelas AKtif Saat ini
            $data = TahunAkademik::select('id')->where('status', 'aktif')->first()->kelas->all();
            foreach ($data as $d) {
                array_push($this->kelasAktif, $d->id);
            }

            //Ambil Data Mata Pelajaran Yang Diampu Guru
            $dataUser = Guru::where('user_id', auth('sanctum')->user()->id)->select('id', 'nama')->with(['jadwalPelajarans' => function ($query) {
                $query->select('id', 'guru_id', 'mata_pelajaran_id')->whereIn('kelas_id', $this->kelasAktif)->with(['mataPelajaran' => function ($query) {
                    $query->select('id', 'nama');
                }]);
            }])->first();
            $dataMapel = $dataUser->jadwalPelajarans->groupBy('mata_pelajaran_id');
            $mapel = [];
            foreach ($dataMapel as $key => $dm) {
                $mataPelajaran = MataPelajaran::select('nama')->where('id', $key)->first();
                array_push($mapel, ['nama' => $mataPelajaran->nama]);
            }

            //Get Nama Guru
            $user = ['nama' => $dataUser->nama];

            //Ambil Jadwal Piket
            if (JadwalGuruPiket::where('guru_id', $dataUser->id)->first()) {
                $jadwalPiket = JadwalGuruPiket::where('guru_id', $dataUser->id)->first();
            } else {
                $jadwalPiket = '-';
            }

            return response()->json([
                'message' => 'Login success',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'role' => auth('sanctum')->user()->role,
                'pimpinan' => auth('sanctum')->user()->guru->pimpinan,
                'user' => $user,
                'username' => auth('sanctum')->user()->username,
                'mapel' => $mapel,
                'jadwalPiket' => $jadwalPiket
            ]);
        } else if ($user->role === 'wali_asrama') {
            if ($user->waliAsrama->angkatans->where('status', 'belum lulus')->first()) {
                $angkatan = $user->waliAsrama->angkatans->where('status', 'belum lulus')->first();
            } else {
                $angkatan = null;
            }
            return response()->json([
                'message' => 'Login success',
                'access_token' => $token,
                'username' => auth('sanctum')->user()->username,
                'token_type' => 'Bearer',
                'role' => auth('sanctum')->user()->role,
                'nama' => $user->waliAsrama->nama,
                'angkatan_id' => $angkatan->id,
                'angkatan' => $angkatan->nama
            ]);
        } else {
            $kelas = '';
            $akademik = TahunAkademik::where('status', 'aktif')->first()->id;
            if ($user->siswa->kelas->where('tahun_akademik_id', $akademik)->first()) {
                $kelas = $user->siswa->kelas->where('tahun_akademik_id', $akademik)->first()->nama;
            }
            return response()->json([
                'message' => 'Login success',
                'access_token' => $token,
                'username' => auth('sanctum')->user()->username,
                'token_type' => 'Bearer',
                'role' => auth('sanctum')->user()->role,
                'nama' => $user->siswa->nama,
                'kelas' => $kelas
            ]);
        }
    }

    public function changePassword(Request $request)
    {
        if ($request->username && $request->password) {
            User::where('id', auth('sanctum')->user()->id)->update([
                'username' => $request->username,
                'password' => bcrypt($request->password)
            ]);
            return response()->json([
                'message' => 'Username dan password berhasil diubah',
                'username' => $request->username,
                'password' => $request->password
            ]);
        } else {
            return response()->json([
                'message' => 'Username dan password wajib diisi'
            ]);
        }
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logout Success'
        ]);
    }
}
