<x-mail.layouts.main>
    <x-slot:heading>
        Welcome {{ config('app.name') }}!
    </x-slot:heading>

    <x-slot:title>
        We are happy we have you here
    </x-slot:title>

    <div class="p-6">
        <h2 class="text-xl font-semibold text-gray-800">Hello John Doe,</h2>
        <p class="text-gray-600 mt-4">
            Thank you for joining {{ config('app.name') }}. We're excited to help you get started. Explore the features we've created just for you, and don't hesitate to reach out if you need any assistance.
        </p>

        <p class="text-gray-600 mt-4">
            To start exploring, click the button below to log in to your account:
        </p>

        <div class="mt-6 text-center">
            <a href="{{ url(route('login.form')) }}" class="inline-block text-white font-bold py-2 px-4 rounded shadow" style="background-color: #fd3d57;">
                Log in to Your Account
            </a>
        </div>

        <p class="text-gray-600 mt-8">
            If you have any questions, feel free to <a href="mailto:{{ config('app.email_address') }}" class="text-gray-800 underline" style="color: #fd3d57;">contact our support team</a>. We're here to help!
        </p>
    </div>
</x-mail.layouts.main>
