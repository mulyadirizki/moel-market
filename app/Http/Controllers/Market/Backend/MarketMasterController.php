<?php

namespace App\Http\Controllers\Market\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use DataTables;
use App\Traits\MasterTrait;
use App\Models\Satuan;
use App\Models\Kategori;

class MarketMasterController extends Controller
{
    use MasterTrait;

    public function satuan() {
        if (request()->ajax()) {
            $satuan = Satuan::all();

            return DataTables::of($satuan)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  dataId="'.$row->id_satuan.'" data-original-title="Edit" class="edit btn btn-primary btn-sm btn-edit">Edit</a>';

                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  dataId="'. $row->id_satuan .'" data-original-title="Delete" class="btn btn-danger btn-sm btn-delete">Delete</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make();
        }
        return view('market.backend.master.satuanIndex');
    }

    public function satuanAdd(Request $request) {
        $rules = [
            'desc_satuan' => 'required'
        ];

        $messages = [
            'desc_satuan.required' => 'Nama satuan wajib diisi'
        ];
        $validasi = Validator::make($request->all(), $rules, $messages);

        if($validasi->fails()){
            return response()->json(
                ['error' => $validasi->errors()->all()
            ], 400);
        }

        if ($request->id_satuan == '') {
            $cek_count = Satuan::where('desc_satuan', $request->desc_satuan)
                ->select('*')
                ->count();
            if ($cek_count > 0) {
                return response()->json([
                    'success'   => false,
                    'message'   => 'Satuan Barang sudah ada'
                ], 422);
                die();
            }
        }

        $save = Satuan::updateOrCreate(
            [
                'id_satuan'         => $request->id_satuan,
            ],
            [
                'desc_satuan'       => $request->desc_satuan,
                'aliase_satuan'     => $request->aliase_satuan
            ]
        );

        if($save) {
            $data = Satuan::where('m_satuan.id_satuan', $request->id_satuan)
                ->select('m_satuan.*')
                ->first();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
                'data'    => $data
            ], 200);
        } else {
            return response()->json([
                'success'   => false,
                'message'   => 'Data gagal disimpan'
            ], 422);
        }
    }

    public function satuanEdit($id) {
        $satuanEdit = Satuan::select('*')
                        ->where('id_satuan', $id)
                        ->first();

        return response()->json([
            'success'   => true,
            'item'      => $satuanEdit
        ], 200);
    }

    public function satuanDelete($id_satuan) {
        $satuan = Satuan::find($id_satuan);
        if($satuan) {
            $satuan->delete();

            if($satuan) {
                $data = Satuan::where('m_satuan.id_satuan', $id_satuan)
                    ->select('m_satuan.*')
                    ->first();
                return response()->json([
                    'success' => true,
                    'message' => 'Data berhasil dihapus',
                    'data'    => $data
                ], 200);
            }
            return response()->json([
                'success'   => false,
                'message'   => 'Data gagal dihapus'
            ], 422);
        }else {
            return redirect()->route('satuan')->with(['error' => 'Data Gagal Dihapus!']);
        }
    }

    public function kategori() {
        if (request()->ajax()) {
            $kategori = Kategori::all();

            return DataTables::of($kategori)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  dataId="'.$row->id_kategori.'" data-original-title="Edit" class="edit btn btn-primary btn-sm btn-edit">Edit</a>';

                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  dataId="'. $row->id_kategori .'" data-original-title="Delete" class="btn btn-danger btn-sm btn-delete">Delete</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make();
        }
        return view('market.backend.master.kategoriIndex');
    }

    public function kategoriAdd(Request $request) {
        $rules = [
            'nama_kategori' => 'required'
        ];

        $messages = [
            'nama_kategori.required' => 'Nama satuan wajib diisi'
        ];
        $validasi = Validator::make($request->all(), $rules, $messages);

        if($validasi->fails()){
            return response()->json(
                ['error' => $validasi->errors()->all()
            ], 400);
        }

        if ($request->id_satuan == '') {
            $cek_count = Kategori::where('nama_kategori', $request->nama_kategori)
                ->select('*')
                ->count();
            if ($cek_count > 0) {
                return response()->json([
                    'success'   => false,
                    'message'   => 'Kategori Barang sudah ada'
                ], 422);
                die();
            }
        }

        $table = 'm_kategori';
        $field = 'id_kategori';

        $id_kategori = $this->idCreate($table, $field);
        $save = Kategori::updateOrCreate(
            ['id_kategori' => $request->id_kategori],
            [
                'id_kategori' => $id_kategori,
                'nama_kategori' => $request->nama_kategori,
            ]
        );

        if($save) {
            $data = Kategori::where('m_kategori.id_kategori', $request->id_kategori)
                ->select('m_kategori.*')
                ->first();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
                'data'    => $data
            ], 200);
        } else {
            return response()->json([
                'success'   => false,
                'message'   => 'Data gagal disimpan'
            ], 422);
        }
    }

    public function kategoriEdit($id) {
        $kategoriEdit = Kategori::select('*')
                        ->where('id_kategori', $id)
                        ->first();

        return response()->json([
            'success'   => true,
            'item'      => $kategoriEdit
        ], 200);
    }

    public function kategoriDelete($id_kategori) {
        $kategori = Kategori::find($id_kategori);
        if($kategori) {
            $kategori->delete();

            if($kategori) {
                $data = Kategori::where('m_kategori.id_kategori', $id_kategori)
                    ->select('m_kategori.*')
                    ->first();
                return response()->json([
                    'success' => true,
                    'message' => 'Data berhasil dihapus',
                    'data'    => $data
                ], 200);
            }
            return response()->json([
                'success'   => false,
                'message'   => 'Data gagal dihapus'
            ], 422);
        }else {
            return redirect()->route('satuan')->with(['error' => 'Data Gagal Dihapus!']);
        }
    }
}
