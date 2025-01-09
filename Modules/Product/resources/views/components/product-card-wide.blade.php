@inject('productService', 'Modules\Product\Services\ProductService')
@props([
    'product',
    'quantity'     => null,
    'isWishlisted' => $productService->isWishlisted($product->id),
    'isInCart'     => $productService->isInCart($product->id)
])
@php
    $isAvailable = $product->quantity > 0;
@endphp

<div id="product-{{ $product->id }}" class="flex items-center justify-between border gap-6 p-4 border-gray-200 rounded">
    <div class="w-28">
        <img src="{{ $product->picture }}" alt="{{ $product->title }}" class="w-full">
    </div>
    <div class="w-1/3">
        <a href="{{ route('products.index', $product->slug) }}">
            <h2 class="text-gray-800 text-xl font-medium uppercase hover:text-primary transition">{{ $product->title }}</h2>
        </a>
        <p class="text-sm text-green-600">In Stock: {{ $product->quantity }}</p>
    </div>
    <div class="text-primary text-lg font-semibold">${{ round($product->price) }}</div>
    @if(Route::currentRouteNamed('cart.index'))
        <form id="update-quantity-cart-form" method="POST" action="{{ route('cart.update-quantity', $product) }}" data-product-id="{{ $product->id }}">
            @csrf
            @method('PATCH')

            <label for="quantity-input-number" class="sr-only">Quantity</label>
            <input type="number" min="1" max="{{ $product->quantity }}" id="quantity-input-number" class="w-16 border-gray-300 border rounded p-2 mr-2" name="quantity" value="{{ $quantity }}">
            <button class="px-6 py-2 text-center text-sm text-white bg-primary border border-primary rounded hover:bg-transparent hover:text-primary transition duration-300 uppercase font-roboto font-medium">
                Update Quantity
            </button>
        </form>
    @endif

    @unless(Route::currentRouteNamed('cart.index'))
        <form id="toggle-cart-form" method="POST" action="{{ route('cart.toggle', $product->id) }}" data-product-id="{{ $product->id }}">
            @csrf

            <button id="cart-button-card-{{ $product->id }}"
                    class="px-6 py-2 text-center text-sm text-white bg-primary border border-primary rounded {{ ! $isAvailable ? 'cursor-not-allowed' : 'hover:bg-transparent hover:text-primary transition duration-300' }} uppercase font-roboto font-medium"
                    {{ ! $isAvailable ? 'disabled' : '' }}
            >
                {{ ! $isAvailable ? 'Unavailable' : ($isInCart ? 'Remove from Cart' : 'Add to Cart') }}
            </button>
        </form>
    @endunless

    <div class="text-gray-600 cursor-pointer hover:text-primary">
        @if(Route::currentRouteName() === 'wishlist.index' || Route::currentRouteName() === 'cart.index')
            <form
                id="{{ Route::currentRouteName() === 'wishlist.index' ? 'remove-wishlist-form' : 'remove-cart-form' }}"
                method="POST"
                action="{{ route(Route::currentRouteName() === 'wishlist.index' ? 'wishlist.remove' : 'cart.remove', $product->id) }}"
                data-product-id="{{ $product->id }}"
            >
                @method('DELETE')
                @csrf
                <button title="{{ Route::currentRouteName() === 'wishlist.index' ? 'Remove from wishlist' : 'Remove from cart'}}" aria-label="{{ Route::currentRouteName() === 'wishlist.index' ? 'Remove from wishlist' : 'Remove from cart'}}">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </form>
        @endif
    </div>
</div>

@pushOnce('scripts')
    @vite('Modules/Wishlist/resources/js/main.js')
    @vite('Modules/Cart/resources/js/main.js')
@endpushOnce
