<x-home::layouts.app title="checkout">
    @if($products->isNotEmpty())

        <form method="POST" action="{{ route('orders.store') }}" class="container grid grid-cols-12 items-start pb-16 pt-4 gap-6">
            @csrf

            <div class="col-span-8 border border-gray-200 p-4 rounded">
                <h3 class="text-lg font-medium capitalize mb-4">Checkout</h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="first_name" class="text-gray-600">First Name <span
                                    class="text-primary">*</span></label>
                            <input type="text" name="first_name" id="first_name" class="input-box @error('first_name') border-red-500 @enderror" value="{{ old('first_name') }}">

                            @error('first_name')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="last_name" class="text-gray-600">Last Name <span
                                    class="text-primary">*</span></label>
                            <input type="text" name="last_name" id="last_name" class="input-box @error('last_name') border-red-500 @enderror" value="{{ old('last_name') }}">

                            @error('last_name')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="address" class="text-gray-600">Address <span
                                    class="text-primary">*</span></label>
                            <input type="text" name="address" id="address" class="input-box @error('address') border-red-500 @enderror" value="{{ old('address', auth()->user()->address) }}">

                            @error('address')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="payment_method" class="text-gray-600">Payment Method <span
                                    class="text-primary">*</span></label>
                            <select name="payment_method"
                                    id="payment_method"
                                    class="input-box @error('payment_method') border-red-500 @enderror">
                                @foreach($paymentMethods as $paymentMethod)
                                    <option value="{{ $paymentMethod }}" {{ $paymentMethod === old('payment_method') ? 'selected' : '' }}>{{ $paymentMethod }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="phone">Phone Number <span
                                class="text-primary">*</span></label>
                        <input type="text" name="phone" id="phone" class="input-box @error('phone') border-red-500 @enderror" value="{{ old('phone', auth()->user()->phone) }}">

                        @error('phone')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

            </div>

            <div class="col-span-4 border border-gray-200 p-4 rounded">
                <h4 class="text-gray-800 text-lg mb-4 font-medium uppercase">Order Summary</h4>
                <div class="space-y-2">
                    @foreach($products as $product)
                        <div class="flex justify-between">
                            <div>
                                <h5 class="text-gray-800 font-medium">{{ $product->title }}</h5>
                            </div>
                            <p class="text-gray-600">{{ $product->pivot->quantity }}</p>
                            <p class="text-gray-800 font-medium">${{ $product->price }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-between border-b border-gray-200 mt-1 text-gray-800 font-medium py-3 uppercase">
                    <p>Subtotal</p>
                    <p>${{ $subtotal }}</p>
                </div>

                <div class="flex justify-between border-b border-gray-200 mt-1 text-gray-800 font-medium py-3 uppercase">
                    <p>shipping</p>
                    <p>{{ $subtotal > 1000 ? 'Free' : '$' . $shippingCost }}</p>
                </div>

                <div class="flex justify-between text-gray-800 font-medium py-3 uppercase">
                    <p class="font-semibold">Total</p>
                    <p>${{ $total }}</p>
                </div>

                <x-forms.button style="wide">Place Order</x-forms.button>
            </div>
        </form>
            @else
        <div class="flex flex-col items-center justify-center text-center py-10">
            <i class="fa-solid fa-info-circle text-gray-400 text-4xl mb-4"></i>
            <h2 class="text-2xl font-bold text-gray-700">Your cart is empty</h2>
            <p class="text-gray-500 mt-2">You have not added any products to your cart to purchase them.</p>
            <a href="{{ route('shop') }}" class="mt-6 px-6 py-2 text-center text-sm text-white bg-primary border border-primary rounded hover:bg-transparent hover:text-primary transition duration-300 uppercase font-roboto font-medium">
                Start Shopping
            </a>
        </div>
    @endif

</x-home::layouts.app>
