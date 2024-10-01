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
use Yajra\DataTables\DataTables;

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
                            'id_variant'     => $list['id_variant'],
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
        $dataPenjualan = DB::table('t_penjualan')
            ->select(DB::raw('DATE(tgl_nota) AS tgl_nota'), DB::raw('SUM(total) AS total_penjualan'), 'statusenabled')
            ->selectRaw("CASE `status`
                                WHEN 1 THEN 'Cash'
                                WHEN 2 THEN 'Pay Later'
                                WHEN 3 THEN 'QRIS'
                                ELSE 'Unknown'
                            END AS payment_method")
            ->whereDate('tgl_nota', $tgl_transaksi)
            ->where('statusenabled', true)
            ->groupBy(DB::raw('DATE(tgl_nota)'), 'status', 'statusenabled')
            ->get();

            // $results = DB::table('t_penjualan_det AS pjd')
            //     ->join('m_item AS itm', 'pjd.id_item', '=', 'itm.id_item')
            //     ->join('m_category AS ct', 'itm.category_id', '=', 'ct.id_category')
            //     ->leftJoin('m_variant', 'itm.id_item', '=', 'm_variant.id_item')
            //     ->select(
            //         'pjd.id_penjualan_det',
            //         'pjd.id_penjualan',
            //         'pjd.qty',
            //         'pjd.harga_peritem',
            //         'pjd.sub_total',
            //         'itm.item_name',
            //         'itm.category_id',
            //         'ct.category_name',
            //         DB::raw('DATE(pjd.tgl_penjualan) AS tgl_penjualan'),
            //         'm_variant.variant_name'
            //     )
            //     ->whereDate('tgl_penjualan', $tgl_transaksi)
            //     ->groupBy(
            //         'pjd.id_penjualan_det',
            //         'pjd.id_penjualan',
            //         'pjd.qty',
            //         'pjd.harga_peritem',
            //         'pjd.sub_total',
            //         'itm.item_name',
            //         'itm.category_id',
            //         'ct.category_name',
            //         'tgl_penjualan',
            //         'm_variant.variant_name'
            //     )
            //     ->get()
            //     ->groupBy('category_name') // Mengelompokkan hasil berdasarkan array
            //     ->toArray();
            //     return $results;

                $results = DB::table('t_penjualan_det AS pjd')
                ->select(
                    'itm.item_name',
                    'mv.variant_name',
                    DB::raw('SUM(pjd.qty) AS total_qty'),
                    'pjd.harga_peritem',
                    DB::raw('SUM(pjd.sub_total) AS total_sub_total'),
                    'ct.category_name'
                )
                ->leftJoin('m_item AS itm', 'pjd.id_item', '=', 'itm.id_item')
                ->join('m_category AS ct', 'itm.category_id', '=', 'ct.id_category')
                ->leftJoin('m_variant AS mv', 'itm.id_item', '=', 'mv.id_item')
                ->whereDate('pjd.tgl_penjualan', '=', '2024-05-02')
                ->groupBy('itm.item_name', 'mv.variant_name', 'pjd.harga_peritem', 'ct.category_name')
                ->orderBy('itm.item_name')
                ->orderBy('mv.variant_name')
                ->orderBy('pjd.harga_peritem')
                ->orderBy('total_sub_total')
                ->orderBy('ct.category_name')
                ->get()
                ->groupBy('category_name')
                ->toArray();
                // return $results;

        $formattedPenjualan = [];

        foreach ($dataPenjualan as $result) {
            if (!isset($formattedPenjualan[$result->tgl_nota])) {
                $formattedPenjualan[$result->tgl_nota] = [];
            }
            $formattedPenjualan[$result->tgl_nota][] = [
                'payment_method' => $result->payment_method,
                'total_penjualan' => $result->total_penjualan
            ];
        }

        $dataPembelian = DB::table('t_pengeluaran')
            ->select(DB::raw('DATE(tgl_pengeluaran) AS tgl_pengeluaran'), 'nama_barang', 'jenis_pembayaran', 'harga_barang')
            ->whereDate('tgl_pengeluaran', $tgl_transaksi)
            ->where('statusenabled', 1)
            ->where('jenis_pembayaran', 1)
            ->get();

        $formattedPembelian = [];

        foreach ($dataPembelian as $result) {
            if (!isset($formattedPembelian[$result->tgl_pengeluaran])) {
                $formattedPembelian[$result->tgl_pengeluaran] = [];
            }
            $formattedPembelian[$result->tgl_pengeluaran][] = [
                'nama_barang' => $result->nama_barang,
                'jenis_pembayaran' => $result->jenis_pembayaran,
                'harga_barang' => $result->harga_barang
            ];
        }

        $totalIncome = 0;
        foreach ($dataPenjualan as $result) {
            if ($result->payment_method === 'Cash') {
                $totalIncome += $result->total_penjualan;
            }
        }

        $totalPurchases = 0;
        foreach ($dataPembelian as $result) {
            $totalPurchases += $result->harga_barang;
        }

        $difference = $totalIncome - $totalPurchases;

        // dd($difference);

        if (request()->expectsJson()) {
            return response()->json([
                'data' => $formattedPenjualan
            ], 200);
        } else {
            return view('koffe.frontend.cetakan.billingHarian', compact('formattedPenjualan', 'formattedPembelian', 'difference', 'results'));
        }
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
        // Query 1: Mengambil detail penjualan dengan varian yang benar
        $datPenjualan = DB::table('t_penjualan_det as pjd')
            ->leftJoin('m_item as itm', 'pjd.id_item', '=', 'itm.id_item')
            ->leftJoin('m_variant as vr', function($join) {
                $join->on('pjd.id_variant', '=', 'vr.id_variant')  // Sesuaikan id_variant di t_penjualan_det dengan m_variant
                    ->on('vr.id_item', '=', 'itm.id_item');        // Pastikan varian juga sesuai dengan item
            })
            ->select('itm.item_name', 'pjd.harga_peritem', 'vr.variant_name')
            ->where('pjd.id_penjualan', $id_penjualan)
            ->get();

        // Query 2: Menampilkan result dan mengelompokkan data berdasarkan kategori
        $result = DB::table('t_penjualan_det AS pjd')
            ->join('m_item AS itm', 'pjd.id_item', '=', 'itm.id_item')
            ->join('m_category AS ct', 'itm.category_id', '=', 'ct.id_category')
            ->leftJoin('m_variant as vr', function($join) {
                $join->on('pjd.id_variant', '=', 'vr.id_variant')  // Filter varian sesuai dengan item
                    ->on('vr.id_item', '=', 'itm.id_item');       // Tambahkan kondisi agar varian yang tepat muncul
            })
            ->select(
                'pjd.id_penjualan_det',
                'pjd.id_penjualan',
                'pjd.qty',
                'pjd.harga_peritem',
                'pjd.sub_total',
                'itm.item_name',
                'itm.category_id',
                'ct.category_name',
                'vr.variant_name'
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
                'ct.category_name',
                'vr.variant_name'
            )
            ->get()
            ->groupBy('category_name') // Mengelompokkan hasil berdasarkan nama kategori
            ->toArray();

        // Query 3: Mengambil detail transaksi penjualan
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

        // Mengembalikan respon JSON jika request berupa JSON, atau mengarahkan ke view
        if (request()->expectsJson()) {
            return response()->json([
                'dtitm' => $dataPenj,
                'result' => $result
            ], 200);
        } else {
            return view('koffe.frontend.activity.activityDetail', compact('datPenjualan', 'dataPenj', 'result'));
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
                ->where('statusenabled', 1)
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

        $pengeluaran = Pengeluaran::updateOrCreate(
            [
                'id_pengeluaran'   => $request->id_pengeluaran,
            ],
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

    public function pengeluaranGetById($id_pengeluaran) {
        $pengeluaran = Pengeluaran::where('id_pengeluaran', $id_pengeluaran)->first();

        return response()->json([
            'success' => true,
            'data'    => $pengeluaran
        ], 200);
    }

    public function pengeluaranDelete($id_pengeluaran) {
        // Cari barang berdasarkan ID
        $pengeluaran = Pengeluaran::where('id_pengeluaran', $id_pengeluaran)->first();

        // Cek apakah barang ditemukan
        if (!$pengeluaran) {
            return response()->json([
                'success' => false,
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }

        // Update statusenabled menjadi 0
        $pengeluaran->statusenabled = 0;
        $pengeluaran->norec_user = auth()->user()->noregistrasi;
        $pengeluaran->save();

        // Mengembalikan response JSON
        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil dinonaktifkan'
        ], 200);
    }

    public function setting()
    {
        return view('koffe.frontend.partials.setting');
    }

}
