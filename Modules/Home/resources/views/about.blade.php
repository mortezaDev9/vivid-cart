<x-home::layouts.app title="About Us">
    <!-- about wrapper -->
    <div class="container grid grid-cols-12 items-start gap-6 pt-4 pb-16">

        <!-- about content -->
        <div class="col-span-12 shadow rounded bg-white px-6 py-8">
            <h3 class="font-medium text-gray-800 text-2xl mb-6">Our Story</h3>
            <p class="text-gray-700 mb-4 leading-relaxed">
                Welcome to VividCart, your number one source for Products. We're dedicated to giving you the very best of Service, with a focus on Quality, Customer Service, and Simplicity.
            </p>
            <p class="text-gray-700 mb-4 leading-relaxed">
                Founded in 2024 by John Doe, VividCart has come a long way from its beginnings in JohnCountry. When John first started out, his passion for selling products online drove them to quit day job, do tons of research and develop VividCart, so that VividCart can offer you great shopping experience. We now serve customers all over JohnCountry, and are thrilled that we're able to turn our passion into our own website.
            </p>
            <p class="text-gray-700 mb-4 leading-relaxed">
                We hope you enjoy our products as much as we enjoy offering them to you. If you have any questions or comments, please don't hesitate to <a href="{{ route('contact') }}" class="text-primary transition-colors duration-300 hover:text-gray-600">contact us</a>.
            </p>
            <p class="text-gray-700 leading-relaxed">
                Sincerely,<br>
                John Doe, CEO and Founder of VividCart
            </p>
        </div>
        <!-- ./about content -->

    </div>
    <!-- ./about wrapper -->
</x-home::layouts.app>
