<?php

namespace App\Http\Controllers\Api;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\MataPelajaran;
use App\Models\TahunAkademik;
use App\Http\Controllers\Controller;
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
        } else if ($user->role === 'guru') {
            $data = TahunAkademik::select('id')->where('status', 'aktif')->first()->kelas->all();
            foreach ($data as $d) {
                array_push($this->kelasAktif, $d->id);
            }
            $dataUser = Guru::where('user_id', auth('sanctum')->user()->id)->select('id', 'nama')->with(['jadwalPelajarans' => function ($query) {
                $query->select('id', 'guru_id', 'mata_pelajaran_id')->whereIn('kelas_id', $this->kelasAktif)->with(['mataPelajaran' => function ($query) {
                    $query->select('id', 'nama');
                }]);
            }])->first();
            $user = ['nama' => $dataUser->nama];
            $mapel = [];
            $dataMapel = $dataUser->jadwalPelajarans->groupBy('mata_pelajaran_id');
            foreach ($dataMapel as $key => $dm) {
                $mataPelajaran = MataPelajaran::select('nama')->where('id', $key)->first();
                array_push($mapel, $mataPelajaran->nama);
            }
            return response()->json([
                'message' => 'Login success',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'role' => auth('sanctum')->user()->role,
                'user' => $user,
                'mapel' => $mapel
            ]);
        } else {
            $dataUser = $user->siswa;
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logout Success'
        ]);
    }

    public function getUser()
    {
        $data = User::all();
        return response()->json([
            'data' => $data
        ]);
    }
}
