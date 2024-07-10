<?php

namespace App\Http\Controllers\Market\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Traits\MasterTrait;
use App\Models\Satuan;
use App\Models\Kategori;
use App\Models\Supplier;

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

        $save = Kategori::updateOrCreate(
            ['id_kategori' => $request->id_kategori],
            [
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

    public function supplier() {
        if (request()->ajax()) {
            $supplier = Supplier::all();

            return DataTables::of($supplier)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  dataId="'.$row->id_supplier.'" data-original-title="Edit" class="edit btn btn-primary btn-sm btn-edit">Edit</a>';

                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  dataId="'. $row->id_supplier .'" data-original-title="Delete" class="btn btn-danger btn-sm btn-delete">Delete</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make();
        }
        return view('market.backend.master.supplierIndex');
    }

    function supplierAdd(Request $request) {
        $rules = [
            'nama_supplier' => 'required'
        ];

        $messages = [
            'nama_supplier.required' => 'Nama Supplier wajib diisi'
        ];
        $validasi = Validator::make($request->all(), $rules, $messages);

        if($validasi->fails()){
            return response()->json(
                ['error' => $validasi->errors()->all()
            ], 400);
        }

        if ($request->nama_supplier == '') {
            $cek_count = Satuan::where('nama_supplier', $request->nama_supplier)
                ->select('*')
                ->count();
            if ($cek_count > 0) {
                return response()->json([
                    'success'   => false,
                    'message'   => 'Nama Supplier sudah ada'
                ], 422);
                die();
            }
        }

        $kode_supplier = $this->idSupplier();

        $table = 'm_supplier';
        $field = 'id_supplier';

        $id_supplier = $this->idCreate($table, $field);
        $save = Supplier::updateOrCreate(
            [
                'id_supplier'           => $request->id_supplier,
            ],
            [
                'id_supplier'           => $id_supplier,
                "toko_id"               => auth()->user()->toko_id,
                'kode_supplier'         => $kode_supplier,
                'nama_supplier'         => $request->nama_supplier,
                'no_hp'                 => $request->no_hp,
                'alamat'                => $request->alamat
            ]
        );

        if($save) {
            $data = Supplier::where('m_supplier.id_supplier', $request->id_supplier)
                ->select('m_supplier.*')
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

    function supplierEdit($id) {
        $supplierEdit = Supplier::select('*')
                        ->where('id_supplier', $id)
                        ->first();

        return response()->json([
            'success'   => true,
            'item'      => $supplierEdit
        ], 200);
    }

    function supplierDelete($id_supplier) {
        $supplier = Supplier::find($id_supplier);
        if($supplier) {
            $supplier->delete();

            if($supplier) {
                $data = Satuan::where('m_supplier.id_supplier', $id_supplier)
                    ->select('m_supplier.*')
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

    public function getKategori(Request $request) {
        $query = DB::table('m_kategori')
            ->select('id_kategori', 'nama_kategori');

        if ($request->has('q') && !empty($request->q)) {
            $cari = $request->q;
            $query = $query->where('nama_kategori', 'LIKE', '%' . $cari . '%');
        }

        $data = $query->limit(10)->get(); // Membatasi hasil agar tidak terlalu banyak

        return response()->json([
            'success' => true,
            'data'    => $data
        ], 200);
    }

    public function getSatuan(Request $request) {
        $query = DB::table('m_satuan')
            ->select('id_satuan', 'aliase_satuan', 'desc_satuan');

        if ($request->has('q') && !empty($request->q)) {
            $cari = $request->q;
            $query = $query->where(function($q) use ($cari) {
                $q->where('aliase_satuan', 'LIKE', '%' . $cari . '%')
                ->orWhere('desc_satuan', 'LIKE', '%' . $cari . '%');
            });
        }

        $data = $query->limit(10)->get(); // Membatasi hasil agar tidak terlalu banyak

        return response()->json([
            'success' => true,
            'data'    => $data
        ], 200);
    }

    public function getMerek(Request $request) {
        $query = DB::table('m_merek')
            ->select('id_merek', 'desc_merek');

        if ($request->has('q') && !empty($request->q)) {
            $cari = $request->q;
            $query = $query->where('desc_merek', 'LIKE', '%' . $cari . '%');
        }

        $data = $query->limit(10)->get(); // Membatasi hasil agar tidak terlalu banyak

        return response()->json([
            'success' => true,
            'data'    => $data
        ], 200);
    }

    public function getSupplier(Request $request) {
        $query = DB::table('m_supplier')
            ->select('id_supplier', 'nama_supplier');

        if ($request->has('q') && !empty($request->q)) {
            $cari = $request->q;
            $query = $query->where('nama_supplier', 'LIKE', '%' . $cari . '%');
        }

        $data = $query->limit(10)->get(); // Membatasi hasil agar tidak terlalu banyak

        return response()->json([
            'success' => true,
            'data'    => $data
        ], 200);
    }

    public function getBarang(Request $request) {
        $query = DB::table('m_barang')
            ->select('id_barang', 'nama_barang');

        if ($request->has('q') && !empty($request->q)) {
            $cari = $request->q;
            $query = $query->where('nama_barang', 'LIKE', '%' . $cari . '%');
        }

        $data = $query->limit(10)->get(); // Membatasi hasil agar tidak terlalu banyak

        return response()->json([
            'success' => true,
            'data'    => $data
        ], 200);
    }
}
