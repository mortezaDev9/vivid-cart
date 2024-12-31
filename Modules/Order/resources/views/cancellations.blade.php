<x-home::layouts.app title="Order Cancellations">

    <div class="container grid grid-cols-12 items-start gap-6 pt-4 pb-16">
        <x-user::sidebar />

        <div id="cancellation-container" class="col-span-9 space-y-4">
            @forelse($cancellations as $order)
                <x-order::order-card :$order />
            @empty
                <div class="flex flex-col items-center justify-center text-center py-10">
                    <i class="fa-solid fa-info-circle text-gray-400 text-4xl mb-4"></i>
                    <h2 class="text-2xl font-bold text-gray-700">You don't have any Cancelled Orders</h2>
                    <p class="text-gray-500 mt-2">All your orders are successfully processed or pending.</p>
                </div>
            @endforelse

            {{ $cancellations->links() }}
        </div>
    </div>
    </x-home::layouts.app>
