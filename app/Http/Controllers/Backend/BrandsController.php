<?php

namespace App\Http\Controllers\Backend;

use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use Image;
use File;

class BrandsController extends Controller
{
    public function index(){
        $brands = Brand::orderBy('id','desc')->get();
        return view('backend.pages.brands.index', compact('brands'));
    }
    public function create(){
        $main_brands = Brand::orderBy('id','desc')->get();

        return view('backend.pages.brands.create',compact('main_brands'));
    }
    public function store(Request $request){
        $this->validate($request, [
            'name' => 'required',
            'image' => 'nullable|image',
        ],
            [
                'name.required' => 'Please provide a Brand name ',
                'image.image' => 'Please provide a valid image with .jpg,  .png,  .gif,  .jpeg extension ',
            ]);

        $brand = new Brand();
        $brand->name = $request->name;
        $brand->description = $request->description;
        //insert image also
        if (count($request->image) > 0) {
            $image = $request->file('image');
            $img = time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('images/brands/' . $img);
            Image::make($image)->save($location);
            $brand->image = $img;
        }

        $brand->save();

        session()->flash('success', 'A new Brand has added successfully !!');
        return redirect()->route('admin.brands');

    }
    public function edit($id){
        $brand= Brand::find($id);
        if(!is_null($brand)){
            return view('backend.pages.brands.edit', compact('brand','brands'));
        }else{
            return redirect()->route('admin.brands');
        }
    }

    public function update(Request $request, $id){
        $this->validate($request, [
            'name' => 'required',
            'image' => 'nullable|image',
        ],
            [
                'name.required' => 'Please provide a category name ',
                'image.image' => 'Please provide a valid image with .jpg,  .png,  .gif,  .jpeg extension ',
            ]);

        $brand = Brand::find($id);
        $brand->name = $request->name;
        $brand->description = $request->description;
        //insert image also
        if (count($request->image) > 0) {
            //Delete the old image from folder
            if(File::exists('images/brands/'.$brand->image)){
                File::delete('images/brands/'.$brand->image);
            }
            $image = $request->file('image');
            $img = time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('images/brands/' . $img);
            Image::make($image)->save($location);
            $brand->image = $img;
        }

        $brand->save();

        session()->flash('success', 'Brand has Updated successfully !!');
        return redirect()->route('admin.brands');
    }
    public function delete($id)
    {
        $brand = Brand::find($id);
        if (!is_null($brand)) {
            // Delete category image
            if(File::exists('images/brands/'.$brand->image)){
                File::delete('images/brands/'.$brand->image);
            }
            $brand->delete();
        }
        session()->flash('success', 'Brand has deleted successfully !!');
        return back();

    }


}
