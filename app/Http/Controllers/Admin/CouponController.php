<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CouponController extends Controller
{
    public function index()
    {
        return view('admin.coupons', ['coupons' => Coupon::orderByDesc('id')->get()]);
    }

    public function store(Request $r)
    {
        $r->merge(['code' => strtoupper((string) $r->code)]);
        $d = $r->validate(['code' => 'required|max:30|unique:coupons'] + $this->rules());
        Coupon::create($d + ['active' => $r->boolean('active')]);
        return back()->with('ok', 'Coupon added.');
    }

    public function update(Request $r, Coupon $coupon)
    {
        $r->merge(['code' => strtoupper((string) $r->code)]);
        $d = $r->validate(['code' => ['required', 'max:30', Rule::unique('coupons')->ignore($coupon->id)]] + $this->rules());
        $coupon->update($d + ['active' => $r->boolean('active')]);
        return back()->with('ok', 'Coupon updated.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return back()->with('ok', 'Coupon deleted.');
    }

    private function rules(): array
    {
        return ['percent' => 'required|integer|between:1,100', 'expires_at' => 'nullable|date', 'max_uses' => 'nullable|integer|min:1'];
    }
}
