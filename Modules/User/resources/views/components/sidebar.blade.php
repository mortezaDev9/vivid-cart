<div class="col-span-3">
    <div class="px-4 py-3 shadow flex items-center gap-4">
        <div class="flex-grow">
            <h4 class="text-gray-800 font-medium"><a href="{{ route('account.index')}}" class="hover:text-primary">{{ auth()->user()->username }}</a></h4>
        </div>
    </div>

    <div class="mt-6 bg-white shadow rounded p-4 divide-y divide-gray-200 space-y-4 text-gray-600">
        <div class="space-y-1 pl-8">
            <p class="relative block font-medium capitalize transition">
                        <span class="absolute -left-8 top-0 text-base">
                            <i class="fa-regular fa-address-card"></i>
                        </span>
                Manage Account
            </p>
            <a href="{{ route('account.index') }}" class="relative {{ request()->routeIs('account.index', 'account.profile.edit') ? 'text-primary' : '' }} hover:text-primary block capitalize transition">
                Profile Information
            </a>
            <a href="{{ route('account.password.change') }}" class="relative {{ request()->routeIs('account.password.change') ? 'text-primary' : '' }} hover:text-primary block capitalize transition">
                Change Password
            </a>
        </div>

        <div class="space-y-1 pl-8 pt-4">
            <p class="relative block font-medium capitalize transition">
                        <span class="absolute -left-8 top-0 text-base">
                            <i class="fa-solid fa-box-archive"></i>
                        </span>
                Order History
            </p>
            <a href="{{ route('orders.index') }}" class="relative {{ request()->routeIs('orders.index') ? 'text-primary' : '' }} hover:text-primary block capitalize transition">
                My Orders
            </a>
            <a href="{{ route('orders.cancellations') }}" class="relative {{ request()->routeIs('orders.cancellations') ? 'text-primary' : '' }} hover:text-primary block capitalize transition">
                My Cancellations
            </a>
        </div>

        <div class="space-y-1 pl-8 pt-4">
            <a href="{{ route('reviews.index') }}" class="relative {{ request()->routeIs('reviews.index') ? 'text-primary' : '' }} hover:text-primary block font-medium capitalize transition">
                        <span class="absolute -left-8 top-0 text-base">
                            <i class="fa-solid fa-pen"></i>
                        </span>
                My Reviews
            </a>
        </div>

        <div class="space-y-1 pl-8 pt-4">
            <a href="{{ route('wishlist.index') }}" class="relative {{ request()->routeIs('wishlist.index') ? 'text-primary' : '' }} hover:text-primary block font-medium capitalize transition">
                        <span class="absolute -left-8 top-0 text-base">
                            <i class="fa-regular fa-heart"></i>
                        </span>
                My Wishlist
            </a>
        </div>
    </div>
</div>
