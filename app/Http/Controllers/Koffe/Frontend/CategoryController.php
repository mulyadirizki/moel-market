<?php

namespace App\Http\Controllers\Koffe\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use DataTables;

class CategoryController extends Controller
{
    public function manageCategory()
    {
        if (request()->ajax()) {
            $category = Category::select('m_category.*')
                ->where('id_category', '<>', 1)
                ->where('statusenabled', 1)
                ->get();

            return DataTables::of($category)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $btn = '<li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Edit">
                                <a href="javascript:void(0)" class="avtar avtar-xs btn-link-primary btn-edit" data-toggle="tooltip"  dataId="'. $row->id_category .'">
                                    <i class="ti ti-edit-circle f-18"></i>
                                </a>
                            </li>';

                    $btn = $btn.'<li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Delete">
                                    <a href="javascript:void(0)" class="avtar avtar-xs btn-link-danger btn-delete" data-toggle="tooltip"  dataId="'. $row->id_category .'">
                                        <i class="ti ti-trash f-18"></i>
                                    </a>
                                </li>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make();
        }
        return view('koffe.frontend.category.categoryIndex');
    }

    public function manageCategoryAdd(Request $request)
    {
        $rules = [
            'categoryname' => 'required'
        ];

        $messages = [
            'categoryname.required' => 'Category Name required'
        ];
        $validasi = Validator::make($request->all(), $rules, $messages);

        if($validasi->fails()){
            return response()->json(
                ['error' => $validasi->errors()->all()
            ], 400);
        }

        $categoryname_exists = Category::where('category_name', $request->categryname)->exists();

        if ($categoryname_exists) {
            return response()->json([
                'success'   => false,
                'message'   => 'Category Name already exists'
            ], 422);
        }

        $data = Category::create(
            [
                'category_name'  => $request->categoryname,
                'toko_id'    => auth()->user()->toko_id
            ]
        );

        if($data) {
            return response()->json([
                'success' => true,
                'message' => 'Category added successfully'
            ], 200);
        } else {
            return response()->json([
                'success'   => true,
                'message'   => 'Category failed to add'
            ], 400);
        }
    }

    public function manageCategoryDelete($id)
    {
        $category = Category::find($id);

        if ($category) {
            // Update statusenabled menjadi 0, bukan delete
            $category->statusenabled = 0;
            $category->save();

            return response()->json([
                'success' => true,
                'message' => 'Data updated successfully (statusenabled = 0)',
                'data'    => $category
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Data not found'
        ], 404);
    }
}
