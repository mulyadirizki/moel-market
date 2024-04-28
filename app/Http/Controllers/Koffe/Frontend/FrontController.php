<?php

namespace App\Http\Controllers\Koffe\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Item;
use App\Models\Variant;
use App\Models\Penjualan;
use App\Models\PenjualanDet;
use App\Models\Pengeluaran;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid as Generator;
use DataTables;

class FrontController extends Controller
{
    public function index()
    {
        $category = Category::where('toko_id', auth()->user()->toko_id)
            ->select('m_category.*')
            ->get();
        return view('koffe.frontend.home', compact('category'));
    }

    public function allItem()
    {
        $allItem = Variant::leftJoin('m_item', 'm_variant.id_item', '=', 'm_item.id_item')
            ->select(
                'm_variant.id_variant',
                'm_variant.variant_name',
                'm_variant.price',
                'm_variant.sku',
                'm_variant.id_item',
                'm_item.item_name',
                'm_item.toko_id'
            )
            ->where('m_item.toko_id', auth()->user()->toko_id)
            ->orderBy('m_item.item_name', 'asc')
            ->get();

        if (request()->expectsJson()) {
            return response()->json([
                'data' => $allItem
            ], 200);
        } else {
            return view('koffe.frontend.items.allItem', compact('allItem'));
        }
    }

    public function categoryItem($id_category)
    {

        $itemCategory = Variant::where('category_id', $id_category)
            ->leftJoin('m_item', 'm_variant.id_item', '=', 'm_item.id_item')
            ->leftJoin('m_category', 'm_item.category_id', '=', 'm_category.id_category')
            ->select(
                'm_category.category_name',
                'm_variant.id_variant',
                'm_variant.variant_name',
                'm_variant.price',
                'm_variant.sku',
                'm_variant.id_item',
                'm_item.item_name',
            )
            ->orderBy('m_item.item_name', 'asc')
            ->get();

        if (request()->expectsJson()) {
            return response()->json([
                'data' => $itemCategory
            ], 200);
        } else {
            return view('koffe.frontend.items.itemByCategory', compact('itemCategory'));
        }
    }

    public function variantItem($id_item)
    {
        $itemVariant = Variant::where('m_variant.id_item', $id_item)
            ->leftJoin('m_item', 'm_variant.id_item', '=', 'm_item.id_item')
            ->select(
                'm_item.item_name',
                'm_item.id_item as item_id',
                'm_variant.id_variant',
                'm_variant.variant_name',
                'm_variant.price',
                'm_variant.sku'
            )
            ->get();

        if (request()->expectsJson()) {
            return response()->json([
                'data' => $itemVariant
            ], 200);
        }
    }

    public function paymentOrder()
    {
        return view('koffe.frontend.payment');
    }

