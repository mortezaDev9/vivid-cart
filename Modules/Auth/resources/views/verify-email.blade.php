<x-home::layouts.raw title="Email Verification">
    @if(session('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 5.652a1 1 0 00-1.414 0L10 8.586 7.066 5.652a1 1 0 10-1.414 1.414L8.586 10l-2.934 2.934a1 1 0 101.414 1.414L10 11.414l2.934 2.934a1 1 0 001.414-1.414L11.414 10l2.934-2.934a1 1 0 000-1.414z"/></svg>
        </span>
        </div>
    @endif

    <div class="container py-16">
        <div class="max-w-lg mx-auto shadow px-6 py-7 rounded overflow-hidden">
            <x-auth::page-heading heading="Email Verify">
                We have sent you email to verify your email address
            </x-auth::page-heading>
            <form action="{{ route('verification.resend') }}" method="POST" autocomplete="off">
                @csrf

                <div class="flex flex-col space-y-4 mt-6">
                    <button type="submit" class="text-primary hover:text-gray-600 text-left">
                        Didn't get the email? Tap to resend
                    </button>
                    <a href="{{ route('home') }}" class="text-primary hover:text-gray-600 text-left">
                        Verify later, return to homepage
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-home::layouts.raw>
