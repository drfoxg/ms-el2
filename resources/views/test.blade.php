<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased">
        <!-- header -->
        <header class="flex px-2 border-b items-center justify-between">
            <a class="uppercase font-bold text-purple-800" href="">webDev</a>
            <nav class="flex items-center">
                <ul class="text-gray-500 font-semibold inline-flex items-center">
                    <li><a class="header-link" href="#">Home</a></li>
                    <li><a class="header-link" href="#">About</a></li>
                    <li><a class="header-link" href="#">Contact</a></li>
                </ul>
                <ul class="inline-flex items-center">
                    <li><button class="header-btn">Login</button></li>
                    <li><button class="header-btn">Register</button></li>
                </ul>
            </nav>
        </header>
        <main>
            <!-- breadcrumbs -->
            <div class="flex items-center px-2">
                <div class="flex items-center py-4 overflow-y-auto whitespace-nowrap ">
                    <a class="text-gray-600 hover:text-gray-900" href="#">Home</a>
                    <span class="mx-2 text-gray-500">&gt;</span>
                    <a class="text-gray-600 hover:text-gray-900" href="#">News</a>
                    <span class="mx-2 text-gray-500 ">&gt;</span>
                    <a class="text-gray-600" href="#">Tech</a>
                </div>
            </div>

            <!-- post cards -->
            <section class="px-2">
                <a class="block mb-10" href="#">
                    <div class="flex">
                        <div>
                            <h3 class="mt-3 mb-2 text-gray-700 font-bold text-2xl">Lorem ipsum dolor sit, amet consectetur adipisicing elit.</h3>
                            <p class="text-gray-700">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Esse necessitatibus, odio fuga assumenda aperiam quas voluptatem voluptates illum repudiandae dolores ipsum a similique commodi. Explicabo a blanditiis saepe corrupti animi?</p>
                        </div>
                    </div>
                </a>

                <a class="block mb-10" href="#">
                    <div class="flex">
                        <div>
                            <h3 class="mt-3 mb-2 text-gray-700 font-bold text-2xl">Esse necessitatibus, dolores ipsum a similique commodi.</h3>
                            <p class="text-gray-700">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Esse necessitatibus, odio fuga assumenda aperiam quas voluptatem voluptates illum repudiandae dolores ipsum a similique commodi. Explicabo a blanditiis saepe corrupti animi?</p>
                        </div>
                    </div>
                </a>

                <a class="block mb-10" href="#">
                    <div class="flex">
                        <div>
                            <h3 class="mt-3 mb-2 text-gray-700 font-bold text-2xl">Lorem ipsum dolor sit, amet consectetur adipisicing elit.</h3>
                            <p class="text-gray-700">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Esse necessitatibus, odio fuga assumenda aperiam quas voluptatem voluptates illum repudiandae dolores ipsum a similique commodi. Explicabo a blanditiis saepe corrupti animi?</p>
                        </div>
                    </div>
                </a>

                <a class="block mb-10" href="#">
                    <div class="flex">
                        <div>
                            <h3 class="mt-3 mb-2 text-gray-700 font-bold text-2xl">Esse necessitatibus, dolores ipsum a similique commodi.</h3>
                            <p class="text-gray-700">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Esse necessitatibus, odio fuga assumenda aperiam quas voluptatem voluptates illum repudiandae dolores ipsum a similique commodi. Explicabo a blanditiis saepe corrupti animi?</p>
                        </div>
                    </div>
                </a>
            </section>

            <!-- pagination -->
            <div class="mt-20 mb-10">
                <ul class="flex justify-center">
                    <li><a class="pagination-item rounded-l-lg" href="#">Previous</a></li>
                    <li><a class="pagination-item" href="#">1</a></li>
                    <li><a class="pagination-item" href="#">2</a></li>
                    <li><a class="pagination-item text-blue-600 hover:bg-blue-100 hover:text-blue-600" href="#">3</a></li>
                    <li><a class="pagination-item" href="#">4</a></li>
                    <li><a class="pagination-item" href="#">5</a></li>
                    <li><a class="pagination-item rounded-r-lg" href="#">Next</a></li>
                </ul>
            </div>

            <!-- divider -->
            <div class="border border-dotted"></div>

            <!-- subscribe -->
            <div class="my-5">
                <h5 class="font-bold text-lg uppercase text-gray-700 mb-2">Subscribe</h5>
                <p class="text-gray-600 mb-4">Subscribe to our newsletter.</p>
                <input
                    class="text-gray-700 bg-gray-100 p-2 w-full border-2 rounded-t hover:border-gray-700"
                    placeholder="Your email address"
                    type="email">
                <button class="px-4 py-2 bg-indigo-600 text-white rounded-b w-full hover:bg-indigo-800">Subscribe</button>
            </div>

            <!-- divider -->
            <div class="border border-dotted"></div>
        </main>

        <footer class="border-t mt-10 py-10 px-2">
            <div>
                <div class="mb-5">
                    <h6 class="font-semibold text-gray-700 mb-4">Company</h6>
                    <ul>
                        <li><a class="footer-link" href="#">Team</a></li>
                        <li><a class="footer-link" href="#">About as</a></li>
                    </ul>
                </div>
                <div>
                    <h6 class="font-semibold text-gray-700 mb-4">Contenty</h6>
                    <ul>
                        <li><a class="footer-link" href="#">Blog</a></li>
                        <li><a class="footer-link" href="#">Policy</a></li>
                        <li><a class="footer-link" href="#">Documentation</a></li>
                    </ul>
                </div>
            </div>
        </footer>

    </body>
</html>
