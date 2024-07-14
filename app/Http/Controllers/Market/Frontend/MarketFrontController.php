<?php

namespace App\Http\Controllers\Market\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;

class MarketFrontController extends Controller
{
    public function index() {
        return view('market.frontend.home-kasir-market');
    }

    public function getDataBarang(Request $request) {
        $query = Barang::with('totalStok')
            ->select('id_barang', 'nama_barang')
            ->where('toko_id', auth()->user()->toko_id);

        if ($request->has('q') && !empty($request->q)) {
            $cari = $request->q;
            $query->where('nama_barang', 'LIKE', '%' . $cari . '%');
        }

        $barang = $query->limit(10)->get()->map(function ($item) {
            return [
                'id_barang' => $item->id_barang,
                'nama_barang' => $item->nama_barang,
                'total_stok' => $item->totalStok->total_stok ?? 0,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $barang
        ], 200);
    }

    public function getDataBarangById($id) {
        $data = Barang::with('satuan')
            ->select('id_barang', 'nama_barang', 'kode_barcode', 'harga_jual_default', 'id_satuan')
            ->where('id_barang', $id)
            ->where('toko_id', auth()->user()->toko_id)
            ->first();

        if ($data) {
            return response()->json([
                'data' => [
                    'id_barang'          => $data->id_barang,
                    'nama_barang'        => $data->nama_barang,
                    'kode_barcode'       => $data->kode_barcode,
                    'harga_jual_default' => $data->harga_jual_default,
                    'desc_satuan'        => optional($data->satuan)->desc_satuan,
                ]
            ], 200);
        } else {
            return response()->json([
                'data' => null
            ], 404);
        }
    }
}
