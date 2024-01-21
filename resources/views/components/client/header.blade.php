<header class="bg-gray-700 px-4 py-2 h-16 sticky top-0 z-50">
    <nav class="flex justify-between items-center gap-4">
        <div class="pl-20">
            <a href="/libgen" class="flex items-center">
                <img src="{{ asset('logo.png') }}" alt="LibGen Logo" class="h-12 w-12 object-cover">
                <span class="text-red-600 font-medium text-2xl px-3">LibGen</span>
            </a>
        </div>
        <ul class="flex items-center gap-16 text-xl text-white">
            <li class="hover:text-indigo-500"><a href="/libgen">Home</a></li>
            <li class="hover:text-indigo-500"><a href="/mybooks">My Books</a></li>
        </ul>
        <ul class="flex items-center gap-9 pr-20">
            @auth
            <li class="text-white text-lg font-medium px-2 py-1 rounded-md hover:text-indigo-600">
                <a href="/cart" title="Cart">
                    <x-icons.cart class="w-7 h-7" />
                </a>
            </li>
            <li class="text-white text-lg font-medium px-2 py-1 rounded-md hover:text-indigo-600">
                <a href="/settings" title="Settings">
                    <x-icons.settings class="w-7 h-7" />
                </a>
            </li>
            @else
            <li
                class="bg-indigo-600 text-white text-lg font-medium px-2 py-1 rounded-md hover:bg-white hover:text-indigo-600">
                <a href="/register">Sign Up</a>
            </li>
            <li
                class="bg-indigo-600 text-white text-lg font-medium px-2 py-1 rounded-md hover:bg-white hover:text-indigo-600">
                <a href="/login">Login</a>
            </li>
            @endauth
        </ul>
    </nav>
</header>