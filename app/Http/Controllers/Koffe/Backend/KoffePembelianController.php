<?php

namespace App\Http\Controllers\Koffe\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengeluaran;
use Illuminate\Support\Facades\DB;
use DataTables;

class KoffePembelianController extends Controller
{
    public function dataPembelian(Request $request)
    {

        if (request()->ajax()) {
            $page       = $request->get('page');
            $limit      = $request->get('limit');
            // $category   = $request->category;
            $sort       = $request->get('sort');

            $column = preg_replace("/\W/", "", $sort);
            $asc    = substr($sort, 0, 1);
            $ascdsc = $asc == '-' ? 'ASC' : 'DESC';

            $data = Pengeluaran::select('t_pengeluaran.*')
                ->where('toko_id', auth()->user()->toko_id);

            if(isset($request->tgl_pengeluaran) && $request->tgl_pengeluaran !== "" && $request->tgl_pengeluaran != "undefined" ) {
                $data = $data->whereRaw("DATE_FORMAT(t_pengeluaran.tgl_pengeluaran, '%Y-%m-%d %H:%i') >= ?", [$request->tgl_pengeluaran]);
            }

            if(isset($request->tgl_pengeluaranAkhir) && $request->tgl_pengeluaranAkhir !== "" && $request->tgl_pengeluaranAkhir != "undefined" ) {
                $data = $data->whereRaw("DATE_FORMAT(t_pengeluaran.tgl_pengeluaran, '%Y-%m-%d %H:%i') <= ?", [$request->tgl_pengeluaranAkhir]);
            }

            $item = $data->offset(($page * $limit) - $limit)->limit($limit)->get();

            return DataTables::of($item)
                ->addIndexColumn()
                ->editColumn('jenispembayaran', function($row) {
                    if ($row->jenis_pembayaran === 1) {
                        return '<span>Cash</span>';
                    } else if ($row->jenis_pembayaran === 2) {
                        return '<span>Transfer</span>';
                    } else if ($row->jenis_pembayaran === 3) {
                        return '<span>Hutan</span>';
                    }
                })
                ->addColumn('action', function($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  dataId="'.$row->id_pengeluaran.'" data-original-title="Edit" class="edit btn btn-primary btn-sm btn-edit">Edit</a>';

                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  dataId="'. $row->id_pengeluaran .'" data-original-title="Delete" class="btn btn-danger btn-sm btn-delete">Delete</a>';

                    return $btn;
                })
                ->rawColumns(['action', 'jenispembayaran'])
                ->make();
        }
        return view('koffe.backend.pembelian.pembelian');
    }
}
