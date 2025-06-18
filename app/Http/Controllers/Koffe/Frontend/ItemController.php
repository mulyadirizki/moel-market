<?php

namespace App\Http\Controllers\Koffe\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use DataTables;
use App\Models\Item;
use App\Models\Variant;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid as Generator;
use Illuminate\Database\QueryException;

class ItemController extends Controller
{
    public function createItem()
    {
        $category = Category::where('toko_id', auth()->user()->toko_id)
                        ->select('m_category.*')
                        ->where('m_category.statusenabled', 1)
                        ->get();
        return view('koffe.frontend.items.createItem', compact('category'));
    }

    public function createItemAdd(Request $request)
    {

        $success = true;
        try {
            DB::transaction(function () use ($request) {
                $dataArray = json_decode($request->getContent(), true);

                $idItem = Generator::uuid4()->toString();
                $item = Item::create([
                    'id_item'       => $idItem,
                    "toko_id"       => auth()->user()->toko_id,
                    "item_name"     => $dataArray['dataObj']['itemname'],
                    "category_id"   => $dataArray['dataObj']['category'],
                ]);

                foreach ($dataArray['dataObj']['dataArr'] as $data) {

                    if ($dataArray['dataObj']['variantname'] == true) {
                        $variant = Variant::create([
                            "id_item"       => $idItem,
                            "variant_name"  => $data['variantname'],
                            "price"         => $data['price'],
                            "sku"           => $data['sku'],
                        ]);
                    } else {
                        $variant = Variant::create([
                            "id_item"       => $idItem,
                            "variant_name"  => null,
                            "price"         => $data['price'],
                            "sku"           => $data['sku'],
                        ]);
                    }
                }
            });
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil ditambahkan'
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    
        // if (is_array($dataArray)) {
        //     foreach ($dataArray as $data) {
        //         $itemsToCreate = [];
        //         foreach ($data as $key => $value) {
        //             $idItem = Generator::uuid4()->toString();

        //             $itemData = [
        //                 'id_item'       => $idItem,
        //                 "toko_id"       => auth()->user()->toko_id,
        //                 "item_name"     => $value['itemname'],
        //                 "category_id"   => $value['category'],
        //             ];

        //             $item = Variant::create([
        //                 "id_item"       => $idItem,
        //                 "variant_name"  => $value['variantname'],
        //                 "price"         => $value['price'],
        //                 "sku"           => $value['sku'],
        //             ]);

        //             if (!$item) {
        //                 $success = false;
        //             }

        //             $itemsToCreate[] = $itemData;
        //         }
        //         Item::insert($itemsToCreate);
        //         if ($success) {
        //             return response()->json([
        //                 'success' => true,
        //                 'message' => 'Items added successfully'
        //             ], 200);
        //         } else {
        //             return response()->json([
        //                 'success' => false,
        //                 'message' => 'Failed to add items'
        //             ], 400);
        //         }
        //     }
        // }
    }
}
