@props(['ctaText' => 'Continue'])

<div class="mt-6 flex justify-center relative">
    <div class="text-gray-600 uppercase px-4 bg-white z-10 relative">Or</div>
    <div class="absolute left-0 top-3 w-full border-b-2 border-gray-200"></div>
</div>
<div class="mt-4 flex flex-col items-center space-y-4">
    <a href="#"
       class="flex justify-center items-center w-full p-3 border border-gray-300 rounded font-roboto font-medium transition-colors duration-300 hover:bg-gray-100">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" role="img" aria-hidden="true" class="crayons-icon crayons-icon--default">
            <path d="M18.09 18.75c2.115-1.973 3.052-5.25 2.49-8.393h-8.392v3.473h4.777a3.945 3.945 0 0 1-1.777 2.67l2.902 2.25Z" fill="#4285F4"></path>
            <path d="M4.215 15.982A9 9 0 0 0 18.09 18.75l-2.902-2.25a5.37 5.37 0 0 1-8.018-2.813l-2.955 2.296Z" fill="#34A853"></path>
            <path d="M7.17 13.687c-.375-1.17-.375-2.25 0-3.42L4.215 7.965a9.06 9.06 0 0 0 0 8.025l2.955-2.303Z" fill="#FBBC02"></path>
            <path d="M7.17 10.267c1.035-3.24 5.438-5.115 8.393-2.347l2.58-2.528A8.85 8.85 0 0 0 4.215 7.965l2.955 2.302Z" fill="#EA4335"></path>
        </svg>
        <span class="flex-1 text-center">{{ $ctaText }} with Google</span>
    </a>

    <a href="#"
       class="flex justify-center items-center w-full p-3 border border-gray-300 rounded font-roboto font-medium transition-colors duration-300 hover:bg-gray-100">
        <i class="fa-brands fa-square-facebook text-2xl text-blue-600"></i>
        <span class="flex-1 text-center">{{ $ctaText }} with Facebook</span>
    </a>

    <a href="#"
       class="flex justify-center items-center w-full p-3 border border-gray-300 rounded font-roboto font-medium transition-colors duration-300 hover:bg-gray-100">
        <i class="fa-brands fa-apple text-2xl"></i>
        <span class="flex-1 text-center">{{ $ctaText }} with Apple</span>
    </a>
</div>
