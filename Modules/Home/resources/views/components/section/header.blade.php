<header class="py-4 shadow-sm bg-white">
    <div class="container flex items-center justify-between">
        <a href="{{ route('home') }}">
            <img src="{{ Vite::asset('resources/images/logo.svg') }}" alt="Logo" class="w-32">
        </a>

        <form method="GET" action="{{ route('shop') }}" class="w-full max-w-xl relative flex items-center">
            <x-shop::filters skip="q" />

            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-lg text-gray-400">
                <i class="fa-solid fa-magnifying-glass"></i>
            </span>
            <label for="search" class="sr-only">Search</label>
            <input type="text" name="q" id="search"
                   class="w-full border border-primary border-r-0 pl-12 py-3 pr-3 rounded-l-md focus:outline-none hidden md:flex"
                   placeholder="search">
            <button class="bg-primary border border-primary text-white text-center px-8 py-3 rounded-r-md hover:bg-transparent hover:text-primary transition duration-300 hidden md:flex items-center justify-center">
                Search
            </button>
        </form>


        <div class="flex items-center space-x-4">
            @auth
                <a href="{{ route('wishlist.index') }}" class="text-center text-gray-700 hover:text-primary transition duration-300 relative">
                    <div class="text-2xl">
                        <i class="fa-regular fa-heart"></i>
                    </div>
                    <div class="text-xs leading-3">Wishlist</div>
                    <div id="wishlist-count"
                         class="absolute right-0 -top-1 w-5 h-5 rounded-full flex items-center justify-center bg-primary text-white text-xs">
                        {{ auth()->user()->wishlist->products()->count() }}
                    </div>
                </a>

                <a href="{{ route('cart.index') }}" class="text-center text-gray-700 hover:text-primary transition duration-300 relative">
                    <div class="text-2xl">
                        <i class="fa-solid fa-bag-shopping"></i>
                    </div>
                    <div class="text-xs leading-3">Cart</div>
                    <div id="cart-count"
                         class="absolute -right-3 -top-1 w-5 h-5 rounded-full flex items-center justify-center bg-primary text-white text-xs">
                        {{ auth()->user()->cart->products()->count() }}
                    </div>
                </a>

                <a href="{{ route('account.index') }}" class="text-center text-gray-700 hover:text-primary transition duration-300 relative">
                    <div class="text-2xl">
                        <i class="fa-regular fa-user"></i>
                    </div>
                    <div class="text-xs leading-3">Account</div>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button class="text-center text-gray-700 hover:text-primary transition duration-300 relative">
                        <div class="text-2xl">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </div>
                        <div class="text-xs leading-3">Logout</div>
                    </button>
                </form>
            @endauth

            @guest
                <a href="{{ route('login.form') }}" class="text-center text-gray-700 hover:text-primary transition duration-300 relative">
                    <div class="text-2xl">
                        <i class="fa-solid fa-right-to-bracket"></i>
                    </div>
                    <div class="text-xs leading-3">Login</div>
                </a>
                <a href="{{ route('register.form') }}" class="text-center text-gray-700 hover:text-primary transition duration-300 relative">
                    <div class="text-2xl">
                        <i class="fa-solid fa-user-plus"></i>
                    </div>
                    <div class="text-xs leading-3">Sign Up</div>
                </a>
            @endguest
        </div>
    </div>
</header>
