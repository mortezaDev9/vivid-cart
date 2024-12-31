<x-home::layouts.app title="cart">
    <div class="container grid grid-cols-12 items-start gap-6 pt-4 pb-16">
        @auth()
            <x-user::sidebar />
        @endauth

        <div id="cart-product-container" class="col-span-9 space-y-4">
            @forelse($products as $product)
                <x-product::product-card-wide :$product :quantity="$quantities[$product->id]" />
            @empty
                <div class="flex flex-col items-center justify-center text-center py-10">
                    <i class="fa-solid fa-heart-broken text-gray-400 text-4xl mb-4"></i>
                    <h2 class="text-2xl font-bold text-gray-700">Your cart is empty</h2>
                    <p class="text-gray-500 mt-2">You have not added any products to your cart yet.</p>
                    <a href="{{ route('shop') }}" class="mt-6 px-6 py-2 text-center text-sm text-white bg-primary border border-primary rounded hover:bg-transparent hover:text-primary transition duration-300 uppercase font-roboto font-medium">
                        Start Shopping
                    </a>
                </div>
            @endforelse

            @if(! $products->isEmpty())
                <div class="col-span-3 border p-6 pt-4 border-gray-200 rounded">
                    <h2 class="text-xl font-semibold mb-4">Cart Summary</h2>
                    <div class="flex justify-between border-b border-gray-200 pb-2 mb-2">
                        <span class="text-gray-800">Subtotal</span>
                        <span id="subtotal-cart-summary" class="text-primary font-semibold">${{ $subtotal }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-200 pb-2 mb-2">
                        <span class="text-gray-800">Shipping</span>
                        <span class="text-gray-500">{{ $subtotal > 1000 ? 'Free' : 'Calculated at checkout' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-200 pb-2 mb-2">
                        <span class="text-gray-800">Total</span>
                        <span id="total-cart-summary" class="text-primary font-semibold">${{ $subtotal }}</span>
                    </div>
                    <p class="text-gray-600 text-sm mb-6">Free shipping for orders more than $1000</p>
                    <a href="{{ route('checkout') }}" class="w-full px-4 py-2 text-center text-sm text-white bg-primary border border-primary rounded hover:bg-transparent hover:text-primary transition duration-300 uppercase font-roboto font-medium">Proceed to Checkout</a>
                </div>
            @endif
        </div>
    </div>
</x-home::layouts.app>
