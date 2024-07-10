<?php

namespace App\Http\Controllers\Market\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Traits\MasterTrait;
use App\Models\Barang;
use App\Models\Terima;
use App\Models\TerimaDet;
use App\Models\Stok;
use App\Models\StokDet;

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

    public function barangMasuk() {
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
        return view('market.backend.barang.list-barang-masuk');
    }

    public function barangMasukAdd(Request $request) {
        // $data = $request->input('dataSave');

        // foreach ($data['barang'] as $key => $item) {
        //     dd($item['id_barang']);
        // }

        try {
            DB::transaction(function () use ($request) {
                $data = $request->input('dataSave');

                $id_terima = $this->idCreate('t_barang_terima', 'id_terima');

                Terima::create([
                    'id_terima'     => $id_terima,
                    'tgl_terima'    => $data['tgl_terima'],
                    'tgl_faktur'    => $data['tgl_faktur'],
                    'no_faktur'     => $data['no_faktur'],
                    'id_supplier'   => $data['supplier'],
                    'id_tx'         => 1, //lihat tabel m_tx
                    'user'          => auth()->user()->noregistrasi,
                    'toko_id'       => auth()->user()->toko_id
                ]);

                foreach ($data['barang'] as $key => $item) {
                    TerimaDet::create([
                        'id_terima'     => $id_terima,
                        'id_barang'     => $item['id_barang'],
                        'qty'           => $item['qty'],
                        'tgl_expired'   => $item['tgl_expired'],
                        'user'          => auth()->user()->noregistrasi,
                        'toko_id'       => auth()->user()->toko_id
                    ]);

                    //STOK Update or Create
                    $msk_awal = Stok::where('th', date('Y', strtotime($data['tgl_terima'])))
                        ->where('bln', date('m', strtotime($data['tgl_terima'])))
                        ->where('id_barang', $item['id_barang'])
                        ->where('tgl_expired', $item['tgl_expired'])
                        ->select('*')
                        ->first();

                    $msk = !$msk_awal ? 0 : $msk_awal->masuk;

                    Stok::updateOrCreate([
                        'th'            => date('Y', strtotime($data['tgl_terima'])),
                        'bln'           => date('m', strtotime($data['tgl_terima'])),
                        'id_barang'     => $item['id_barang'],
                        'rak'           => 'ABC-123',
                        'tgl_expired'   => $item['tgl_expired'],
                        'toko_id'       => auth()->user()->toko_id
                    ], [
                        'masuk' => $msk + $request->qty[$key],
                    ]);
                    //end STOK Update or Create

                    //STOK DET create
                    // StokDet::create([
                    //     'id_tx'         => 1,
                    //     //dari daftar m_tx
                    //     'id_barang'     => $id_brg,
                    //     'qty'           => $request->qty[$key],
                    //     'tarif_beli'    => preg_replace("/[^0-9]/", "", $request->tarif[$key]),
                    //     'tarif_jual'    => preg_replace("/[^0-9]/", "", $request->tarif[$key]),
                    //     'id_trans'      => $id_terima,
                    //     'tgl_trans'     => $request->tgl_terima,
                    //     'id_lokasi'     => $id_lokasi,
                    //     'expired'       => $request->expired[$key],
                    //     'in_out'        => 1, //1 msk, 0 klr
                    // ]);
                    //end STOK DET create
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Success to add data',
                // 'data'      => $resep_list,
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
