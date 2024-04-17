<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Tipebisnis;
use App\Models\User;
use App\Models\Toko;
use Illuminate\Support\Facades\Validator;
use App\Traits\MasterTrait;
use Illuminate\Support\Facades\DB;

use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid as Generator;

class RegisterController extends Controller
{
    use MasterTrait;

    public function register()
    {
        $tipe = Tipebisnis::all();
        return view('auth.register', compact('tipe'));
    }

    public function doregister(Request $request)
    {
        $rules = [
            'nama' => 'required',
            'nohp' => 'required',
            'email' => 'required',
            'namatoko' => 'required',
            'tipebisnis' => 'required',
            'alamat' => 'required',
            'username' => 'required',
            'password' => 'required'
        ];

        $messages = [
            'nama.required' => 'Nama Lengkap wajib diisi',
            'nohp.required' => 'No HP wajib diisi',
            'email.required' => 'Email wajib diisi',
            'namatoko.required' => 'Nama Toko wajib diisi',
            'tipebisnis.required' => 'Tipe Bisnis wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
            'username.required' => 'Username wajib diisi',
            'password.required' => 'Password wajib diisi'
        ];
        $validasi = Validator::make($request->all(), $rules, $messages);

        if($validasi->fails()){
            return response()->json(
                ['error' => $validasi->errors()->all()
            ], 400);
        }

        // if ($request->id == '') {
        //     $cek_count = Barang::where('kode_barang', $request->kode_barang)
        //         ->where('kode_barang', $request->kode_barang)
        //         ->select('*')
        //         ->count();
        //     if ($cek_count > 0) {
        //         return response()->json([
        //             'success'   => false,
        //             'message'   => 'Kode Barang sudah ada'
        //         ], 422);
        //         die();
        //     }
        // }

        try {
            DB::transaction(function () use ($request) {
                $noregistrasi = $this->idCreate('users', 'noregistrasi');
                $norectoko = Generator::uuid4()->toString();

                $toko = Toko::create([
                    'norectoko' => $norectoko,
                    'nama_toko' => $request->namatoko,
                    'alamat'    => $request->alamat,
                    'bisnis_id' => $request->tipebisnis
                ]);

                $data = User::create(
                    [
                        'noregistrasi'  => $noregistrasi,
                        'nama'          => $request->nama,
                        'email'         => $request->email,
                        'nohp'          => $request->nohp,
                        'username'      => $request->username,
                        'password'      => bcrypt($request->password),
                        'roles'         => 1,
                        'toko_id'       => $norectoko
                    ]
                );
            });

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil'
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
