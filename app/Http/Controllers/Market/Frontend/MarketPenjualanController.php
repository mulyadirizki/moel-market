<?php

namespace App\Http\Controllers\Market\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Traits\MasterTrait;
use App\Models\PenjualanMarket;
use App\Models\PenjualanMarketDet;
use App\Models\StokBarang;
use App\Models\Stok;

class MarketPenjualanController extends Controller
{
    use MasterTrait;

    public function saveTransaksi(Request $request)
    {
        $penjualanData = $request->input('dataSave');

        $id_penjualan_market = $this->idCreate('t_penjualan_market', 'id_penjualan_market');

        // Transaksi database
        DB::transaction(function () use ($penjualanData, $id_penjualan_market) {
            // Insert ke tabel penjualan
            $penjualan = PenjualanMarket::create([
                'id_penjualan_market'   => $id_penjualan_market,
                'statusenabled'         => '1',
                'user'                  => auth()->user()->noregistrasi,
                'toko_id'               => auth()->user()->toko_id,
                'no_nota'               => $penjualanData['no_nota'],
                'tgl_nota'              => $penjualanData['tgl_nota'],
                'total'                 => $penjualanData['total'],
                'uang_bayar'            => $penjualanData['uang_bayar'],
                'uang_kembali'          => $penjualanData['uang_kembali'],
                'cara_bayar'            => $penjualanData['cara_bayar'],
                'nm_pelanggan'          => $penjualanData['nm_pelanggan']
            ]);

            // Loop melalui setiap barang dalam transaksi
            foreach ($penjualanData['barang'] as $barang) {
                // Insert ke tabel detail penjualan
                $id_penjualan_market_det = $this->idCreate('t_penjualan_market_det', 'id_penjualan_market_det');
                PenjualanMarketDet::create([
                    'id_penjualan_market_det'   => $id_penjualan_market_det,
                    'id_penjualan_market'       => $id_penjualan_market,
                    'tgl_penjualan'             => now(),
                    'id_barang'                 => $barang['id_barang'],
                    'qty'                       => $barang['qty'],
                    'harga_jual_default'        => $barang['harga_peritem'],
                    'sub_total'                 => $barang['sub_total'],
                    'user'                      => auth()->user()->noregistrasi,
                    'toko_id'                   => auth()->user()->toko_id,
                ]);

                // Mengurangi stok barang
                $this->kurangiStokBarang($barang['id_barang'], $barang['qty']);
            }
        });

        return response()->json([
            'success'   => true,
            'message'   => 'Transaksi berhasil disimpan',
            'id_penjualan_market' => $id_penjualan_market
        ], 200);
    }

    private function kurangiStokBarang($idBarang, $qty)
    {
        DB::transaction(function () use ($idBarang, $qty) {
            $initialQty = $qty; // Simpan nilai awal qty
            $detailBarangMasuk = Stok::where('id_barang', $idBarang)
                ->where('masuk', '>', 0)
                ->orderBy('tgl_expired', 'asc')
                ->get();

            foreach ($detailBarangMasuk as $detail) {
                $klr_awal = Stok::where('id_barang', $detail['id_barang'])
                    ->where('tgl_expired', $detail['tgl_expired'])
                    ->select('*')
                    ->first();

                $klr = !$klr_awal ? 0 : $klr_awal->keluar;
                $availableQty = $detail['masuk'] - $klr;

                if ($qty <= $availableQty) {
                    Stok::updateOrCreate([
                        'id_barang' => $detail['id_barang'],
                        'tgl_expired' => $detail['tgl_expired']
                    ], [
                        'keluar' => $klr + $qty,
                    ]);
                    $qty = 0; // Semua qty telah dikurangi
                    break;
                } else {
                    Stok::updateOrCreate([
                        'id_barang' => $detail['id_barang'],
                        'tgl_expired' => $detail['tgl_expired']
                    ], [
                        'keluar' => $klr + $availableQty,
                    ]);
                    $qty -= $availableQty; // Kurangi qty dengan jumlah yang telah dikeluarkan
                }
            }

            if ($qty > 0) {
                throw new \Exception("Qty penjualan melebihi stok yang tersedia untuk ID barang: $idBarang");
            }

            $totalStokBarang = StokBarang::where('id_barang', $idBarang)->first();
            if ($totalStokBarang) {
                $totalStokBarang->total_stok -= $initialQty; // Kurangi stok total dengan nilai awal qty
                $totalStokBarang->save();
            } else {
                // Tambahan: Handle error jika total stok barang tidak ditemukan
                throw new \Exception("Total stok barang tidak ditemukan untuk ID barang: $idBarang");
            }
        });
    }

