<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Category;

class CategoryController extends Controller
{
    //
    public function index()
    {
        $categories = Category::all();
        return view('pages.categories', compact('categories'));
    }
    public function store(Request $data)
    {
        $validator = Validator::make($data->all(), [
            'name' => ['required', 'string', 'max:255', 'unique:categories'],
        ]);
        if ($validator->fails()) {
            return response()->json(array('result' => false, 'errors' => $validator->getMessageBag()->toArray()));
        }

        $new_category = new Category ;

        $new_category->name = $data['name'] ;
        $new_category->comment = $data['comment'] ;
        $new_category->bgcolor = $data['color'] ;

        $new_category->save();

        return response()->json(array('result' => true, 'success' => 'Category Created'));
    }

    public function update(Request $request, $id)
    {
        //
        $data = $request->all();
        $category = Category::find($id);

        if ($category->name != $data['name']) {
            $temp = category::where('name', '=', $data['name'])->first();
            if ($temp) {
                return response()->json(array('result' => false, 'errors' => 'Name Duplicated'));
            }
        }

        $category->name = $data['name'];
        $category->comment = $data['comment'];
        $category->bgcolor = $data['color'] ;

        $category->save();

        return response()->json(array('result' => true, 'success' => 'Category Updated'));
    }
}
