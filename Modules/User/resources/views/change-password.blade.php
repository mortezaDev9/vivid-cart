<x-home::layouts.app title="profile">

    <div class="container grid grid-cols-12 items-start gap-6 pt-4 pb-16">
        <x-user::sidebar />

        <!-- info -->
        <div class="col-span-9 shadow rounded px-6 pt-5 pb-7">
            <h4 class="text-lg font-medium capitalize mb-4">
                Profile information
            </h4>
            <form action="{{ route('account.password.update') }}" method="POST" autocomplete="off">
                @csrf
                @method('PATCH')

                <div class="space-y-4">
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label for="current_password">Current Password</label>
                            <input type="password" name="current_password" id="current_password" class="input-box @error('current_password') border-red-500 @enderror">

                            @error('current_password')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="new_password">New Password</label>
                            <input type="password" name="new_password" id="new_password" class="input-box @error('new_password') border-red-500 @enderror">

                            @error('new_password')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="new_password_confirmation">Confirm Password</label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="input-box @error('new_password_confirmation') border-red-500 @enderror">

                            @error('new_password_confirmation')
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
