<?php
namespace App\Http\Controllers;

use App\Models\Accessory;
use Illuminate\Http\Request;

class AccessoryController extends Controller
{
    public function index(Request $r)
    {
        $items = Accessory::query()
            ->when($r->type, fn ($q, $t) => $q->where('type', $t))
            ->when($r->q, fn ($q, $s) => $q->where('name', 'like', "%$s%"))
            ->orderBy('name')->paginate(12)->withQueryString();
        return view('accessories', ['items' => $items, 'types' => Accessory::TYPES]);
    }
}
