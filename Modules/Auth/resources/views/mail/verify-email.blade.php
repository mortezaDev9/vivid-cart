<x-mail.layouts.main>
    <x-slot:heading>
        Verify Email Address
    </x-slot:heading>

    <div class="p-6">
        <p class="text-gray-600 mt-4">
            Thank you for registering with {{ config('app.name') }}. Please verify your email address to complete your registration and activate your account.
        </p>

        <p class="text-gray-600 mt-4">
            To verify your email, click the button below:
        </p>

        <div class="mt-6 mb-6 text-center">
            <a href="{{ $url }}" class="inline-block text-white font-bold py-2 px-4 rounded shadow" style="background-color: #fd3d57;">
                Verify Email
            </a>
        </div>

        <hr />

        <p class="text-gray-600 mt-4">
            If you're having trouble clicking the "Verify Email" button, copy and paste the URL below into your web browser:
        </p>
        <p class="text-gray-600 break-words">{{ $url }}</p>
    </div>
</x-mail.layouts.main>
