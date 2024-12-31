<x-home::layouts.raw title="Forgot Password">
    <div class="container py-16">
        <div class="max-w-lg mx-auto shadow px-6 py-7 rounded overflow-hidden">
            <x-auth::page-heading heading="Forgot Password">
                Reset Your Password
            </x-auth::page-heading>
            <form action="{{ route('forgot-password.email') }}" method="POST" autocomplete="off">
                @csrf

                <div class="space-y-2">
                    <div>
                        <label for="email" class="text-gray-600 mb-2 block">Email address</label>
                        <input type="email" name="email" id="email" value="{{ old('email')}}"
                               class="block w-full border border-gray-300 px-4 py-3 text-gray-600 text-sm rounded focus:ring-0 focus:border-primary placeholder-gray-400
                               @error('password') border-red-500 @enderror"
                        >

                        @error('email')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <x-forms.button style="wide">
                        Send Password Reset Email
                    </x-forms.button>
                </div>
            </form>
        </div>
    </div>
</x-home::layouts.raw>