    public function paymentOrderAdd(Request $request)
    {
        $id_penjualan = null;
        try {
            DB::transaction(function () use ($request, &$id_penjualan) {
                $data = json_decode($request->getContent(), true);
                $id_penjualan = Generator::uuid4()->toString();
                Penjualan::updateOrCreate(
                    [
                        'id_penjualan'  => $id_penjualan,
                    ],
                    [
                        'toko_id'        => auth()->user()->toko_id,
                        'norec_user'     => auth()->user()->noregistrasi,
                        'no_nota'       => $data['dataObj']['nonota'],
                        'tgl_nota'      => $data['dataObj']['tgl_nota'],
                        'total'         => $data['dataObj']['total'],
                        'uang_bayar'    => $data['dataObj']['cash'],
                        'uang_kembali'  => $data['dataObj']['uang_kembali'],
                        'status'        => $data['dataObj']['status'],
                        'nm_pelanggan'  => $data['dataObj']['nm_pelanggan']
                    ]
                );
                foreach ($data['dataObj']['item'] as $list) {
                    PenjualanDet::updateOrCreate(
                        [
                            'id_penjualan_det'  => $request->id_penjualan_det,
                        ],
                        [
                            'id_penjualan'   => $id_penjualan,
                            'tgl_penjualan'  => $list['tgl_penjualan'],
                            'id_item'        => $list['id_item'],
                            'qty'            => $list['qty'],
                            'harga_peritem'  => $list['harga_peritem'],
                            'sub_total'      => $list['subtotal'],
                        ]
                    );
                }
            });
            return response()->json([
                'success' => true,
                'message' => 'Payment Successful',
                'id_penjualan' => $id_penjualan
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function billingPrint($id_penjualan)
    {
        $datPenjualan = DB::table('t_penjualan AS pj')
            ->join('users as usr', 'pj.norec_user', '=', 'usr.noregistrasi')
            ->select(
                'pj.no_nota',
                'pj.tgl_nota',
                'usr.nama',
                'pj.total',
                'pj.uang_bayar',
                'pj.uang_kembali',
                'pj.nm_pelanggan',
                DB::raw('CASE
                            WHEN pj.status = 1 THEN "Cash"
                            WHEN pj.status = 2 THEN "Pay Later"
                            WHEN pj.status = 3 THEN "QRIS"
                            ELSE ""
                        END AS payment_method')
            )->where('pj.id_penjualan', $id_penjualan)
            ->first();

            $result = DB::table('t_penjualan_det AS pjd')
                ->join('m_item AS itm', 'pjd.id_item', '=', 'itm.id_item')
                ->join('m_category AS ct', 'itm.category_id', '=', 'ct.id_category')
                ->select(
                    'pjd.id_penjualan_det',
                    'pjd.id_penjualan',
                    'pjd.qty',
                    'pjd.harga_peritem',
                    'pjd.sub_total',
                    'itm.item_name',
                    'itm.category_id',
                    'ct.category_name'
                )
                ->where('pjd.id_penjualan', '=', $id_penjualan)
                ->groupBy(
                    'pjd.id_penjualan_det',
                    'pjd.id_penjualan',
                    'pjd.qty',
                    'pjd.harga_peritem',
                    'pjd.sub_total',
                    'itm.item_name',
                    'itm.category_id',
                    'ct.category_name'
                )
                ->get()
                ->groupBy('category_name') // Mengelompokkan hasil berdasarkan array
                ->toArray();

        // dd($datPenjualan);

        return view('koffe.frontend.cetakan.billing', compact('datPenjualan', 'result'));
    }

    public function billingPrintHarian($tgl_transaksi)
    {
        // $datPenjualan = DB::table('t_penjualan AS pj')
        //     ->join('users as usr', 'pj.norec_user', '=', 'usr.noregistrasi')
        //     ->select(
        //         'pj.no_nota',
        //         DB::raw('DATE_FORMAT(pj.tgl_nota, "%Y-%m-%d") as tgl_nota'), // Format tgl_nota ke YYYY-MM-DD
        //         'usr.nama',
        //         'pj.total',
        //         'pj.uang_bayar',
        //         'pj.uang_kembali',
        //         DB::raw('CASE
        //                     WHEN pj.status = 1 THEN "Cash"
        //                     WHEN pj.status = 2 THEN "Pay Later"
        //                     WHEN pj.status = 3 THEN "QRIS"
        //                     ELSE ""
        //                 END AS payment_method')
        //     )
        //     ->where('pj.tgl_nota', 'LIKE', $tgl_transaksi . '%') // Tambahkan '%' untuk mencocokkan tanggal tertentu
        //     ->first();
        $dataPenjualan = DB::table('t_penjualan_det AS pjd')
            ->join('t_penjualan AS pj', 'pjd.id_penjualan', '=', 'pj.id_penjualan')
            ->join('m_item AS itm', 'pjd.id_item', '=', 'itm.id_item')
            ->leftJoin('m_category AS ct', 'itm.category_id', '=', 'ct.id_category')
            ->select(
                DB::raw("DATE_FORMAT(pj.tgl_nota, '%Y-%m-%d') AS tanggal_nota"),
                DB::raw("DATE_FORMAT(pj.tgl_nota, '%H:%i') AS jam_nota"),
                'pj.id_penjualan',
                'pj.total',
                'pj.status',
                'pj.statusenabled',
                DB::raw('AVG(pjd.harga_peritem) AS rata_rata_harga_peritem'),
                DB::raw('GROUP_CONCAT(itm.item_name) AS item_names'),
                DB::raw('GROUP_CONCAT(pj.total) AS totals')
            )
            ->groupBy('tanggal_nota', 'jam_nota', 'pj.id_penjualan')
            ->orderBy('tanggal_nota', 'desc')
            ->orderBy('jam_nota', 'asc')
            ->where('pj.statusenabled', true)
            ->having('tanggal_nota', 'LIKE', $tgl_transaksi . '%')
            ->get();

        $groupedData = [];

        foreach($dataPenjualan as $penjualan) {
            $tanggal = $penjualan->tanggal_nota;

            if (!isset($groupedData[$tanggal])) {
                $groupedData[$tanggal] = [];
            }

            $groupedData[$tanggal][] = $penjualan;
        }

        // dd($groupedData);

        if (request()->expectsJson()) {
            return response()->json([
                'data' => $groupedData
            ], 200);
        } else {
            return view('koffe.frontend.cetakan.billingHarian', compact('groupedData'));
        }
        // return response()->json([
        //     'tgl'   => $id
        // ], 200);
    }

    public function activity()
    {
        $dataPenjualan = DB::table('t_penjualan_det AS pjd')
            ->join('t_penjualan AS pj', 'pjd.id_penjualan', '=', 'pj.id_penjualan')
            ->join('m_item AS itm', 'pjd.id_item', '=', 'itm.id_item')
            ->select(
                DB::raw("DATE_FORMAT(pj.tgl_nota, '%Y-%m-%d') AS tanggal_nota"),
                DB::raw("DATE_FORMAT(pj.tgl_nota, '%H:%i') AS jam_nota"),
                'pj.id_penjualan',
                'pj.total',
                'pj.status',
                'pj.statusenabled',
                DB::raw('GROUP_CONCAT(itm.item_name) AS item_names'),
                DB::raw('GROUP_CONCAT(pj.total) AS totals')
            )
            ->groupBy('tanggal_nota', 'jam_nota', 'pj.id_penjualan')
            ->orderBy('tanggal_nota', 'desc')
            ->orderBy('jam_nota', 'asc')
            ->where('pj.statusenabled', true)
            ->get();

        $groupedData = [];

        foreach($dataPenjualan as $penjualan) {
            $tanggal = $penjualan->tanggal_nota;

            if (!isset($groupedData[$tanggal])) {
                $groupedData[$tanggal] = [];
            }

            $groupedData[$tanggal][] = $penjualan;
        }

        if (request()->expectsJson()) {
            return response()->json([
                'data' => $groupedData
            ], 200);
        } else {
            return view('koffe.frontend.activity.activityIndex', compact('groupedData'));
        }
    }

    public function activityDetail($id_penjualan)
    {
        $detPenjualan = DB::table('m_variant as vr')
            ->leftJoin('m_item as itm', 'vr.id_item', '=', 'itm.id_item')
            ->join('t_penjualan_det as pjd', 'pjd.id_item', '=', 'itm.id_item')
            ->select(
                'itm.item_name',
                'pjd.harga_peritem',
                'vr.variant_name',
            )
            ->where('pjd.id_penjualan', $id_penjualan)
            ->get();

        $dataPenj = DB::table('t_penjualan as pj')
                ->select(
                    DB::raw('CASE
                            WHEN pj.status = 1 THEN "Cash"
                            WHEN pj.status = 2 THEN "Pay Later"
                            WHEN pj.status = 3 THEN "QRIS"
                            ELSE ""
                        END AS payment_method'),
                    'pj.nm_pelanggan',
                    'pj.status',
                    'pj.no_nota',
                    'pj.tgl_nota',
                    'pj.total',
                    'pj.uang_bayar',
                    'pj.uang_kembali',
                    'pj.tgl_pembayaran'
                )
                ->where('pj.id_penjualan', $id_penjualan)
                ->first();

        if (request()->expectsJson()) {
            return response()->json([
                'dtpj' => $detPenjualan,
                'dtitm' => $dataPenj
            ], 200);
        } else {
            return view('koffe.frontend.activity.activityDetail', compact('detPenjualan', 'dataPenj'));
        }
    }

    public function changePaymentMethod(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $saveData = Penjualan::updateOrCreate(
            [
                'id_penjualan'  => $data['dataObj']['id_penjualan'],
            ],
            [
                'tgl_pembayaran'    => $data['dataObj']['tgl_pembayaran'],
                'uang_bayar'        => $data['dataObj']['cash'],
                'uang_kembali'      => $data['dataObj']['uang_kembali'],
                'status'            => $data['dataObj']['statusbayar']
            ]
        );

        if($saveData) {
            return response()->json([
                'success' => true,
                'message' => 'Change Payment Mehtod Successful',
                'id_penjualan' => $data['dataObj']['id_penjualan']
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Change Payment Method Failed'
            ], 400);
        }
    }

    public function transaksiPenjualanDelete(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $saveData = Penjualan::updateOrCreate(
            [
                'id_penjualan'  => $data['dataObj']['id_penjualan'],
            ],
            [
                'keteranganrefund'  => $data['dataObj']['keteranganrefund'],
                'statusenabled'      => 'f'
            ]
        );

        if($saveData) {
            return response()->json([
                'success' => true,
                'message' => 'Delete transaksi Successful',
                'id_penjualan' => $data['dataObj']['id_penjualan']
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Delete transaksi Failed'
            ], 400);
        }
    }

    public function pengeluaranIndex()
    {
        if (request()->ajax()) {
            $pengeluaran = Pengeluaran::select('t_pengeluaran.*')
                ->where('toko_id', auth()->user()->toko_id)
                ->get();

            return DataTables::of($pengeluaran)
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
        return view('koffe.frontend.pengeluaran.pengeluaranIndex');
    }

    public function pengeluaranAdd(Request $request)
    {
        $rules = [
            'tgl_pengeluaran'   => 'required',
            'nama_barang'       => 'required',
            'harga_barang'      => 'required',
            'jenis_pembayaran'  => 'required',
            'keterangan'        => 'required',
        ];

        $messages = [
            'tgl_pengeluaran.required' => 'Tanggal Pengeluaran required',
            'nama_barang.required' => 'Nama Barang required',
            'harga_barang.required' => 'Harga Barang required',
            'jenis_pembayaran.required' => 'Jenis Pembayaran required',
            'keterangan.required' => 'Keterangan required',
        ];
        $validasi = Validator::make($request->all(), $rules, $messages);

        if($validasi->fails()){
            return response()->json(
                ['error' => $validasi->errors()->all()
            ], 400);
        }

        $pengeluaran = Pengeluaran::create(
            [
                'toko_id'        => auth()->user()->toko_id,
                'norec_user'        => auth()->user()->noregistrasi,
                'tgl_pengeluaran'   => $request->tgl_pengeluaran,
                'nama_barang'       => $request->nama_barang,
                'harga_barang'      => $request->harga_barang,
                'jenis_pembayaran'  => $request->jenis_pembayaran,
                'keterangan'        => $request->keterangan
            ]
        );

        if($pengeluaran) {
            return response()->json([
                'success' => true,
                'message' => 'Pengeluaran added successfully'
            ], 200);
        } else {
            return response()->json([
                'success'   => true,
                'message'   => 'Pengeluaran failed to add'
            ], 400);
        }
    }

    public function setting()
    {
        return view('koffe.frontend.partials.setting');
    }

}
