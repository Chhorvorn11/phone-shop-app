<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Phone;
use App\Support\Picture;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $r)
    {
        $phones = Phone::with('category')
            ->when($r->low, fn ($q) => $q->where('stock', '<', 5))
            ->orderBy('brand')->orderBy('name')->paginate(15)->withQueryString();
        return view('admin.stock', compact('phones'));
    }

    public function create()
    {
        return view('admin.phone_form', ['phone' => new Phone, 'categories' => Category::orderBy('name')->get()]);
    }

    public function store(Request $r)
    {
        [$data, $specs] = $this->validated($r);
        $data['image'] = Picture::handle($r, 'image', 'phones', null);
        $phone = Phone::create($data);
        if ($phone->stock > 0) $phone->logMovement($phone->stock, 'initial', 'Opening stock');
        $this->saveSpecs($phone, $specs);
        return redirect()->route('admin.stock')->with('ok', 'Phone added.');
    }

    public function edit(Phone $phone)
    {
        return view('admin.phone_form', ['phone' => $phone->load('specs'), 'categories' => Category::orderBy('name')->get()]);
    }

    public function update(Request $r, Phone $phone)
    {
        [$data, $specs] = $this->validated($r);
        $data['image'] = Picture::handle($r, 'image', 'phones', $phone->image);
        $old = $phone->stock;
        $phone->update($data);
        if ($phone->stock !== $old) $phone->logMovement($phone->stock - $old, 'adjust', 'Edited by admin');
        $this->saveSpecs($phone, $specs);
        return redirect()->route('admin.stock')->with('ok', 'Phone updated.');
    }

    public function destroy(Phone $phone)
    {
        $phone->delete();
        return redirect()->route('admin.stock')->with('ok', 'Phone deleted.');
    }

    private function validated(Request $r): array
    {
        $d = $r->validate([
            'category_id' => 'nullable|exists:categories,id',
            'brand' => 'required|max:60', 'name' => 'required|max:100', 'storage' => 'required|max:20',
            'price' => 'required|integer|min:0', 'stock' => 'required|integer|min:0',
            'description' => 'nullable|max:500', 'specs' => 'nullable|max:1000', 'image' => Picture::rules(),
        ]);
        $specs = $d['specs'] ?? null;
        unset($d['specs'], $d['image']);
        return [$d, $specs];
    }

    /** One spec per line, written as "Label: value". */
    private function saveSpecs(Phone $phone, ?string $text): void
    {
        $phone->specs()->delete();
        foreach (preg_split('/\R/', (string) $text) as $line) {
            if (! str_contains($line, ':')) continue;
            [$label, $value] = array_map('trim', explode(':', $line, 2));
            if ($label !== '' && $value !== '') $phone->specs()->create(['label' => $label, 'value' => $value]);
        }
    }
}
