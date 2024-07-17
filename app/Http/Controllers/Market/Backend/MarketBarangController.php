<?php

namespace App\Http\Controllers\Market\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Traits\MasterTrait;
use App\Models\Barang;

class MarketBarangController extends Controller
{
    use MasterTrait;

    public function barang() {
        if (request()->ajax()) {
            $barang = DB::table('m_barang')
                ->join('m_kategori', 'm_barang.id_kategori', '=', 'm_kategori.id_kategori')
                ->join('m_satuan', 'm_barang.id_satuan', '=', 'm_satuan.id_satuan')
                ->join('m_merek', 'm_barang.id_merek', '=', 'm_merek.id_merek')
                ->select(
                    'm_barang.id_barang',
                    'm_barang.statusenabled',
                    'm_barang.kode_barcode',
                    'm_barang.nama_barang',
                    'm_kategori.nama_kategori',
                    'm_satuan.desc_satuan',
                    'm_merek.desc_merek',
                    'm_barang.stok_min',
                    'm_barang.stok_max',
                    'm_barang.harga_pokok',
                    'm_barang.harga_jual',
                    'm_barang.margin',
                    'm_barang.harga_jual_default'
                )
                ->where('m_barang.toko_id', auth()->user()->toko_id)
                ->where('m_barang.statusenabled', '1')
                ->orderBy('m_barang.nama_barang', 'asc')
                ->get();

            return DataTables::of($barang)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  dataId="'.$row->id_barang.'" data-original-title="Edit" class="edit btn btn-primary btn-sm btn-edit">Edit</a>';

                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  dataId="'. $row->id_barang .'" data-original-title="Delete" class="btn btn-danger btn-sm btn-delete">Delete</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make();
        }
        return view('market.backend.barang.list-barang');
    }