    public function historyTransaksi() {
        if (request()->ajax()) {
            $historyPenjualan = PenjualanMarket::select('*')
                ->where('toko_id', auth()->user()->toko_id)
                ->where('statusenabled', 1)
                ->orderBy('t_penjualan_market.tgl_nota', 'ASC')
                ->get();

            return DataTables::of($historyPenjualan)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  dataId="'.$row->id_penjualan_market.'" data-original-title="Edit" class="edit btn btn-primary btn-sm btn-edit">Edit</a>';

                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  dataId="'. $row->id_penjualan_market .'" data-original-title="Delete" class="btn btn-danger btn-sm btn-delete">Delete</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make();
        }
        return view('market.frontend.history-transaksi');
    }

    public function historyTransaksiDelete(Request $request) {
        $data = json_decode($request->getContent(), true);
        $saveData = PenjualanMarket::updateOrCreate(
            [
                'id_penjualan_market'  => $data['dataObj']['id_penjualan_market'],
            ],
            [
                'keteranganhapus'    => $data['dataObj']['keteranganhapus'],
                'statusenabled'      => 0
            ]
        );

        if($saveData) {
            return response()->json([
                'success' => true,
                'message' => 'Delete transaksi Successful',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Delete transaksi Failed'
            ], 400);
        }
    }

    public function transaksiPrint($id_penjualan_market) {
        $datPenjualan = DB::table('t_penjualan_market AS pjm')
            ->join('users as usr', 'pjm.user', '=', DB::raw('usr.noregistrasi COLLATE utf8mb4_unicode_ci'))
            ->select(
                'pjm.no_nota',
                'pjm.tgl_nota',
                'usr.nama',
                'pjm.total',
                'pjm.uang_bayar',
                'pjm.uang_kembali',
                'pjm.nm_pelanggan',
                DB::raw('CASE
                            WHEN pjm.cara_bayar = 1 THEN "Cash"
                            WHEN pjm.cara_bayar = 2 THEN "Pay Later"
                            WHEN pjm.cara_bayar = 3 THEN "QRIS"
                            ELSE ""
                        END AS payment_method')
            )->where('pjm.id_penjualan_market', $id_penjualan_market)
            ->first();

            // dd($datPenjualan);

            $result = DB::table('t_penjualan_market_det AS pjmd')
                ->join('m_barang AS mb', 'pjmd.id_barang', '=', 'mb.id_barang')
                ->join('m_kategori AS kt', 'mb.id_kategori', '=', 'kt.id_kategori')
                ->select(
                    'pjmd.id_penjualan_market_det',
                    'pjmd.id_penjualan_market',
                    'pjmd.qty',
                    'pjmd.harga_jual_default AS harga_peritem',
                    'pjmd.sub_total',
                    'mb.nama_barang',
                    'kt.id_kategori',
                    'kt.nama_kategori'
                )
                ->where('pjmd.id_penjualan_market', '=', $id_penjualan_market)
                ->groupBy(
                    'pjmd.id_penjualan_market_det',
                    'pjmd.id_penjualan_market',
                    'pjmd.qty',
                    'pjmd.harga_jual_default',
                    'pjmd.sub_total',
                    'mb.nama_barang',
                    'kt.id_kategori',
                    'kt.nama_kategori'
                )
                ->get()
                ->groupBy('nama_kategori') // Mengelompokkan hasil berdasarkan nama kategori setelah pengambilan data
                ->toArray();


        // dd($datPenjualan);

        return view('market.frontend.cetakan.billing-market', compact('datPenjualan', 'result'));
    }

}
