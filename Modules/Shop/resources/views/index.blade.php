<x-home::layouts.app title="shop">
    <!-- shop wrapper -->
    <div class="container grid md:grid-cols-4 grid-cols-2 gap-6 pt-4 pb-16 items-start">
        <!-- drawer init and toggle -->
        <div class="text-center md:hidden" >
            <button
                class="text-white hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 block md:hidden"
                type="button" data-drawer-target="drawer-example" data-drawer-show="drawer-example"
                aria-controls="drawer-example">
                <ion-icon name="grid-outline"></ion-icon>
            </button>
        </div>

        <!-- drawer component -->
        <div id="drawer-example" class="fixed top-0 left-0 z-40 h-screen p-4 overflow-y-auto transition-transform duration-300 -translate-x-full bg-white w-80 dark:bg-gray-800" tabindex="-1" aria-labelledby="drawer-label">
            <h5 id="drawer-label" class="inline-flex items-center mb-4 text-base font-semibold text-gray-500 dark:text-gray-400"><svg class="w-5 h-5 mr-2" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>Info</h5>
            <button type="button" data-drawer-hide="drawer-example" aria-controls="drawer-example" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 absolute top-2.5 right-2.5 inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" >
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                <span class="sr-only">Close menu</span>
            </button>
            <div class="divide-y divide-gray-200 space-y-5">
                <div>
                    <h3 class="text-xl text-gray-800 mb-3 uppercase font-medium">Categories</h3>
                    <div class="space-y-2">
                        <form method="GET" action="{{ route('shop') }}">
                            <x-shop::filters skip="category" />

                            @foreach($categories as $category)
                                <div class="flex items-center">
                                    <input type="checkbox"
                                           name="category[]"
                                           id="category-{{ $category->slug }}"
                                           value="{{ $category->slug }}"
                                           {{ in_array($category->slug, $selectedCategorySlugs) ? 'checked' : '' }}
                                           class="text-primary focus:ring-0 rounded-sm cursor-pointer"
                                           onchange="this.form.submit()">
                                    <label for="category-{{ $category->slug }}" class="text-gray-600 ml-3 cursor-pointer">{{ $category->name }}</label>
                                    <div class="ml-auto text-gray-600 text-sm">({{ $category->products_count }})</div>
                                </div>
                            @endforeach
                        </form>
                    </div>
                </div>

                <div class="pt-4">
                    <h3 class="text-xl text-gray-800 mb-3 uppercase font-medium">Price</h3>
                    <div class="mt-4 flex items-center">
                        <input type="text" name="min" id="min"
                               class="w-full border-gray-300 focus:border-primary rounded focus:ring-0 px-3 py-1 text-gray-600 shadow-sm"
                               placeholder="min">
                        <span class="mx-3 text-gray-500">-</span>
                        <input type="text" name="max" id="max"
                               class="w-full border-gray-300 focus:border-primary rounded focus:ring-0 px-3 py-1 text-gray-600 shadow-sm"
                               placeholder="max">
                    </div>
                </div>
            </div>
        </div>

        <!-- ./sidebar -->
        <div class="col-span-1 bg-white px-4 pb-6 shadow rounded overflow-hiddenb hidden md:block">
            <div class="divide-y divide-gray-200 space-y-5">
                <div>
                    <h3 class="text-xl text-gray-800 mb-3 uppercase font-medium">Categories</h3>
                    <div class="space-y-2">
                        <form method="GET" action="{{ route('shop') }}">
                            <x-shop::filters skip="category" />

                            @foreach($categories as $category)
                                <div class="flex items-center">
                                    <input type="checkbox"
                                           name="category[]"
                                           id="category-{{ $category->slug }}"
                                           value="{{ $category->slug }}"
                                           {{ in_array($category->slug, $selectedCategorySlugs) ? 'checked' : '' }}
                                           class="text-primary focus:ring-0 rounded-sm cursor-pointer"
                                           onchange="this.form.submit()">
                                    <label for="category-{{ $category->slug }}" class="text-gray-600 ml-3 cursor-pointer">{{ $category->name }}</label>
                                    <div class="ml-auto text-gray-600 text-sm">({{ $category->products_count }})</div>
                                </div>
                            @endforeach
                        </form>
                    </div>
                </div>

                <div class="pt-4">
                    <h3 class="text-xl text-gray-800 mb-3 uppercase font-medium">Price</h3>
                    <form method="GET" action="{{ route('shop') }}">
                        <x-shop::filters :skip="['min', 'max']" />

                        <div class="mt-4 grid grid-cols-2 gap-3">
                            <div class="">
                                <input type="number" name="min" id="min"
                                       class="w-full border-gray-300 focus:border-primary rounded focus:ring-0 px-3 py-2 text-gray-600 shadow-sm
                                       @error('min') border-red-500 @enderror"
                                       placeholder="Min"
                                       value="{{ request()->has('min') ? request()->query('min') : '' }}">

                                @error('min')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="">
                                <input type="number" name="max" id="max"
                                       class="w-full border-gray-300 focus:border-primary rounded focus:ring-0 px-3 py-2 text-gray-600 shadow-sm
                                       @error('max') border-red-500 @enderror"
                                       placeholder="Max"
                                       value="{{ request()->has('max') ? request()->query('max') : '' }}">

                                @error('max')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <x-forms.button class="mt-4 w-full" style="wide">Apply</x-forms.button>
                    </form>
                </div>

            </div>
        </div>
        <!-- products -->
        <div class="col-span-3">
            <div class="flex items-center mb-4">
                <form method="GET" action="{{ route('shop') }}">
                    <x-shop::filters skip="sort" />

                    <label for="sort" class="sr-only">Sort Options</label>
                    <select name="sort"
                            id="sort"
                            class="w-44 text-sm text-gray-600 py-3 px-4 border-gray-300 shadow-sm rounded focus:ring-primary focus:border-primary"
                            onchange="this.form.submit()">
                        <option>Default sorting</option>
                        <option value="price-low-to-high" {{ request('sort') === 'price-low-to-high' ? 'selected' : '' }}>Price low to high</option>
                        <option value="price-high-to-low" {{ request('sort') === 'price-high-to-low' ? 'selected' : '' }}>Price high to low</option>
                        <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Latest products</option>
                    </select>
                </form>

                <div class="flex gap-2 ml-auto">
                    <form method="GET" action="{{ route('shop') }}">
                        <x-shop::filters skip="grid" />

                        <input type="hidden" name="grid" value="grid">

                        <button class="border border-primary w-10 h-9 flex items-center justify-center text-white bg-primary rounded cursor-pointer">
                            <i class="fa-solid fa-grip-vertical"></i>
                        </button>
                    </form>

                    <form method="GET" action="{{ route('shop') }}">
                        <x-shop::filters skip="list" />

                        <input type="hidden" name="list" value="list">

                        <button class="border border-gray-300 w-10 h-9 flex items-center justify-center text-gray-600 rounded cursor-pointer">
                            <i class="fa-solid fa-list"></i>
                        </button>
                    </form>
                </div>
            </div>

            @if(request()->has('list') && request()->query('list') === 'list')
                <div class="grid grid-cols-1 gap-6" data-view="grid">
                    @foreach($products as $product)
                            <x-product::product-card-wide :$product />
                    @endforeach
                </div>
            @else
                <div class="grid md:grid-cols-3 grid-cols-2 gap-6" data-view="grid">
                    @foreach($products as $product)
                            <x-product::product-card :$product />
                    @endforeach
                </div>
            @endif
            <div class="mt-10">
                {{ $products->links() }}
            </div>
        </div>
        <!-- ./products -->
    </div>
    <!-- ./shop wrapper -->

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.js"></script>
        <script src="https://unpkg.com/ionicons@5.0.0/dist/ionicons.js"></script>
    @endpush
</x-home::layouts.app>
