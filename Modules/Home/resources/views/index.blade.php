<x-home::layouts.app title="Home" :categories="$categories">
    <x-home::banner />

    <x-home::features />

    <div class="container pb-16">
        <h2 class="text-2xl font-medium text-gray-800 uppercase mb-6">Top New Arrival</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            @forelse($newProducts as $newProduct)
                <x-product::product-card :product="$newProduct" />
            @empty
                <div class="col-span-1 md:col-span-4 flex flex-col items-center justify-center p-6 bg-white shadow rounded">
                    <i class="fa-solid fa-box-open text-gray-400 text-3xl mb-4"></i>
                    <h2 class="text-lg font-medium text-gray-800">No new arrival products</h2>
                    <p class="text-sm text-gray-500 mt-2">Check back later for new arrivals or explore our other products.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- ads -->
    <div class="container pb-16">
        <a href="#">
            <img src="{{ Vite::asset('resources/images/offer.jpg') }}" alt="ads" class="w-full">
        </a>
    </div>
    <!-- ./ads -->

    <div class="container pb-16">
        <h2 class="text-2xl font-medium text-gray-800 uppercase mb-6">Recommended For You</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @forelse($products as $product)
                <x-product::product-card :$product />
            @empty
                <div class="col-span-1 md:col-span-4 flex flex-col items-center justify-center p-6 bg-white shadow rounded">
                    <i class="fa-solid fa-shopping-cart text-gray-400 text-3xl mb-4"></i>
                    <h2 class="text-lg font-medium text-gray-800">Wow! it looks like we don't have any products</h2>
                    <p class="text-sm text-gray-500 mt-2">Check back later for new products so you can buy some</p>
                </div>
            @endforelse
        </div>
    </div>
</x-home::layouts.app>
