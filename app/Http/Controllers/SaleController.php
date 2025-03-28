<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with('product')->get();
        return view('sales.index', compact('sales'));
    }

    public function show(Sale $sale)
    {
        return view('sales.show', compact('sale'));
    }

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

    public function downloadMonthlySales(Request $request)
    {
        $request->validate([
            'month' => 'required|date_format:Y-m',
        ]);

        $month = Carbon::parse($request->month);
        $sales = Sale::whereMonth('created_at', $month->month)
            ->whereYear('created_at', $month->year)
            ->with('product')
            ->get();

        $csv = "Product,Quantity,Price,Total,Date\n";
        foreach ($sales as $sale) {
            $csv .= $sale->product->name . "," . $sale->quantity . "," . $sale->price . "," . ($sale->quantity * $sale->price) . "," . $sale->created_at . "\n";
        }

        $filename = "sales-{$month->format('Y-m')}.csv";

        return Response::streamDownload(function () use ($csv) {
            echo $csv;
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
