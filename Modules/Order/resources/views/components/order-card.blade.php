<div id="order-{{ $order->id }}" class="flex flex-col gap-4 border border-gray-200 rounded p-4 shadow-sm">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-medium text-gray-800">Order #{{ $order->id }}</h2>
            <p class="text-sm text-gray-500">Placed on {{ $order->created_at->format('M d, Y') }}</p>
            @if(Route::currentRouteNamed('orders.index'))
                <p class="text-base
                   {{ $order->status === 'verified' ? 'text-green-500' : '' }}
                   {{ $order->status === 'shipped' ? 'text-green-500' : '' }}
                   {{ $order->status === 'pending' ? 'text-gray-400' : '' }}
                   {{ $order->status === 'declined' ? 'text-red-500' : '' }}">
                    Status: {{ ucfirst($order->status) }}
                </p>
            @else
                <p class="text-base text-red-500">
                    Status: {{ ucfirst($order->status) }}
                </p>
            @endif
        </div>
        <div class="text-primary font-semibold text-lg">
            Total: ${{ number_format($order->amount, 2) }}
        </div>
    </div>

    @foreach($order->items as $item)
        <div class="flex items-center justify-between border gap-6 p-4 border-gray-200 rounded">
            <div class="flex items-center gap-4">
                <img src="{{ $item->product->picture }}" alt="{{ $item->product->title }}" class="w-28">
                <a href="{{ route('products.index', $item->product->slug) }}">
                    <h2 class="text-gray-800 text-xl font-medium uppercase hover:text-primary transition">{{ $item->product->title }}</h2>
                    <p class="text-gray-600 text-sm">Quantity: {{ $item->quantity }}</p>
                </a>
            </div>
            <div class="text-primary text-lg font-semibold">
                ${{ round($item->price * $item->quantity, 2) }}
            </div>
        </div>
    @endforeach

    @if(Route::currentRouteNamed('orders.index'))
        <div class="flex items-center justify-between">
            @if(in_array($order->status, ['pending', 'declined']))
                <form method="POST" action="{{ route('payments.handle', $order) }}">
                    @csrf

                    <x-forms.button >Proceed to payment</x-forms.button>
                </form>
                <form method="POST" action="{{ route('orders.cancel', $order) }}">
                    @csrf
                    @method('PATCH')

                    <button type="submit" class="text-primary transition-colors duration-300 hover:text-gray-600">
                        Cancel
                    </button>
                </form>
            @endif
        </div>
    @endif
</div>
