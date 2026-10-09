<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Phone;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index()
    {
        return view('admin.purchases', ['purchases' => Purchase::with('supplier')->withCount('items')->latest('purchased_at')->latest('id')->paginate(15)]);
    }

    public function create()
    {
        return view('admin.purchase_form', ['suppliers' => Supplier::orderBy('name')->get(), 'phones' => Phone::orderBy('brand')->orderBy('name')->get()]);
    }

    /** Receiving a purchase adds the units to stock and writes the stock log. */
    public function store(Request $r)
    {
        $d = $r->validate([
            'supplier_id' => 'required|exists:suppliers,id', 'reference' => 'nullable|max:40',
            'purchased_at' => 'required|date', 'note' => 'nullable|max:200',
            'items' => 'required|array', 'items.*.phone_id' => 'nullable|exists:phones,id',
            'items.*.quantity' => 'nullable|integer|min:1', 'items.*.unit_cost' => 'nullable|integer|min:0',
        ]);

        $items = collect($d['items'])->filter(fn ($i) => ! empty($i['phone_id']) && ! empty($i['quantity']));
        if ($items->isEmpty()) {
            return back()->withErrors(['items' => 'Add at least one phone line with a quantity.'])->withInput();
        }

        DB::transaction(function () use ($d, $items) {
            $purchase = Purchase::create([
                'supplier_id' => $d['supplier_id'], 'reference' => $d['reference'] ?? null, 'purchased_at' => $d['purchased_at'],
                'note' => $d['note'] ?? null, 'total_cost' => $items->sum(fn ($i) => $i['quantity'] * ($i['unit_cost'] ?? 0)),
            ]);
            foreach ($items as $i) {
                $purchase->items()->create(['phone_id' => $i['phone_id'], 'quantity' => $i['quantity'], 'unit_cost' => $i['unit_cost'] ?? 0]);
                Phone::find($i['phone_id'])->adjustStock((int) $i['quantity'], 'purchase', 'Purchase '.($purchase->reference ?: '#'.$purchase->id));
            }
        });

        return redirect()->route('admin.purchases.index')->with('ok', 'Purchase received and stock updated.');
    }
}
