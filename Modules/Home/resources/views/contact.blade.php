<x-home::layouts.app title="Contact Us">
    <!-- contact wrapper -->
    <div class="container grid grid-cols-12 items-start gap-6 pt-4 pb-16">

        <!-- contact form -->
        <div class="col-span-8 shadow rounded bg-white px-4 pt-6 pb-8">
            <h3 class="font-medium text-gray-800 text-lg mb-4">Get in Touch</h3>

            <form action="" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="text-gray-600">Your Name</label>
                    <input type="text" name="name" id="name" class="input-box">
                </div>
                <div>
                    <label for="email" class="text-gray-600">Your Email</label>
                    <input type="email" name="email" id="email" class="input-box">
                </div>
                <div>
                    <label for="message" class="text-gray-600">Your Message</label>
                    <textarea name="message" id="message" rows="5" class="input-box"></textarea>
                </div>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded hover:bg-primary-dark transition">Send Message</button>
            </form>

        </div>
        <!-- ./contact form -->

        <!-- contact info -->
        <div class="col-span-4 shadow rounded bg-white px-4 pt-6 pb-8">
            <h3 class="font-medium text-gray-800 text-lg mb-4">Contact Information</h3>
            <p class="text-gray-700 mb-4">Feel free to reach out to us through any of the channels below:</p>
            <p class="text-gray-800"><strong>Email:</strong> support@yourcompany.com</p>
            <p class="text-gray-800"><strong>Phone:</strong> +123 456 7890</p>
            <p class="text-gray-800"><strong>Address:</strong> 123 Your Street, City, Country</p>
        </div>
        <!-- ./contact info -->

    </div>
    <!-- ./contact wrapper -->
</x-home::layouts.app>
