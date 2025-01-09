<x-home::layouts.app title="Orders">
    <div class="container grid grid-cols-12 items-start gap-6 pt-4 pb-16">
        <x-user::sidebar />

        <div id="order-container" class="col-span-9 space-y-6">
            @forelse($orders as $order)
                <x-order::order-card :$order />
            @empty
                <div class="flex flex-col items-center justify-center text-center py-10">
                    <i class="fa-solid fa-info-circle text-gray-400 text-4xl mb-4"></i>
                    <h2 class="text-2xl font-bold text-gray-700">You don't have any orders yet</h2>
                    <p class="text-gray-500 mt-2">You can start shopping to place your first order.</p>
                    <a href="{{ route('shop') }}" class="mt-6 px-6 py-2 text-center text-sm text-white bg-primary border border-primary rounded hover:bg-transparent hover:text-primary transition duration-300 uppercase font-roboto font-medium">
                        Start Shopping
                    </a>
                </div>
            @endforelse

            {{ $orders->links() }}
        </div>
    </div>
</x-home::layouts.app>
