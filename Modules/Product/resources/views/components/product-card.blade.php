@inject('productService', 'Modules\Product\Services\ProductService')
@props(['product', 'isWishlisted' => $productService->isWishlisted($product->id), 'isInCart' => $productService->isInCart($product->id)])
@php
    $isAvailable = $product->quantity > 0;
@endphp

<div class="bg-white shadow rounded overflow-hidden">
    <div class="relative group">
        <img src="{{ $product->picture }}" alt="{{ $product->title }} image" class="w-full object-cover h-48" loading="lazy">
        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-colors duration-300">
            <form id="toggle-wishlist-form" method="POST" action="{{ route('wishlist.toggle', $product->id) }}" data-product-id="{{ $product->id }}">
                @csrf

                <button id="wishlist-button-card-{{ $product->id }}" class="text-white text-lg w-9 h-8 rounded-full bg-primary flex items-center justify-center transition-colors duration-300 hover:bg-gray-800"
                        title="{{ $isWishlisted ? 'Remove from wishlist' : 'Add to wishlist' }}" aria-label="{{ $isWishlisted ? 'Remove from wishlist' : 'Add to wishlist' }}">
                    <i class="fa-solid {{ $isWishlisted ? 'fa-remove' : 'fa-heart' }}"></i>
                </button>
            </form>
        </div>
    </div>
    <div class="pt-4 pb-3 px-4">
        <a href="{{ route('products.index', $product->slug) }}">
            <h4 class="uppercase font-medium text-lg mb-2 text-gray-800 transition-colors duration-300 hover:text-primary">
                {{ Str::limit($product->title, 15) }}
            </h4>
        </a>
        <div class="flex items-baseline mb-1 space-x-2">
            <p class="text-xl text-primary font-semibold">${{ $product->price }}</p>
        </div>
        <div class="flex items-center">
            <p class="mr-2 text-lg text-gray-800">{{ number_format($product->reviews_avg_rating , 1) }}</p>
            <div class="flex gap-1 text-sm text-yellow-400">
                @for ($i = 0; $i < 5; $i++)
                    <i class="fa-solid fa-star {{ $i < round($product->reviews_avg_rating) ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                @endfor
            </div>
            <div class="text-xs text-gray-500 ml-3">({{ $product->reviews_count }})</div>
        </div>
    </div>
    <form id="toggle-cart-form" method="POST" action="{{ route('cart.toggle', $product->id) }}" data-product-id="{{ $product->id }}">
        @csrf

        <button id="cart-button-card-{{ $product->id }}"
                class="block w-full py-2 text-center text-white bg-primary border border-primary rounded-b {{ ! $isAvailable ? 'cursor-not-allowed' : 'hover:bg-transparent hover:text-primary transition duration-300' }} uppercase font-roboto font-medium"
                {{ ! $isAvailable ? 'disabled' : '' }}
        >
            {{ ! $isAvailable ? 'Unavailable' : ($isInCart ? 'Remove from Cart' : 'Add to Cart') }}
        </button>
    </form>
</div>

@pushOnce('scripts')
    @vite('Modules/Wishlist/resources/js/main.js')
    @vite('Modules/Cart/resources/js/main.js')
@endpushOnce
