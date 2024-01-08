<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryReource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $category = Category::all();
        return new CategoryCollection($category);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $category = Category::create([
            'name' => $request->name
        ]);

        return new CategoryReource(true, "Create Category Berhasil", $category);
    }

    public function show($id)
    {
        $category = Category::find($id);

        return new CategoryReource(true, "detail category", $category);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $category = Category::find($id);
        $category->update([
            'name' => $request->name
        ]);

        return new CategoryReource(true, "Update category berhasil", $category);
    }

    public function destroy($id)
    {
        Category::find($id)->delete();

        return new CategoryReource(true, "Hapus category berhasil", null);
    }
}
