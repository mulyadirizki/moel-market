<?php

namespace App\Http\Controllers\Koffe\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Item;
use App\Models\Variant;
use App\Models\Penjualan;
use App\Models\PenjualanDet;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid as Generator;

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
        try {
            DB::transaction(function () use ($request) {
                $data = json_decode($request->getContent(), true);

                $id_penjualan = Generator::uuid4()->toString();
                Penjualan::updateOrCreate(
                    [
                        'id_penjualan'  => $id_penjualan,
                    ],
                    [
                         'toko_id'        => auth()->user()->noregistrasi,
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
                'message' => 'Payment Successful'
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

}
