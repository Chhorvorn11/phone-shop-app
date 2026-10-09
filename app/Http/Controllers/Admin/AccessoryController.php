<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accessory;
use App\Support\Picture;
use Illuminate\Http\Request;

class AccessoryController extends Controller
{
    public function index()
    {
        return view('admin.accessories', ['items' => Accessory::orderBy('name')->paginate(12)]);
    }

    public function create()
    {
        return view('admin.accessory_form', ['item' => new Accessory, 'types' => Accessory::TYPES]);
    }

    public function store(Request $r)
    {
        $d = $this->validated($r);
        $d['image'] = Picture::handle($r, 'image', 'accessories', null);
        Accessory::create($d);
        return redirect()->route('admin.accessories.index')->with('ok', 'Accessory added.');
    }

    public function edit(Accessory $accessory)
    {
        return view('admin.accessory_form', ['item' => $accessory, 'types' => Accessory::TYPES]);
    }

    public function update(Request $r, Accessory $accessory)
    {
        $d = $this->validated($r);
        $d['image'] = Picture::handle($r, 'image', 'accessories', $accessory->image);
        $accessory->update($d);
        return redirect()->route('admin.accessories.index')->with('ok', 'Accessory updated.');
    }

    public function destroy(Accessory $accessory)
    {
        $accessory->delete();
        return redirect()->route('admin.accessories.index')->with('ok', 'Accessory deleted.');
    }

    private function validated(Request $r): array
    {
        $d = $r->validate([
            'name' => 'required|max:100', 'type' => 'required|in:'.implode(',', Accessory::TYPES),
            'price' => 'required|integer|min:0', 'stock' => 'required|integer|min:0',
            'description' => 'nullable|max:500', 'image' => Picture::rules(),
        ]);
        unset($d['image']);
        return $d;
    }
}
