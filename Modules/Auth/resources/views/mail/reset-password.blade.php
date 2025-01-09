<x-mail.layouts.main>
    <x-slot:heading>
        Reset Your Password
    </x-slot:heading>

    <div class="p-6">
        <p class="text-gray-600 mt-4">
            We received a request to reset your password for your {{ config('app.name') }} account. If you didn't request this, you can safely ignore this email.
        </p>

        <p class="text-gray-600 mt-4">
            To reset your password, click the button below:
        </p>

        <div class="mt-6 mb-6 text-center">
            <a href="{{ $url }}" class="inline-block text-white font-bold py-2 px-4 rounded shadow" style="background-color: #fd3d57;">
                Reset Password
            </a>
        </div>

        <hr />

        <p class="text-gray-600 mt-4">
            If you're having trouble clicking the "Verify Email" button, copy and paste the URL below into your web browser:
        </p>
        <p class="text-gray-600 break-words">{{ $url }}</p>
    </div>
</x-mail.layouts.main>
