<x-home::layouts.app title="Profile">
    <div class="container grid grid-cols-12 items-start gap-6 pt-4 pb-16">
        <x-user::sidebar />

        <!-- Profile Information -->
        <div class="col-span-9 shadow rounded px-6 pt-5 pb-7">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-medium text-gray-800 text-lg">Personal Profile</h3>
                <a href="{{ route('account.profile.edit') }}" class="text-primary transition-colors duration-300 hover:text-black">Edit</a>
            </div>
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Username</label>
                        <p class="text-gray-800">{{ auth()->user()->username }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Address</label>
                        <p class="text-gray-800">{{ auth()->user()->address ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email Address</label>
                        <p class="text-gray-800">{{ auth()->user()->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <p class="text-gray-800">{{ auth()->user()->phone ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-home::layouts.app>