    public function barangAdd(Request $request) {
        // $rules = [
        //     'nama_barang' => 'required',
        //     'satuan' => 'required',
        //     'kategori' => 'required',
        //     'harga_pokok' => 'required',
        //     'harga_jual' => 'required',
        //     'harga_jual_default' => 'required'
        // ];

        // $messages = [
        //     'nama_barang.required' => 'Nama Barang wajib diisi',
        //     'satuan.required' => 'Satuan Barang wajib diisi',
        //     'kategori.required' => 'Kategori Barang wajib diisi',
        //     'harga_pokok.required' => 'Harga Beli Barang wajib diisi',
        //     'harga_jual.required' => 'Harga Jual Barang wajib diisi',
        //     'harga_jual_default.required' => 'Harga Jual x Margin wajib diisi'
        // ];
        // $validasi = Validator::make($request->all(), $rules, $messages);

        // if($validasi->fails()){
        //     return response()->json(
        //         ['error' => $validasi->errors()->all()
        //     ], 400);
        // }

        $dataArray = json_decode($request->getContent(), true);

        if ($request->id_barang == '') {
            $cek_count = Barang::where('nama_barang', $dataArray['dataSave']['nama_barang'])
                ->select('*')
                ->count();
            if ($cek_count > 0) {
                return response()->json([
                    'success'   => false,
                    'message'   => 'Nama Barang sudah ada'
                ], 422);
                die();
            }
        }

        $table = 'm_barang';
        $field = 'id_barang';

        $id_barang = $this->idCreate($table, $field);
        if (empty($dataArray['dataSave']['kode_barcode'])) {
            $kode_barcode = $this->kodeBarcode();
            $save = Barang::updateOrCreate(
                [
                    'id_barang'     => $dataArray['dataSave']['id_barang']
                ],
                [
                    'id_barang'             => $id_barang,
                    'kode_barcode'          => $kode_barcode,
                    'nama_barang'           => htmlspecialchars($dataArray['dataSave']['nama_barang']),
                    'id_satuan'             => $dataArray['dataSave']['satuan'],
                    'id_kategori'           => $dataArray['dataSave']['kategori'],
                    'id_merek'              => $dataArray['dataSave']['merek'],
                    'stok_min'              => $dataArray['dataSave']['stok_min'],
                    'stok_max'              => $dataArray['dataSave']['stok_max'],
                    'harga_pokok'           => $dataArray['dataSave']['harga_pokok'],
                    'harga_jual'            => $dataArray['dataSave']['harga_jual'],
                    'margin'                => $dataArray['dataSave']['margin'],
                    'harga_jual_default'    => $dataArray['dataSave']['harga_jual_default'],
                    'user'                  => auth()->user()->noregistrasi,
                    'toko_id'               => auth()->user()->toko_id
                ]
            );
        } else {
            $save = Barang::updateOrCreate(
                [
                    'id_barang'     => $dataArray['dataSave']['id_barang']
                ],
                [
                    'id_barang'             => $id_barang,
                    'kode_barcode'          => $dataArray['dataSave']['kode_barcode'],
                    'nama_barang'           => htmlspecialchars($dataArray['dataSave']['nama_barang']),
                    'id_satuan'             => $dataArray['dataSave']['satuan'],
                    'id_kategori'           => $dataArray['dataSave']['kategori'],
                    'id_merek'              => $dataArray['dataSave']['merek'],
                    'stok_min'              => $dataArray['dataSave']['stok_min'],
                    'stok_max'              => $dataArray['dataSave']['stok_max'],
                    'harga_pokok'           => $dataArray['dataSave']['harga_pokok'],
                    'harga_jual'            => $dataArray['dataSave']['harga_jual'],
                    'margin'                => $dataArray['dataSave']['margin'],
                    'harga_jual_default'    => $dataArray['dataSave']['harga_jual_default'],
                    'user'                  => auth()->user()->noregistrasi,
                    'toko_id'               => auth()->user()->toko_id
                ]
            );
        }

        if($save) {
            $data = Barang::where('m_barang.id_barang', $id_barang)
                ->select('m_barang.*')
                ->first();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
                'data'    => $data
            ], 200);
        }

        return response()->json([
            'success'   => false,
            'message'   => 'Data gagal disimpan'
        ], 422);

        // dd($dataArray['dataSave']['kode_barcode']);
    }

    public function barangEdit($id) {
        $barangEdit = Barang::join('m_kategori', 'm_barang.id_kategori', '=', 'm_kategori.id_kategori')
            ->join('m_satuan', 'm_barang.id_satuan', '=', 'm_satuan.id_satuan')
            ->join('m_merek', 'm_barang.id_merek', '=', 'm_merek.id_merek')
            ->select(
                'm_barang.*',
                'm_kategori.nama_kategori',
                'm_satuan.desc_satuan',
                'm_merek.desc_merek'
            )
            ->where('id_barang', $id)
            ->first();

        return response()->json([
            'success'   => true,
            'item'      => $barangEdit
        ], 200);
    }

    public function barangDelete($id_barang) {
        // Cari barang berdasarkan ID
        $barang = Barang::where('id_barang', $id_barang)->first();

        // Cek apakah barang ditemukan
        if (!$barang) {
            return response()->json([
                'success' => false,
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }

        // Update statusenabled menjadi 0
        $barang->statusenabled = 0;
        $barang->user = auth()->user()->noregistrasi;
        $barang->save();

        // Mengembalikan response JSON
        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil dinonaktifkan'
        ], 200);
    }

    public function stokBarang() {
        if (request()->ajax()) {
            $stok = DB::table('t_total_stok_barang as tsb')
                ->join('m_barang as mb', 'tsb.id_barang', '=', 'mb.id_barang')
                ->join('m_satuan as st', 'mb.id_satuan', '=', 'st.id_satuan')
                ->join('m_kategori as kt', 'mb.id_kategori', '=', 'kt.id_kategori')
                ->select(
                    'tsb.id_stok_barang',
                    'tsb.id_barang',
                    'mb.kode_barcode',
                    'mb.nama_barang',
                    'st.desc_satuan',
                    'kt.nama_kategori',
                    'tsb.total_stok',
                    DB::raw('(tsb.total_stok * mb.harga_jual_default) AS jumlah_uang')
                )
                ->where('tsb.toko_id', auth()->user()->toko_id)
                ->orderBy('mb.nama_barang', 'ASC')
                ->get();

            return DataTables::of($stok)
                ->addIndexColumn()
                ->make();
        }
        return view('market.backend.barang.list-stok-barang');
    }
}
