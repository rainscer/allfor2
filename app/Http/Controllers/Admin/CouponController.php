<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{

    public function index()
    {

        $coupons = Coupon::orderBy('expired_at', 'desc')->paginate(15);

        return view('admin.coupon.index', compact('coupons'));

    }

    public function create()
    {
        return view('admin.coupon.create');
    }

    public function store(Request $request)
    {
        if ($request->cancel) return redirect()->route('coupon.index');

        $this->validate($request, [
            'code' => 'required|regex:/^[a-zA-Z0-9_-]+$/u|unique:coupons',
            'count' => 'required',
            'amount' => 'required',
            'expired_at' => 'required|date|after:now()'
        ], [
            'code.required' => 'Обязательное поле',
            'code.regex' => 'Только a-Z, 0-9 , _, -',
            'code.unique' => 'Такой Купон уже используется',
            'count.required' => 'Обязательное поле',
            'amount.required' => 'Обязательное поле',
            'expired_at.required' => 'Обязательное поле',
            'expired_at.after' => 'Дата должна быть позже'
        ]);

        Coupon::create([
            'code' => $request->code,
            'count' => $request->count,
            'amount' => $request->amount,
            'expired_at' => $request->expired_at
        ]);

        return redirect()->route('coupon.index');
    }

    public function show($id)
    {
        $coupon = Coupon::find($id);

        if ($coupon) return view('admin.coupon.show', compact('coupon'));

        return abort(404);
    }

    public function edit($id)
    {
        $coupon = Coupon::find($id);

        if ($coupon) return view('admin.coupon.edit', compact('coupon'));

        return abort(404);
    }

    public function update(Request $request, $id)
    {
        if ($request->cancel) return redirect()->route('coupon.index');

        $coupon = Coupon::find($id);

        if (!$coupon) return abort(404);

        $this->validate($request, [
            'code' => 'required|regex:/^[a-zA-Z0-9_-]+$/u|unique:coupons,code,' . $coupon->id,
            'count' => 'required',
            'amount' => 'required',
            'expired_at' => 'required|date|after:now()'
        ], [
            'code.required' => 'Обязательное поле',
            'code.regex' => 'Только a-Z, 0-9 , _, -',
            'code.unique' => 'Такой Купон уже используется',
            'count.required' => 'Обязательное поле',
            'amount.required' => 'Обязательное поле',
            'expired_at.required' => 'Обязательное поле',
            'expired_at.after' => 'Дата должна быть позже'
        ]);

        $coupon->update([
            'code' => $request->code,
            'count' => $request->count,
            'amount' => $request->amount,
            'expired_at' => $request->expired_at
        ]);

        return redirect()->route('coupon.index');
    }

}