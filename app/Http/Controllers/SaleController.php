<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function create()
    {
        $products = Product::where('user_id', auth()->id())->get();
        return view('sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($request->product_id);

        if ($product->quantity < $request->quantity) {
            return back()->withErrors(['quantity' => 'Insufficient stock.']);
        }

        Sale::create([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'price' => $product->price,
        ]);

        $product->decrement('quantity', $request->quantity);

        return redirect()->route('products.index')->with('success', 'Sale recorded.');
    }
    // Add sales view methods here
}
