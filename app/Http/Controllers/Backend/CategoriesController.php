<?php

namespace App\Http\Controllers\Backend;

use App\Models\ProductImage;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Image;
use File;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('id', 'desc')->get();

        return view('backend.pages.categories.index', compact('categories'));
    }

    public function create()
    {
        $main_categories = Category::query()
            ->orderBy('name', 'desc')
            ->where('parent_id', NULL)
            ->get();

        return view('backend.pages.categories.create', compact('main_categories'));
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'name'  => 'required',
            'image' => 'nullable|image',
        ],
            [
                'name.required' => 'Please provide a category name ',
                'image.image'   => 'Please provide a valid image with .jpg,  .png,  .gif,  .jpeg extension ',
            ]);

        $category              = new Category();
        $category->name        = $request->input('name');
        $category->description = $request->input('description');
        $category->parent_id   = $request->input('parent_id');
        //insert image also

        if ($request->image) {
            $image    = $request->file('image');
            $img      = time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('images/categories/' . $img);
            Image::make($image)->save($location);
            $category->image = $img;
        }

        $category->save();

        session()->flash('success', 'A new Category has added successfully !!');

        return redirect()->route('admin.categories');
    }

    public function edit($id)
    {
        $main_categories = Category::orderBy('name', 'desc')
            ->where('parent_id', NULL)
            ->get();

        $category = Category::find($id);

        if (!is_null($category)) {
            return view('backend.pages.categories.edit', compact('category', 'main_categories'));
        } else {
            return redirect()->route('admin.categories');
        }
    }

    public function update(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'name'  => 'required',
            'image' => 'nullable|image',
        ],
            [
                'name.required' => 'Please provide a category name ',
                'image.image'   => 'Please provide a valid image with .jpg,  .png,  .gif,  .jpeg extension ',
            ]);

        $category              = Category::find($id);
        $category->name        = $request->input('name');
        $category->description = $request->input('description');
        $category->parent_id   = $request->input('parent_id');

        //insert image also
        if ($request->image) {
            //Delete the old image from folder
            if (File::exists('images/categories/' . $category->image)) {
                File::delete('images/categories/' . $category->image);
            }

            $image    = $request->file('image');
            $img      = time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('images/categories/' . $img);
            Image::make($image)->save($location);
            $category->image = $img;
        }

        $category->save();

        session()->flash('success', 'Category has Updated successfully !!');

        return redirect()->route('admin.categories');
    }

    /**
     * @throws Exception
     */
    public function delete($id): \Illuminate\Http\RedirectResponse
    {
        $category = Category::find($id);
        if (!is_null($category)) {
            // If is parent category, then delete all its sub category
            if ($category->parent_id == NULL) {
                //Delete sub category
                $sub_categories = Category::orderBy('name', 'desc')
                    ->where('parent_id', $category->id)
                    ->get();

                foreach ($sub_categories as $sub) {
                    // Delete category image
                    if (File::exists('images/categories/' . $sub->image)) {
                        File::delete('images/categories/' . $sub->image);
                    }
                    $sub->delete();
                }
            }
            // Delete category image
            if (File::exists('images/categories/' . $category->image)) {
                File::delete('images/categories/' . $category->image);
            }

            $category->delete();
        }

        session()->flash('success', 'Category has deleted successfully !!');

        return back();
    }
}
