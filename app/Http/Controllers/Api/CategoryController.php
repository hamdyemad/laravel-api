<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\File;
use App\Traits\Res;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Facades\JWTAuth;

class CategoryController extends Controller
{

    use Res,File;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $default = 10;
        if(request('paginate')) {
            $default = request('paginate');
        }
        $categories = Category::paginate($default);
        if(request('name')) {
            $categories = Category::where('name', 'like',request('name') . '%')->latest()->paginate($default);
        }
        if(request('status') == 0 || request('status') == 1) {
            $categories = Category::where('status', '=', request('status'))->latest()->paginate($default);
        }
        if(request('name') && request('status')) {
            $categories = Category::
            where('name', 'like', request('name') . '%')
            ->where('status', '=', request('status'))->latest()->paginate($default);
        }
        return $this->sendRes('', true, [$categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|unique:categories,name',
            'image' => 'required|file'
        ]);
        if($validator->fails()) {
            return $this->sendRes($validator->errors(), false);
        } else {
            Category::create([
                'name' => $request->name,
                'showing_number' => $request->showing_number,
                'image' => $this->uploadFile($request, $this->categoriesPath, 'image')
            ]);
            return $this->sendRes('category added successfully!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return $this->sendRes('',true, $category);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category, Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => ['required', Rule::unique('categories', 'name')->ignore($category->id)],
            'image' => 'file'
        ]);
        if($validator->fails()) {
            return $this->sendRes($validator->errors(), false);
        } else {
            $updatedArr = [
                'name' => $request->name,
                'showing_number' => $request->showing_number
            ];
            if($request->has('image')) {
                if(file_exists(public_path($category->image))) {
                    unlink($category->image);
                }
                $updatedArr['image'] = $this->uploadFile($request, $this->categoriesPath, 'image');
            }
            $category->update($updatedArr);
            return $this->sendRes('category updated successfully!');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if(file_exists($category->image)) {
            unlink($category->image);
        }
        Category::destroy($category->id);
        return $this->sendRes($category->name . ' deleted successfully!');
    }
}
