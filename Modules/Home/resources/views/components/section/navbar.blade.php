<nav class="bg-white">
    <div class="container flex justify-between items-center px-3 py-2 mx-auto">
        <div class="relative group">
            <button class="flex items-center px-4 py-2 bg-primary text-white transition duration-300 rounded-md hover:bg-primary-dark focus:outline-none">
                <span class="text-white">
                    <i class="fa-solid fa-bars"></i>
                </span>
                <span class="ml-2 capitalize">All Categories</span>
            </button>
            <!-- Dropdown menu -->
            <div class="absolute left-0 top-full w-48 bg-white shadow-lg rounded-md py-2 opacity-0 group-hover:opacity-100 transition duration-300 invisible group-hover:visible z-10">
                @foreach($categories as $category)
                    <a href="{{ route('shop', ['category[]' => $category->slug]) }}" class="flex items-center px-4 py-2 hover:bg-gray-100 transition">
                        <img src="{{ Vite::asset($category->icon) }}" alt="{{ $category->name }}" class="w-5 h-5 object-contain">
                        <span class="ml-4 text-gray-700">{{ $category->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>
        <div class="flex items-center space-x-6">
            <a href="{{ route('home') }}" class="text-gray-700 hover:text-primary transition">Home</a>
            <a href="{{ route('shop') }}" class="text-gray-700 hover:text-primary transition">Shop</a>
            <a href="{{ route('about') }}" class="text-gray-700 hover:text-primary transition">About us</a>
            <a href="{{ route('contact') }}" class="text-gray-700 hover:text-primary transition">Contact us</a>
        </div>
    </div>
</nav>
