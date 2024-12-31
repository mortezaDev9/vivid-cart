<x-home::layouts.app title="Reviews">
    <div class="container grid grid-cols-12 items-start gap-6 pt-4 pb-16">
        <x-user::sidebar />

        <div class="col-span-9">
            @forelse ($reviews as $review)
                <div class="border border-gray-200 p-4 rounded mb-4">
                    <div class="flex justify-between items-center">
                        <a href="{{ route('products.index', $review->product->slug) }}">
                            <h2 class="text-gray-800 text-lg font-medium uppercase hover:text-primary transition-colors duration-300">{{ $review->product->title }}</h2>
                        </a>

                        <div class="flex items-center">
                            @for ($i = 0; $i < 5; $i++)
                                <i class="fa-solid fa-star {{ $i < $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                            @endfor
                        </div>
                    </div>

                    <p class="text-gray-700 mt-2">{{ $review->comment }}</p>
                    <p class="text-sm text-gray-400 mt-2">{{ $review->created_at->format('M d, Y') }}</p>
                </div>
            @empty
                <div class="text-center py-10">
                    <i class="fa-solid fa-info-circle text-gray-400 text-4xl mb-4"></i>
                    <h2 class="text-2xl font-bold text-gray-700">No Reviews Yet</h2>
                    <p class="text-gray-500 mt-2">You haven't written any reviews yet.</p>
                </div>
            @endforelse

            {{ $reviews->links() }}
        </div>
    </div>
</x-home::layouts.app>
