<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sale Receipt') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Receipt</h3>

                    <p><strong>Product:</strong> {{ $sale->product->name }}</p>
                    <p><strong>Quantity:</strong> {{ $sale->quantity }}</p>
                    <p><strong>Price:</strong> ${{ $sale->price }}</p>
                    <p><strong>Total:</strong> ${{ $sale->quantity * $sale->price }}</p>
                    <p><strong>Date:</strong> {{ $sale->created_at }}</p>

                    <button onclick="window.print()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">Print</button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
