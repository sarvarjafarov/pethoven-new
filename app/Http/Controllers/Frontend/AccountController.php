<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lunar\Models\Order;

class AccountController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the account dashboard
     */
    public function dashboard()
    {
        $user = auth()->user();

        // Get recent orders
        $recentOrders = Order::where('user_id', $user->id)
            ->with(['lines.purchasable.product', 'currency'])
            ->latest('placed_at')
            ->take(5)
            ->get();

        return view('frontend.account.dashboard', compact('user', 'recentOrders'));
    }

    /**
     * Show order history
     */
    public function orders()
    {
        $user = auth()->user();

        $orders = Order::where('user_id', $user->id)
            ->with(['lines.purchasable.product', 'currency'])
            ->orderBy('placed_at', 'desc')
            ->paginate(10);

        return view('frontend.account.orders', compact('orders'));
    }

    /**
     * Show order details
     */
    public function orderShow($id)
    {
        $user = auth()->user();

        $order = Order::where('user_id', $user->id)
            ->where('id', $id)
            ->with([
                'lines.purchasable.product',
                'shippingAddress',
                'billingAddress',
                'transactions',
                'currency'
            ])
            ->firstOrFail();

        return view('frontend.account.order-detail', compact('order'));
    }

    /**
     * Show profile settings
     */
    public function profile()
    {
        $user = auth()->user();

        return view('frontend.account.profile', compact('user'));
    }

    /**
     * Update profile
     */
    public function profileUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
        ]);

        $user = auth()->user();
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('account.profile')
            ->with('success', 'Profile updated successfully');
    }
}
