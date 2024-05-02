<?php

namespace App\Http\Controllers\Koffe\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PenjualanDet;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use DataTables;

class KoffePenjualanController extends Controller
{
    public function penjualanButuhDibayarkan(Request $request)
    {
        if (request()->ajax()) {
            $page       = $request->get('page');
            $limit      = $request->get('limit');
            // $category   = $request->category;
            $sort       = $request->get('sort');

            $column = preg_replace("/\W/", "", $sort);
            $asc    = substr($sort, 0, 1);
            $ascdsc = $asc == '-' ? 'ASC' : 'DESC';

            $data = DB::table('t_penjualan_det as pjd')
                ->select(
                    'pj.no_nota',
                    'pj.tgl_nota',
                    'itm.item_name',
                    'vr.variant_name',
                    'ct.category_name',
                    'pjd.qty',
                    'pjd.sub_total',
                    'pj.status',
                    'pj.statusenabled',
                    'pj.nm_pelanggan'
                )
                ->leftJoin('t_penjualan as pj', 'pjd.id_penjualan', '=', 'pj.id_penjualan')
                ->leftJoin('m_item as itm', 'pjd.id_item', '=', 'itm.id_item')
                ->leftJoin('m_category as ct', 'itm.category_id', '=', 'ct.id_category')
                ->leftJoin('m_variant as vr', 'vr.id_item', '=', 'itm.id_item')
                ->where('pj.toko_id', auth()->user()->toko_id)
                ->where('pj.statusenabled', 't')
                ->where('pj.status', '=', '2');

            if(isset($request->tgl_penjualan) && $request->tgl_penjualan !== "" && $request->tgl_penjualan != "undefined" ) {
                $data = $data->whereRaw("DATE_FORMAT(pjd.tgl_penjualan, '%Y-%m-%d %H:%i') >= ?", [$request->tgl_penjualan]);
            }

            if(isset($request->tgl_penjualanAkhir) && $request->tgl_penjualanAkhir !== "" && $request->tgl_penjualanAkhir != "undefined" ) {
                $data = $data->whereRaw("DATE_FORMAT(pjd.tgl_penjualan, '%Y-%m-%d %H:%i') <= ?", [$request->tgl_penjualanAkhir]);
            }

            $item = $data->offset(($page * $limit) - $limit)->limit($limit)->get();

            return DataTables::of($item)
                ->addIndexColumn()
                ->editColumn('statuspayment', function($row) {
                    if ($row->status == 2) {
                        return '<span class="badge bg-warning">Belum Dibayar</span>';
                    }
                })
                ->editColumn('paymentmethod', function($row) {
                    if ($row->status == 1) {
                        return '<span class="badge bg-success">Cash</span>';
                    } else if ($row->status == 2) {
                        return '<span class="badge bg-danger">Paylater</span>';
                    } else if ($row->status == 3) {
                        return '<span class="badge bg-info">QRIS</span>';
                    }
                })
                ->rawColumns(['statuspayment', 'paymentmethod'])
                ->make();
        }
        return view('koffe.backend.penjualan.penjualanButuhDibayarkan');
    }

