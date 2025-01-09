<x-home::layouts.app title="wishlist">
    <div class="container grid grid-cols-12 items-start gap-6 pt-4 pb-16">
        @auth()
            <x-user::sidebar />
        @endauth

        <div id="wishlist-product-container" class="col-span-9 space-y-4">
            @forelse($products as $product)
                <x-product::product-card-wide :$product />
            @empty
                <div class="flex flex-col items-center justify-center text-center py-10">
                    <i class="fa-solid fa-heart-broken text-gray-400 text-4xl mb-4"></i>
                    <h2 class="text-2xl font-bold text-gray-700">Your wishlist is empty</h2>
                    <p class="text-gray-500 mt-2">You have not added any products to your wishlist yet.</p>
                    <a href="{{ route('shop') }}" class="mt-6 px-6 py-2 text-center text-sm text-white bg-primary border border-primary rounded hover:bg-transparent hover:text-primary transition duration-300 uppercase font-roboto font-medium">
                        Start Shopping
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</x-home::layouts.app>
