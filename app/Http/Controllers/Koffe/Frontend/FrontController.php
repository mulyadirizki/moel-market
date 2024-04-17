<?php

namespace App\Http\Controllers\Koffe\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Item;
use App\Models\Variant;
use Illuminate\Support\Facades\DB;

class FrontController extends Controller
{
    public function index()
    {
        $category = Category::where('toko_id', auth()->user()->toko_id)
            ->select('m_category.*')
            ->get();
        return view('koffe.frontend.home', compact('category'));
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

}
