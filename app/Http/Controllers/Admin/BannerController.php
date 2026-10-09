<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Support\Picture;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        return view('admin.banners', ['banners' => Banner::orderBy('sort_order')->orderBy('id')->get()]);
    }

    public function create()
    {
        return view('admin.banner_form', ['banner' => new Banner(['active' => true, 'sort_order' => 0])]);
    }

    public function store(Request $r)
    {
        $d = $this->validated($r, true);
        $d['image'] = Picture::handle($r, 'image', 'banners', null);
        Banner::create($d);
        return redirect()->route('admin.banners.index')->with('ok', 'Banner added.');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banner_form', compact('banner'));
    }

    public function update(Request $r, Banner $banner)
    {
        $d = $this->validated($r, false);
        $d['image'] = Picture::handle($r, 'image', 'banners', $banner->image);
        $banner->update($d);
        return redirect()->route('admin.banners.index')->with('ok', 'Banner updated.');
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();
        return redirect()->route('admin.banners.index')->with('ok', 'Banner deleted.');
    }

    private function validated(Request $r, bool $imageRequired): array
    {
        $d = $r->validate([
            'title' => 'required|max:100', 'subtitle' => 'nullable|max:200', 'link_url' => 'nullable|url:http,https|max:255',
            'sort_order' => 'nullable|integer|min:0|max:999', 'image' => Picture::rules($imageRequired),
        ]);
        unset($d['image']);
        $d['sort_order'] = $d['sort_order'] ?? 0;
        $d['active'] = $r->boolean('active');
        return $d;
    }
}
