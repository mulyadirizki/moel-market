<?php

namespace App\Http\Controllers\Market\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
// use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Traits\MasterTrait;
use App\Models\Terima;
use App\Models\TerimaDet;
use App\Models\Stok;
use App\Models\StokDet;
use App\Models\StokOpname;
use App\Models\StokBarang;

class MarketPenerimaanBarangController extends Controller
{

    use MasterTrait;

    public function barangMasuk() {
        if (request()->ajax()) {
            $barangMasuk = Terima::select(
                    't_barang_terima.id_terima',
                    't_barang_terima.tgl_terima',
                    't_barang_terima.tgl_faktur',
                    't_barang_terima.no_faktur',
                    'm_supplier.nama_supplier'
                )
                ->join('m_supplier', 'm_supplier.id_supplier', '=', 't_barang_terima.id_supplier')
                ->orderBy('t_barang_terima.tgl_terima', 'DESC')
                ->get();

            return DataTables::of($barangMasuk)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  dataId="'.$row->id_terima.'" data-original-title="Edit" class="edit btn btn-primary btn-sm btn-edit">Edit</a>';

                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  dataId="'. $row->id_terima .'" data-original-title="Delete" class="btn btn-danger btn-sm btn-delete">Delete</a>';

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
                        ->where('toko_id', auth()->user()->toko_id)
                        ->select('*')
                        ->first();

                    $msk = !$msk_awal ? 0 : $msk_awal->masuk;

                    // return $msk;

                    Stok::updateOrCreate([
                        'th'            => date('Y', strtotime($data['tgl_terima'])),
                        'bln'           => date('m', strtotime($data['tgl_terima'])),
                        'id_terima'     => $id_terima,
                        'id_barang'     => $item['id_barang'],
                        'rak'           => 'ABC-123',
                        'tgl_expired'   => $item['tgl_expired'],
                        'user'          => auth()->user()->noregistrasi,
                        'toko_id'       => auth()->user()->toko_id
                    ], [
                        'masuk' => $msk + $item['qty'],
                    ]);
                    //end STOK Update or Create

                    //STOK DET create
                    StokDet::create([
                        'id_tx'                 => 1,//dari daftar m_tx
                        'id_barang'             => $item['id_barang'],
                        'qty'                   => $item['qty'],
                        'harga_pokok'           => $item['harga_pokok'],
                        'harga_jual'            => $item['harga_jual'],
                        'harga_jual_default'    => $item['harga_jual_default'],
                        'id_trans'              => $id_terima,
                        'tgl_trans'             => $data['tgl_terima'],
                        'tgl_expired'           => $item['tgl_expired'],
                        'in_out'                => 1, //1 msk, 0 klr
                        'user'                  => auth()->user()->noregistrasi,
                        'toko_id'               => auth()->user()->toko_id
                    ]);
                    //end STOK DET create

                    $total = StokBarang::where('id_barang', $item['id_barang'])
                        ->where('toko_id', auth()->user()->toko_id)
                        ->select('total_stok')
                        ->first();

                    // Jika tidak ada, set total stok awal menjadi 0
                    $totl = $total ? $total->total_stok : 0;

                    // Update atau buat record baru dengan total stok yang diperbarui
                    StokBarang::updateOrCreate(
                        [
                            'id_barang' => $item['id_barang'],
                            'toko_id' => auth()->user()->toko_id
                        ],
                        [
                            'total_stok' => $totl + $item['qty'],
                        ]
                    );
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

    public function barangMasukEdit($id_terima) {
        $penerimaan = Terima::where('id_terima', $id_terima)
            ->join('m_supplier', 't_barang_terima.id_supplier', '=', 'm_supplier.id_supplier')
            ->select(
                't_barang_terima.*',
                'm_supplier.nama_supplier'
            )
            ->first();

        $penerimaan_det = TerimaDet::where('id_terima', $id_terima)
            ->join('m_barang', 't_barang_terima_det.id_barang', '=', 'm_barang.id_barang')
            ->select(
                't_barang_terima_det.*',
                'm_barang.nama_barang',
                'm_barang.harga_pokok',
                'm_barang.harga_jual',
                'm_barang.harga_jual_default'
            )
            ->get();

        if ($penerimaan) {
            return response()->json([
                'success' => true,
                'message' => '',
                'data' => array(
                    'penerimaan' => $penerimaan,
                    'penerimaan_det' => $penerimaan_det
                ),
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to load data',
        ], 422);
    }

    public function barangMasukUpdate(Request $request) {
        $data = $request->input('dataSave');

        $bln = date('m', strtotime($data['tgl_terima']));
        $thn = date('Y', strtotime($data['tgl_terima']));

        $cek_count = StokOpname::where('th', $thn)
            ->where('bln', $bln)
            ->where('aktif', 1)
            ->count();

        if ($cek_count > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot add data, because already stock opname [' . $thn . '/' . $bln . ']',
            ], 422);
        }

        try {
            DB::transaction(function () use ($request) {
                $data = $request->input('dataSave');

                Terima::where('id_terima', $data['id_terima'])
                    ->update([
                        'tgl_terima'    => $data['tgl_terima'],
                        'tgl_faktur'    => $data['tgl_faktur'],
                        'no_faktur'     => $data['no_faktur'],
                        'id_supplier'   => $data['supplier'],
                        'id_tx'         => 1, //lihat tabel m_tx
                        'user'          => auth()->user()->noregistrasi,
                        'toko_id'       => auth()->user()->toko_id
                    ]);
                foreach ($data['barang'] as $value) {
                    $total = StokBarang::where('id_barang', $value['id_barang'])
                        ->where('toko_id', auth()->user()->toko_id)
                        ->first();

                    $qtyStok = Stok::where('id_terima', $data['id_terima'])
                        ->where('id_barang', $value['id_barang'])
                        ->where('toko_id', auth()->user()->toko_id)
                        ->first();

                    $awal = $qtyStok->awal !== null ? $qtyStok->awal : 0;
                    $masuk = $qtyStok->masuk !== null ? $qtyStok->masuk : 0;
                    $keluar = $qtyStok->keluar !== null ? $qtyStok->keluar : 0;

                    $neqWtyStok = $awal + $masuk - $keluar;

                    $newQty = $total->total_stok - $neqWtyStok + $value['qty'];

                    // dd($keluar);

                    StokBarang::updateOrCreate(
                        [
                            'id_barang' => $value['id_barang'],
                            'toko_id' => auth()->user()->toko_id
                        ],
                        [
                            'total_stok' => $newQty,
                        ]
                    );
                }

                // Kurangi stok terlebih dahulu
                $terima_det_delete = TerimaDet::where('id_terima', $data['id_terima'])->get();
                foreach ($terima_det_delete as $terima_det) {
                    $stok = Stok::where('id_terima', $data['id_terima'])
                        ->where('id_barang', $terima_det->id_barang)
                        ->where('toko_id', auth()->user()->toko_id)
                        ->first();

                    if ($stok) {
                        $stok->update(['masuk' => $stok->masuk - $terima_det->qty]);
                    }
                }

                // Terima Det, Stok Det hapus semua
                TerimaDet::where('id_terima', $data['id_terima'])->delete();
                StokDet::where('id_trans', $data['id_terima'])->delete();

                // Create ulang
                foreach ($data['barang'] as $item) {
                    // STOK Update or Create
                    $stok = Stok::where('id_terima', $data['id_terima'])
                        ->where('id_barang', $item['id_barang'])
                        ->where('toko_id', auth()->user()->toko_id)
                        ->first();

                    $stokKlr = !$stok ? 0 : $stok->keluar;

                    TerimaDet::create([
                        'id_terima'     => $data['id_terima'],
                        'id_barang'     => $item['id_barang'],
                        'qty'           => $item['qty'] + $stokKlr,
                        'tgl_expired'   => $item['tgl_expired'],
                        'user'          => auth()->user()->noregistrasi,
                        'toko_id'       => auth()->user()->toko_id
                    ]);

                    if ($stok) {
                        $stok->update(['masuk' => $stok->masuk + $item['qty'] + $stokKlr]);
                    } else {
                        Stok::create([
                            'th'            => date('Y', strtotime($data['tgl_terima'])),
                            'bln'           => date('m', strtotime($data['tgl_terima'])),
                            'id_barang'     => $item['id_barang'],
                            'rak'           => 'ABC-123',
                            'tgl_expired'   => $item['tgl_expired'],
                            'masuk'         => $item['qty'] + $stokKlr,
                            'user'          => auth()->user()->noregistrasi,
                            'toko_id'       => auth()->user()->toko_id
                        ]);
                    }

                    // STOK DET create
                    StokDet::create([
                        'id_tx'                 => 1,//dari daftar m_tx
                        'id_barang'             => $item['id_barang'],
                        'qty'                   => $item['qty'] + $stokKlr,
                        'harga_pokok'           => $item['harga_pokok'],
                        'harga_jual'            => $item['harga_jual'],
                        'harga_jual_default'    => $item['harga_jual_default'],
                        'id_trans'              => $data['id_terima'],
                        'tgl_trans'             => $data['tgl_terima'],
                        'tgl_expired'           => $item['tgl_expired'],
                        'in_out'                => 1, //1 msk, 0 klr
                        'user'                  => auth()->user()->noregistrasi,
                        'toko_id'               => auth()->user()->toko_id
                    ]);
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Success to update data',
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update data ' . $e,
                'pesan' => $e
            ], 422);
        }
    }

    public function barangMasukDelete($id_terima) {

        $terima = Terima::where('id_terima', $id_terima)->get()->first();

        $terima_det_delete = Terima::join('t_barang_terima_det', 't_barang_terima.id_terima', '=', 't_barang_terima_det.id_terima')
            ->where('t_barang_terima.id_terima', $id_terima)
            ->select(
                't_barang_terima.id_terima',
                't_barang_terima.tgl_terima',
                't_barang_terima_det.id_barang',
                't_barang_terima_det.qty'
            )
            ->get();

        // cek sudah stok opname atau blm
        $bln = date('m', strtotime($terima->tgl_terima));
        $thn = date('Y', strtotime($terima->tgl_terima));

        $cek_count = StokOpname::where('th', $thn)
            ->where('bln', $bln)
            ->where('aktif', 1)
            ->select('*')
            ->count();

        if ($cek_count > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot add data, because already stock opname [' . $thn . '/' . $bln . ']',
            ], 422);
            die();
        }

        try {
            DB::transaction(function () use ($id_terima, $terima_det_delete) {
                //kurangi stok terlebih dahulu
                foreach ($terima_det_delete as $key => $terima_det) {
                    $stok = Stok::where('th', date('Y', strtotime($terima_det->tgl_terima)))
                        ->where('bln', date('m', strtotime($terima_det->tgl_terima)))
                        ->where('id_barang', $terima_det->id_barang);

                    $stok->update(['masuk' => $stok->first()->masuk - $terima_det->qty]);
                }

                TerimaDet::where('id_terima', $id_terima)->delete();
                Terima::where('id_terima', $id_terima)->delete();
                StokDet::where('id_trans', $id_terima)->delete();
            });

            return response()->json([
                'success' => true,
                'message' => 'Success to delete data',
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