    public function penjualanSelesai(Request $request)
    {
        $category = Category::all();
        if (request()->ajax()) {
            $page       = $request->get('page');
            $limit      = $request->get('limit');
            $category   = $request->category;
            $sort       = $request->get('sort');

            $column = preg_replace("/\W/", "", $sort);
            $asc    = substr($sort, 0, 1);
            $ascdsc = $asc == '-' ? 'ASC' : 'DESC';

            $data = DB::table('t_penjualan_det as pjd')
                ->select(
                    'pjd.id_penjualan_det',
                    'pjd.id_penjualan',
                    'pj.no_nota',
                    'pjd.tgl_penjualan',
                    'itm.item_name',
                    'vr.variant_name',
                    'ct.category_name',
                    'pjd.qty',
                    'pjd.harga_peritem',
                    'pjd.sub_total',
                    'pj.status',
                    'pj.statusenabled',
                    'pj.toko_id'
                )
                ->leftJoin('t_penjualan as pj', 'pjd.id_penjualan', '=', 'pj.id_penjualan')
                ->leftJoin('m_item as itm', 'pjd.id_item', '=', 'itm.id_item')
                ->leftJoin('m_category as ct', 'itm.category_id', '=', 'ct.id_category')
                ->leftJoin('m_variant as vr', 'vr.id_item', '=', 'itm.id_item')
                ->where('pj.toko_id', auth()->user()->toko_id)
                ->where('pj.statusenabled', true)
                ->where('pj.status', '<>', '2')
                ->where('itm.category_id', 'like', '%' . $category . '%');

            if(isset($request->tgl_penjualan) && $request->tgl_penjualan !== "" && $request->tgl_penjualan != "undefined" ) {
                $data = $data->whereRaw("DATE_FORMAT(pjd.tgl_penjualan, '%Y-%m-%d %H:%i') >= ?", [$request->tgl_penjualan]);
            }

            if(isset($request->tgl_penjualanAkhir) && $request->tgl_penjualanAkhir !== "" && $request->tgl_penjualanAkhir != "undefined" ) {
                $data = $data->whereRaw("DATE_FORMAT(pjd.tgl_penjualan, '%Y-%m-%d %H:%i') <= ?", [$request->tgl_penjualanAkhir]);
            }

            $item = $data->offset(($page * $limit) - $limit)->limit($limit)->get();

            return DataTables::of($item)
                ->addIndexColumn()
                ->editColumn('statuspayment', function($row) {
                    if ($row->statusenabled == true) {
                        return '<span class="badge bg-success">Selesai</span>';
                    }
                })
                ->editColumn('paymentmethod', function($row) {
                    if ($row->status == 1) {
                        return '<span class="badge bg-success">Cash</span>';
                    } else if ($row->status == 2) {
                        return '<span class="badge bg-danger">Paylater</span>';
                    } else if ($row->status == 3) {
                        return '<span class="badge bg-info">QRIS</span>';
                    }
                })
                ->rawColumns(['statuspayment', 'paymentmethod'])
                ->make();
        }
        return view('koffe.backend.penjualan.penjualanSelesai', compact('category'));
    }

    public function penjualanRefund(Request $request)
    {
        if (request()->ajax()) {
            $page       = $request->get('page');
            $limit      = $request->get('limit');
            // $category   = $request->category;
            $sort       = $request->get('sort');

            $column = preg_replace("/\W/", "", $sort);
            $asc    = substr($sort, 0, 1);
            $ascdsc = $asc == '-' ? 'ASC' : 'DESC';

            $data = DB::table('t_penjualan_det as pjd')
                ->select(
                    'pj.no_nota',
                    'pj.tgl_nota',
                    'itm.item_name',
                    'vr.variant_name',
                    'ct.category_name',
                    'pjd.qty',
                    'pjd.sub_total',
                    'pj.status',
                    'pj.statusenabled',
                    'pj.keteranganrefund',
                    'pj.updated_at'
                )
                ->leftJoin('t_penjualan as pj', 'pjd.id_penjualan', '=', 'pj.id_penjualan')
                ->leftJoin('m_item as itm', 'pjd.id_item', '=', 'itm.id_item')
                ->leftJoin('m_category as ct', 'itm.category_id', '=', 'ct.id_category')
                ->leftJoin('m_variant as vr', 'vr.id_item', '=', 'itm.id_item')
                ->where('pj.toko_id', auth()->user()->toko_id)
                ->where('pj.statusenabled', 'f');

            if(isset($request->tgl_penjualan) && $request->tgl_penjualan !== "" && $request->tgl_penjualan != "undefined" ) {
                $data = $data->whereRaw("DATE_FORMAT(pjd.tgl_penjualan, '%Y-%m-%d %H:%i') >= ?", [$request->tgl_penjualan]);
            }

            if(isset($request->tgl_penjualanAkhir) && $request->tgl_penjualanAkhir !== "" && $request->tgl_penjualanAkhir != "undefined" ) {
                $data = $data->whereRaw("DATE_FORMAT(pjd.tgl_penjualan, '%Y-%m-%d %H:%i') <= ?", [$request->tgl_penjualanAkhir]);
            }

            $item = $data->offset(($page * $limit) - $limit)->limit($limit)->get();

            return DataTables::of($item)
                ->addIndexColumn()
                ->editColumn('statuspayment', function($row) {
                    if ($row->statusenabled == 'f') {
                        return '<span class="badge bg-danger">Dibatalkan</span>';
                    }
                })
                ->editColumn('paymentmethod', function($row) {
                    if ($row->status == 1) {
                        return '<span class="badge bg-success">Cash</span>';
                    } else if ($row->status == 2) {
                        return '<span class="badge bg-danger">Paylater</span>';
                    } else if ($row->status == 3) {
                        return '<span class="badge bg-info">QRIS</span>';
                    }
                })
                ->rawColumns(['statuspayment', 'paymentmethod'])
                ->make();
        }
        return view('koffe.backend.penjualan.penjualanRefund');
    }
}
