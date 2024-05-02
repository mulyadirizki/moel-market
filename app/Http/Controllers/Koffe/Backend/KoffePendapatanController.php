<?php

namespace App\Http\Controllers\Koffe\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;

class KoffePendapatanController extends Controller
{
    public function dataPendapatan(Request $request)
    {
        if (request()->ajax()) {
            $page       = $request->get('page');
            $limit      = $request->get('limit');
            // $category   = $request->category;
            $sort       = $request->get('sort');

            $column = preg_replace("/\W/", "", $sort);
            $asc    = substr($sort, 0, 1);
            $ascdsc = $asc == '+' ? 'ASC' : 'DESC';

            $total_penjualan = DB::table('t_penjualan')
                ->select(DB::raw('DATE(tgl_nota) AS tgl_nota'), DB::raw('SUM(total) AS total_penjualan'))
                ->groupBy(DB::raw('DATE(tgl_nota)'))
                ->orderBy(DB::raw('DATE(tgl_nota)'), 'desc')
                ->get();

            $total_pembelian = DB::table('t_pengeluaran')
                ->select(DB::raw('DATE(tgl_pengeluaran) AS tgl_pengeluaran'), DB::raw('SUM(harga_barang) AS total_pembelian'))
                ->groupBy(DB::raw('DATE(tgl_pengeluaran)'))
                ->orderBy(DB::raw('DATE(tgl_pengeluaran)'), 'desc')
                ->get();

            // Menggabungkan hasil kueri
            $data = [];
            foreach ($total_penjualan as $penjualan) {
                $tgl_nota = $penjualan->tgl_nota;
                $total_penjualan_tanggal = $penjualan->total_penjualan;

                // Mencari total pembelian yang sesuai dengan tanggal nota secara manual
                $total_pembelian_tanggal = 0;
                foreach ($total_pembelian as $pembelian) {
                    if ($pembelian->tgl_pengeluaran == $tgl_nota) {
                        $total_pembelian_tanggal = $pembelian->total_pembelian;
                        break;
                    }
                }

                // Menghitung selisih
                $selisih = $total_penjualan_tanggal - $total_pembelian_tanggal;

                // Menambahkan ke hasil akhir
                $data[] = [
                    'tgl_nota'          => $tgl_nota,
                    'total_penjualan'   => $total_penjualan_tanggal,
                    'total_pendapatan'  => $total_pembelian_tanggal,
                    'selisih'           => $selisih,
                ];
            }

            // Filter berdasarkan tanggal
            if (isset($request->tgl_nota) && $request->tgl_nota !== "" && $request->tgl_nota != "undefined") {
                $data = array_filter($data, function ($item) use ($request) {
                    return $item['tgl_nota'] >= $request->tgl_nota;
                });
            }

            if (isset($request->tgl_notaAkhir) && $request->tgl_notaAkhir !== "" && $request->tgl_notaAkhir != "undefined") {
                $data = array_filter($data, function ($item) use ($request) {
                    return $item['tgl_nota'] <= $request->tgl_notaAkhir;
                });
            }

            // Konversi kembali ke koleksi setelah filtering
            $data = collect($data);

            // Mengambil data sesuai dengan paginasi
            $item = $data->slice(($page - 1) * $limit, $limit)->values();

            return DataTables::of($item)
                ->addIndexColumn()
                ->make();
        }

        return view('koffe.backend.pendapatan.pendapatanIndex');
    }
}
