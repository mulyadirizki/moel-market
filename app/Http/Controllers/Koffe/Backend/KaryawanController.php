<?php

namespace App\Http\Controllers\Koffe\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Models\User;
use App\Models\Role;
use App\Traits\MasterTrait;
use Illuminate\Support\Facades\Validator;

class KaryawanController extends Controller
{
    use MasterTrait;

    public function karyawan()
    {
        $role = Role::where('id', '<>', '1')->get();
        if (request()->ajax()) {
            $karyawan = User::select('users.*')
                ->where('toko_id', auth()->user()->toko_id)
                ->where('roles', 2)
                ->get();

            return DataTables::of($karyawan)
                ->addIndexColumn()
                ->editColumn('mroles', function($row) {
                    if ($row->roles === 1) {
                        return '<span>Admin</span>';
                    } else if ($row->roles === 2) {
                        return '<span>Kasir</span>';
                    }
                })
                ->addColumn('action', function($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  dataId="'.$row->noregistrasi.'" data-original-title="Edit" class="edit btn btn-primary btn-sm btn-edit">Edit</a>';

                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  dataId="'. $row->noregistrasi .'" data-original-title="Delete" class="btn btn-danger btn-sm btn-delete">Delete</a>';

                    return $btn;
                })
                ->rawColumns(['action', 'mroles'])
                ->make();
        }
        return view('koffe.backend.karyawan.karyawan', compact('role'));
    }

    public function karyawanAdd(Request $request)
    {
        $rules = [
            'nama' => 'required',
            'nohp' => 'required',
            'email' => 'required',
            'username' => 'required',
            'password' => 'required',
            'roles' => 'required'
        ];

        $messages = [
            'nama.required' => 'Nama Lengkap wajib diisi',
            'nohp.required' => 'No HP wajib diisi',
            'email.required' => 'Email wajib diisi',
            'username.required' => 'Username wajib diisi',
            'password.required' => 'Password wajib diisi',
            'roles.required' => 'Roles wajib diisi'
        ];
        $validasi = Validator::make($request->all(), $rules, $messages);

        if($validasi->fails()){
            return response()->json(
                ['error' => $validasi->errors()->all()
            ], 400);
        }

        $username_exists = User::where('username', $request->username)->exists();
        $email_exists = User::where('email', $request->email)->exists();

        if ($username_exists) {
            return response()->json([
                'success'   => false,
                'message'   => 'Username telah ada'
            ], 422);
        } elseif ($email_exists) {
            return response()->json([
                'success'   => false,
                'message'   => 'Email telah ada'
            ], 422);
        }

        $noregistrasi = $this->idCreate('users', 'noregistrasi');
        $data = User::create(
            [
                'noregistrasi'  => $noregistrasi,
                'nama'          => $request->nama,
                'email'         => $request->email,
                'nohp'          => $request->nohp,
                'username'      => $request->username,
                'password'      => bcrypt($request->password),
                'roles'         => $request->roles,
                'toko_id'       => auth()->user()->toko_id
            ]
        );

        if($data) {
            return response()->json([
                'success' => true,
                'message' => 'Tambah Karyawan Berhasil'
            ], 200);
        } else {
            return response()->json([
                'success'   => true,
                'message'   => 'Gagal Tambah Data'
            ], 400);
        }
    }
}
