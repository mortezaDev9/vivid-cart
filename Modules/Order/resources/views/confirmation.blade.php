<x-home::layouts.raw title="Order Confirmation">
    <div class="container mx-auto max-w-4xl text-center py-16">
        <div class="bg-white shadow-md rounded-lg p-6">
            <i class="fa-solid fa-check-circle text-green-500 text-6xl mb-4"></i>
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Thank You for Your Order!</h1>
            <p class="text-lg text-gray-600 mb-8">Your order has been successfully placed and is being processed.</p>

            <div class="mb-8 text-center">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Order Summary</h2>
                <p class="text-gray-700"><strong>Order Number:</strong> #{{ $order->id }}</p>
                <p class="text-gray-700"><strong>Total Amount:</strong> ${{ number_format($order->amount, 2) }}</p>
                <p class="text-gray-700"><strong>Placed On:</strong> {{ $order->created_at->format('M d, Y') }}</p>
            </div>

            <form method="POST" action="{{ route('payments.handle', $order) }}">
                @csrf

                <button class="inline-block px-8 py-3 bg-primary text-white font-semibold rounded-lg hover:bg-primary-dark border border-primary transition-colors duration-300 hover:bg-transparent hover:text-primary">
                    Proceed to Payment
                </button>
            </form>
            <p class="mt-6 text-sm text-gray-500">You will be redirected to the payment page to complete your purchase.</p>
        </div>
    </div>
</x-home::layouts.raw>
