<x-home::layouts.app title="profile">

    <div class="container grid grid-cols-12 items-start gap-6 pt-4 pb-16">
        <x-user::sidebar />

        <!-- info -->
        <div class="col-span-9 shadow rounded px-6 pt-5 pb-7">
            <h4 class="text-lg font-medium capitalize mb-4">
                Profile information
            </h4>
            <form action="{{ route('account.profile.update') }}" method="POST" autocomplete="off">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="username">Username</label>
                            <input type="text" name="username" id="username" class="input-box @error('username') border-red-500 @enderror" value="{{ auth()->user()->username }}">

                            @error('username')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="address">Address</label>
                            <input type="text" name="address" id="address" class="input-box @error('address') border-red-500 @enderror" value="{{ auth()->user()->address }}">

                            @error('address')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="email">Email Address</label>
                            <input type="email" name="email" id="email" class="input-box @error('email') border-red-500 @enderror" value="{{ auth()->user()->email }}">

                            @error('email')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="phone">Phone Number</label>
                            <input type="text" name="phone" id="phone" class="input-box @error('phone') border-red-500 @enderror" value="{{ auth()->user()->phone ?? '' }}">

                            @error('phone')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <x-forms.button>
                        Save Changes
                    </x-forms.button>
                </div>
            </form>
        </div>
        <!-- ./info -->

    </div>
    </x-home::layouts.app>
