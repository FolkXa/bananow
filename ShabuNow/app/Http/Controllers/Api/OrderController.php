<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Table;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function orderWithPrice()
    {

    }

    public function show(Order $order)
    {

    }
    public function ordersByNonStatus(string $status) {
        return Order::with('menus', 'user')->where('status', '!=', $status)
            ->orderBy('status')->orderBy('id', 'ASC')->get();
    }
    public function ordersByStatus(string $status) {
        return Order::with('menus', 'user')->where('status', $status)
            ->orderBy('status')->orderBy('id', 'ASC')->get();
    }
    public function orderByStatus(string $user_id, string $status)
    {
        return Order::with('menus')->where('user_id', $user_id)
            ->where('status', $status)->get()->last();
    }

    public function orderByNonStatus(string $user_id, string $status) {
        return Order::with('menus')->where('user_id', $user_id)
            ->where('status', '!=', $status)
            ->orderBy('status')->orderBy('id', 'DESC')->get();
    }

    public function allStore(Request $request, string $user_id) {
        $request->validate([
            'menuSender' => 'required',
            'order_id' => 'required'
        ]);
        $user = User::find($user_id);
        if ($user === null) {
            return abort(400, 'invalid user id');
        }
        $menus = $request->get('menuSender');
        $order_id = $request->get('order_id');
        $order = Order::find($order_id);
        if (!$order) {
            $order = new Order();
            $order->user_id = $user_id;
            $order->save();
            $order->refresh();
        }
        $order_id = $order->id;
        $menu_id = 0;
        foreach ($menus as $menuQuantity) {
            if ($menu_id === 0) {
                $menu_id++;
                continue;
            }
            $quantity = 0;
            if ($menuQuantity) {
                $quantity = $menuQuantity;
            }
            if (!$order->menus()->find($menu_id) && $quantity !== 0) {
                $order->menus()->attach($menu_id);
            }
            if ($quantity) {
                $order->menus()->updateExistingPivot($menu_id, ['quantity' => $quantity]);
            } else {
                $order->menus()->detach(Menu::find($menu_id));
            }
            $menu_id++;
        }
        return response()->json('store successfully');
    }

    public function addMenu(Request $request, string $user_id) { // can use as edit menu quantity
        $request->validate([
            'menu_id' => ['required'],
            'quantity' => ['required','integer','min:1','max:10'],
        ]);
        $user = User::find($user_id);
        if ($user === null) {
            return abort(400, 'invalid user id');
        }
        $menu = Menu::find($request->get('menu_id'));
        if ($menu === null) {
            return abort(400, 'invalid menu id');
        }
        $order = User::find(1)->orders()->where('status', 'pending')->get()->get(0);
        $quantity = $request->get('quantity');
        if (!$order) {
            $order = new Order();
            $order->user_id = $user_id;
            $order->save();
            $order->refresh();
        }
        if (!$order->menus()->find($menu->id)) {
            $order->menus()->attach($menu->id);
        } else {
            $quantity += $order->menus()->find($menu->id)->pivot->quantity;
        }
        $order->menus()->updateExistingPivot($menu->id, ['quantity' => $quantity]);
        return response()->json('add menu Successfully');
    }

    public function updateOrderStatus(Request $request, string $order_id) {
        $request->validate([
            'receiving_time' => 'required'
        ]);
        $order = Order::find($order_id);
        $order->status = 'pending';
        $order->receiving_time = $request->get('receiving_time');
        $order->order_date = date("Y-m-d H:i:s");
        $order->save();
        $order->refresh();
        return $order;
    }

    public function updateStatus(string $order_id ,string $status) {
        $order = Order::find($order_id);
        $order->status = $status;
        $order->save();
        $order->refresh();
        return $order;
    }

    public function getQueue() {
        return Order::where('status', 'in Queue')->get();
    }

}
