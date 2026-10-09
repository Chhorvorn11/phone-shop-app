<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Support\Picture;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.categories', ['categories' => Category::withCount('phones')->orderBy('name')->get()]);
    }

    public function store(Request $r)
    {
        $d = $r->validate(['name' => 'required|max:60|unique:categories', 'description' => 'nullable|max:200', 'image' => Picture::rules()]);
        $d['image'] = Picture::handle($r, 'image', 'categories', null);
        Category::create($d);
        return back()->with('ok', 'Category added.');
    }

    public function update(Request $r, Category $category)
    {
        $d = $r->validate([
            'name' => ['required', 'max:60', Rule::unique('categories')->ignore($category->id)],
            'description' => 'nullable|max:200', 'image' => Picture::rules(),
        ]);
        $d['image'] = Picture::handle($r, 'image', 'categories', $category->image);
        $category->update($d);
        return back()->with('ok', 'Category updated.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return back()->with('ok', 'Category deleted. Its phones are now uncategorised.');
    }
}
