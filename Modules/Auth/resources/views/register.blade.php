<x-home::layouts.raw title="Register">
    <div class="container py-16">
        <div class="max-w-lg mx-auto shadow px-6 py-7 rounded overflow-hidden">
            <x-auth::page-heading heading="Register">
                Register for new customer
            </x-auth::page-heading>
            <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="space-y-2">
                    <div>
                        <label for="username" class="text-gray-600 mb-2 block">Username</label>
                        <input type="text" name="username" id="username" value="{{ old('username')}}"
                               class="block w-full border border-gray-300 px-4 py-3 text-gray-600 text-sm rounded focus:ring-0 focus:border-primary placeholder-gray-400
                               @error('username') border-red-500 @enderror"
                        >

                        @error('username')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="text-gray-600 mb-2 block">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email')}}"
                               class="block w-full border border-gray-300 px-4 py-3 text-gray-600 text-sm rounded focus:ring-0 focus:border-primary placeholder-gray-400
                               @error('email') border-red-500 @enderror"
                        >

                        @error('email')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password" class="text-gray-600 mb-2 block">Password</label>
                        <input type="password" name="password" id="password"
                               class="block w-full border border-gray-300 px-4 py-3 text-gray-600 text-sm rounded focus:ring-0 focus:border-primary placeholder-gray-400
                               @error('password') border-red-500 @enderror"
                        >

                        @error('password')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="text-gray-600 mb-2 block">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="block w-full border border-gray-300 px-4 py-3 text-gray-600 text-sm rounded focus:ring-0 focus:border-primary placeholder-gray-400
                               @error('password_confirmation') border-red-500 @enderror"
                        >

                        @error('password_confirmation')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <x-forms.divider />

                <div>
                    <div class="flex items-center">
                        <input type="checkbox" name="agreement" id="agreement"
                               class="text-primary focus:ring-0 rounded-sm cursor-pointer">
                        <label for="agreement" class="text-gray-600 ml-3 cursor-pointer @error('agreement') text-red-500 @enderror">
                            <span>I have read and agree to the</span>
                            <a href="#" class="text-primary transition-colors duration-300 hover:text-gray-600 @error('agreement') text-red-500 @enderror">terms & conditions</a>
                        </label>
                    </div>

                    @error('agreement')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-4">
                    <x-forms.button style="wide">
                        Register
                    </x-forms.button>
                </div>
            </form>

            <x-auth::login-with ctaText="Sign up" />

            <p class="mt-4 text-center text-gray-600">Already have account?
                <a href="{{ route('login.form') }}" class="text-primary transition-colors duration-300 hover:text-gray-600">
                    Login now
                </a>
            </p>
        </div>
    </div>
</x-home::layouts.raw>
