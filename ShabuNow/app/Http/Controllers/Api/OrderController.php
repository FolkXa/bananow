<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function orderWithPrice()
    {
        $orderWithPrice = DB::table('menus')
            ->join('orders', 'orders.menu_id', '=', 'menus.id')
            ->get();
        return $orderWithPrice;
    }

    public function show(Order $order)
    {
        $orderWithPrice = $this->orderWithPrice();
        $orders = $orderWithPrice->where('orders.id', '=' , $order->id);
        return $orders;
    }
    public function index(Table $table)
    {
        $orders = $this->orderWithPrice();
        $orders = $orders->where('table_id', '=' , $table->id);
        return $orders;
    }

    public function checkPending(Table $table)
    {
        $orders = $this->orderWithPrice();
        $orders = $orders->where('table_id', '=' , $table->id);
        $orders = $orders->where('status', '=' , 'pending');
        return $orders;
    }

    public function sendOrders(Table $table)
    {
        $orders = Order::where('table_id', '=' , $table->id)->get();
        $orders = $orders->where('table_id', '=' , $table->id);
        $orders = $orders->where('status', '=' , 'pending');
        foreach ($orders as $order)
        {
            $order->status = 'ordered';
            $order->save();
        }

        $orders = $this->orderWithPrice();
        $orders = $orders->where('table_id', '=' , $table->id);
        return $orders;
    }

    public function store(Request $request, Table $table)
    {
        $request->validate([
                'menu_id' => ['required'],
                'quantity' => ['required','integer','min:1','max:100'],
            ]);

        $order = new Order();

        $order->menu_id = $request->get('menu_id');
        $order->quantity = $request->get('quantity');
        $menu = Menu::find($request->get('menu_id'));
        $order->name = $menu->name;
        $order->table_id = $table->id;
        $order->status = 'pending';

        $order->save();
        $order->refresh();

        return $order;
    }

    public function addMenu(Request $request, string $table_id) { // can use as edit menu quantity
        $request->validate([
            'menu_id' => ['required'],
            'quantity' => ['required','integer','min:1','max:10'],
        ]);

        $table = Table::find($table_id);
        if ($table === null) {
            return abort(400, 'invalid table id');
        }

        $menu = Menu::find($request->get('menu_id'));
        if ($menu === null) {
            return abort(400, 'invalid menu id');
        }
        $order = null;
        $quantity = $request->get('quantity');
        if ($table->status === 'available') {
            $order = new Order();
            $order->status = 'pending';
            $order->table_id = $table->id;
            $order->save();
        } else {
            $order = Order::where('table_id', $table->id)->get()->last();
        }

        if (!$order->menus()->find($menu->id)) {
            $order->menus()->attach($menu->id);
        } else {
            $quantity += $order->menus()->find($menu->id)->pivot->quantity;
        }
        $order->menus()->updateExistingPivot($menu->id, ['quantity' => $quantity]);
        return response()->json('add menu Successfully');
    }

    public function getOrderByTableId(string $table_id) {
        return Order::with('menus')->where('table_id', $table_id)->get()->last();
    }

}
