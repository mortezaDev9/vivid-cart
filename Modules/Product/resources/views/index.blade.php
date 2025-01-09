@inject('productService', 'Modules\Product\Services\ProductService')
@props(['isWishlisted' => $productService->isWishlisted($product->id), 'isInCart' => $productService->isInCart($product->id)])

<x-home::layouts.app title="product">
    <div class="container grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <img id="product-detail-main-image" src="{{ $product->picture }}" alt="{{ $product->title }} image" class="w-full h-64 rounded md:h-80 object-cover">
            <div class="grid grid-cols-5 gap-4 mt-4">
                <img id="product-detail-thumbnail"
                     src="{{ $product->picture }}" alt="{{ $product->title }} image"
                     class="w-full cursor-pointer rounded border border-primary"
                     data-image-source="{{ $product->picture }}">
                @forelse($product->images as $image)
                    <img id="product-detail-thumbnail"
                         src="{{ $image->image }}" alt="{{ $product->title }} image"
                         class="w-full cursor-pointer rounded border"
                         data-image-source="{{ $image->image }}">
                @empty
                    <div class="col-span-full text-center text-gray-500">
                        No additional images available for this product.
                    </div>
                @endforelse
            </div>
        </div>

        <div>
            <h2 class="text-3xl font-medium uppercase mb-2">{{ $product->title }}</h2>
            <div class="flex items-center mb-4">
                <p class="mr-2">{{ number_format($product->reviews_avg_rating, 1) }}</p>
                <div class="flex gap-1 text-sm text-yellow-400">
                    <span><i class="fa-solid fa-star"></i></span>
                </div>
                <div class="text-xs text-gray-500 ml-3">({{ $product->reviews_count }} Reviews)</div>
            </div>
            <div class="space-y-2">
                <p class="text-gray-800 font-semibold space-x-2">
                    <span>Availability: </span>
                    <span class="text-green-600">In Stock</span>
                </p>
                <p class="space-x-2">
                    <span class="text-gray-800 font-semibold">Brand: </span>
                    <span class="text-gray-600">Apex</span>
                </p>
                <p class="space-x-2">
                    <span class="text-gray-800 font-semibold">Category: </span>
                    <span class="text-gray-600">{{ $product->category->name ?? '' }}</span>
                </p>
                <p class="space-x-2">
                    <span class="text-gray-800 font-semibold">SKU: </span>
                    <span class="text-gray-600">{{ $product->sku }}</span>
                </p>
            </div>
            <div class="flex items-baseline  space-x-2 font-roboto mt-4">
                <p class="text-xl text-primary font-semibold">${{ $product->price }}</p>
            </div>

            <div class="mt-5 flex gap-3 md:pt-2">
                <form id="toggle-cart-form" method="POST" action="{{ route('cart.toggle', $product->id) }}" data-product-id="{{ $product->id }}">
                    @csrf

                    <button id="cart-button-detail-{{ $product->id }}"
                            class="bg-primary border border-primary text-white px-8 py-2 font-medium rounded uppercase flex items-center gap-2 hover:bg-transparent hover:text-primary transition-colors duration-300">
                        <i class="fa-solid {{ $isInCart ? 'fa-remove' : 'fa-bag-shopping' }}"></i> {{ $isInCart ? 'Remove from Cart' : 'Add to Cart' }}
                    </button>
                </form>
                <form id="toggle-wishlist-form" method="POST" action="{{ route('wishlist.toggle', $product->id) }}" data-product-id="{{ $product->id }}">
                    @csrf

                    <button id="wishlist-button-detail-{{ $product->id }}" class="border border-gray-300 text-gray-600 px-8 py-2 font-medium rounded uppercase flex items-center gap-2 hover:text-primary transition-colors duration-300"
                            aria-label="{{ $isWishlisted ? 'Remove from wishlist' : 'Add to wishlist' }}">
                        <i class="fa-solid {{ $isWishlisted ? 'fa-remove' : 'fa-heart' }}"></i> {{ $isWishlisted ? 'Remove' : 'Wishlist' }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="container pb-16 pt-4">
        <h3 class="border-t border-gray-200 font-roboto text-gray-800 pt-3 font-medium">Product Description</h3>
        <div class="w-3/5 pt-6">
            <div class="text-gray-600">
                <p>{{ $product->description }}</p>
            </div>
        </div>
    </div>

    <div class="container pb-16">
        @if(auth()->check() && $hasBoughtProduct && ! $hasReviewedProduct)
            <h2 class="text-2xl font-medium text-gray-800 uppercase mb-6">Write a Review</h2>

            <form method="POST" action="{{ route('reviews.store', $product) }}">
                @csrf

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="rating" class="text-gray-600">Rating (0 to 5):</label>
                        <select name="rating" id="rating" class="border-gray-300 focus:border-primary focus:ring-1 focus:ring-primary rounded
                @error('rating') border-red-500 @enderror">
                            <option value="5" {{ old('rating') == '5' ? 'selected' : '' }}>5 - Excellent</option>
                            <option value="4" {{ old('rating') == '4' ? 'selected' : '' }}>4 - Good</option>
                            <option value="3" {{ old('rating') == '3' ? 'selected' : '' }}>3 - Average</option>
                            <option value="2" {{ old('rating') == '2' ? 'selected' : '' }}>2 - Poor</option>
                            <option value="1" {{ old('rating') == '1' ? 'selected' : '' }}>1 - Bad</option>
                            <option value="0" {{ old('rating') == '0' ? 'selected' : '' }}>0 - Awful</option>
                        </select>

                        @error('rating')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="comment" class="text-gray-600">Comment:</label>
                        <textarea name="comment" id="comment" rows="4" class="border-gray-300 focus:border-primary focus:ring-1 focus:ring-primary rounded-lg w-full
                         @error('comment') border-red-500 @enderror">{{ old('comment') }}</textarea>

                        @error('comment')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="bg-primary text-white border border-primary px-4 py-2 rounded w-48 transition-colors duration-300 hover:text-primary hover:bg-white">
                        Submit Review
                    </button>
                </div>
            </form>        @endif
        <h2 class="text-2xl font-medium text-gray-800 uppercase my-6">Reviews</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($product->reviews as $review)
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="flex items-center mb-4">
                        <div>
                            <h4 class="text-lg font-semibold">{{ $review->user->username }}</h4>
                            <div class="flex items-center">
                                <div class="flex gap-1">
                                    @for($i = 0; $i < 5; $i++)
                                        <i class="fa-solid fa-star {{ $i < $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                    @endfor
                                </div>
                                <span class="text-gray-500 text-sm ml-2">{{ $review->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-700 break-words">{{ $review->comment }}</p>
                </div>
            @empty
                <div class="col-span-full text-center py-8">
                    <h2 class="text-2xl font-bold text-green-600">No reviews yet</h2>
                    <p class="text-gray-500 mt-2">Be the first to review this product</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="container pb-16">
        <h2 class="text-2xl font-medium text-gray-800 uppercase mb-6">Related products</h2>
        <div class="grid grid-cols-4 gap-6">
            @forelse($relatedProducts as $relatedProduct)
                <x-product::product-card :product="$relatedProduct" />
            @empty
                <div class="col-span-full text-center py-8">
                    <h2 class="text-2xl font-bold text-gray-600">No related products available</h2>
                    <p class="text-gray-500 mt-2">We couldn’t find any products related to your selection at the moment.</p>
                </div>
            @endforelse
        </div>
    </div>

    @pushonce('scripts')
        @vite('Modules/Wishlist/resources/js/main.js')
        @vite('Modules/Cart/resources/js/main.js')
        @vite('Modules/Product/resources/js/main.js')
    @endpushonce
</x-home::layouts.app>
