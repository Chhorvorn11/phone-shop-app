<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        return view('admin.suppliers', ['suppliers' => Supplier::withCount('purchases')->orderBy('name')->get()]);
    }

    public function store(Request $r)
    {
        Supplier::create($this->rules($r));
        return back()->with('ok', 'Supplier added.');
    }

    public function update(Request $r, Supplier $supplier)
    {
        $supplier->update($this->rules($r));
        return back()->with('ok', 'Supplier updated.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return back()->with('ok', 'Supplier deleted.');
    }

    private function rules(Request $r): array
    {
        return $r->validate(['name' => 'required|max:100', 'contact_name' => 'nullable|max:100', 'email' => 'nullable|email|max:150', 'phone' => 'nullable|max:30', 'address' => 'nullable|max:200']);
    }
}
