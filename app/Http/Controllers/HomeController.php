<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Return_;

class HomeController extends Controller
{
    public function indexMarket()
    {
        return view('market.backend.home');
    }

    public function indexManajemenMarket()
    {
        return view('market.manajemen.home');
    }

    public function dataPenjualan(Request $request)
    {
        $user = User::all()
            ->where('toko_id', auth()->user()->toko_id)
            ->where('roles', 2);
        if (request()->ajax()) {
            $page       = $request->get('page');
            $limit      = $request->get('limit');
            $kasir       = $request->kasir;
            $sort       = $request->get('sort');

            $column = preg_replace("/\W/", "", $sort);
            $asc    = substr($sort, 0, 1);
            $ascdsc = $asc == '-' ? 'ASC' : 'DESC';

            $data = DB::table('t_penjualan_market_det AS pmd')
                ->select(
                    'pmd.id_penjualan_market',
                    'pmd.id_penjualan_market_det',
                    'pmd.tgl_penjualan',
                    'pmd.id_barang',
                    'brg.nama_barang',
                    'pmd.qty',
                    'pmd.harga_jual_default',
                    'pmd.sub_total',
                    'usr.nama'
                )
                ->join('m_barang AS brg', 'pmd.id_barang', '=', 'brg.id_barang')
                ->leftJoin('users AS usr', 'pmd.user', '=', 'usr.noregistrasi')
                ->where('pmd.statusenabled', 1)
                ->where('pmd.toko_id', auth()->user()->toko_id)
                ->when($request->filled('kasir'), function ($query) use ($request) {
                    $query->where('usr.noregistrasi', $request->kasir);
                });

            if(isset($request->tgl_penjualan) && $request->tgl_penjualan !== "" && $request->tgl_penjualan != "undefined" ) {
                $data = $data->whereRaw("DATE_FORMAT(pmd.tgl_penjualan, '%Y-%m-%d %H:%i') >= ?", [$request->tgl_penjualan]);
            }

            if(isset($request->tgl_penjualanAkhir) && $request->tgl_penjualanAkhir !== "" && $request->tgl_penjualanAkhir != "undefined" ) {
                $data = $data->whereRaw("DATE_FORMAT(pmd.tgl_penjualan, '%Y-%m-%d %H:%i') <= ?", [$request->tgl_penjualanAkhir]);
            }

            $item = $data->offset(($page * $limit) - $limit)->limit($limit)->get();

            return response()->json([
                'success' => true,
                'data'    => $item
            ], 200);
        }
        return view('market.manajemen.penjualan', compact('user'));
    }

    public function dataStokBarang(Request $request) {
        if ($request->ajax()) {
            $item = DB::table('t_total_stok_barang as tsb')
                ->leftJoin('m_barang as mb', 'tsb.id_barang', '=', 'mb.id_barang')
                ->leftJoin('m_satuan as st', 'mb.id_satuan', '=', 'st.id_satuan')
                ->select(
                    'tsb.id_stok_barang',
                    'tsb.id_barang',
                    'mb.kode_barcode',
                    'mb.nama_barang',
                    'st.desc_satuan',
                    'tsb.total_stok',
                    'mb.harga_jual_default',
                    DB::raw('tsb.total_stok * mb.harga_jual_default AS total_rupiah')
                )
                ->where('tsb.toko_id', auth()->user()->toko_id)
                ->get();

            return response()->json([
                'success' => true,
                'data'    => $item
            ], 200);
        }
        return view('market.manajemen.stokBarang');
    }

    public function dataLabaPendapatan(Request $request) {
        if (request()->ajax()) {
            $page  = $request->get('page'); // Default page to 1 if not provided
            $limit = $request->get('limit'); // Default limit to 10 if not provided
            $sort  = $request->get('sort', '-pmd.tgl_penjualan'); // Default sort column

            // Remove any non-alphanumeric characters from the column name
            $column = preg_replace("/[^a-zA-Z0-9_\.]/", "", $sort);
            $asc    = substr($sort, 0, 1);
            $ascdsc = $asc == '-' ? 'DESC' : 'ASC';

            // Adjust the column if it starts with a sorting indicator ('-' for DESC)
            if ($asc == '-') {
                $column = substr($column, 1);
            }

            // Base query
            $data = DB::table('t_penjualan_market_det as pmd')
                ->leftJoin('m_barang as mb', 'pmd.id_barang', '=', 'mb.id_barang')
                ->selectRaw('
                    DATE(pmd.tgl_penjualan) as tgl_penjualan,
                    SUM(pmd.sub_total) as total_penjualan,
                    SUM(pmd.qty * mb.harga_pokok) as total_harga_pokok,
                    (SUM(pmd.sub_total) - SUM(pmd.qty * mb.harga_pokok)) as laba
                ')
                ->where('pmd.statusenabled', 1)
                ->where('pmd.toko_id', auth()->user()->toko_id)
                ->groupBy(DB::raw('DATE(pmd.tgl_penjualan)'));

            // Filter by start date
            if (!empty($request->tgl_penjualan)) {
                $data = $data->whereRaw("DATE(pmd.tgl_penjualan) >= ?", [$request->tgl_penjualan]);
            }

            // Filter by end date
            if (!empty($request->tgl_penjualanAkhir)) {
                $data = $data->whereRaw("DATE(pmd.tgl_penjualan) <= ?", [$request->tgl_penjualanAkhir]);
            }

            // Sorting
            $data = $data->orderBy($column, $ascdsc);

            // Pagination: offset calculation and limit
            $data = $data->offset(($page - 1) * $limit)->limit($limit);

            // Fetch the data
            $items = $data->get();

            return response()->json([
                'success' => true,
                'data'    => $items
            ], 200);
        }
        return view('market.manajemen.laba');
    }
}
