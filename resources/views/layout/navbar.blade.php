<nav id="navbar" class="font-poppins mx-2 md:mx-4 rounded-xl bg-white bg-opacity-90 sticky top-0 z-40 transform transition-transform duration-300">
    <div class="flex justify-around md:justify-end p-4 space-x-0 md:space-x-4">
        <div class="flex justify-end">
            <div class="my-auto">
                <form method="get" action="{{ route('search') }}">
                    <div class="md:border-2 border p-1 rounded-xl px-0 md:px-4 flex">
                        <span class="my-auto">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="search" name="search" class="p-1 bg-gray-50" placeholder="Search..." />
                        <button type="submit" class=""></button>
                    </div>
                </form>
            </div>
        </div>
        <a href="{{ route('profil') }}" class="my-auto">
        <div class="flex space-x-2 md:space-x-4">
            <div class="my-auto hidden md:flex">
                <h1 class="text-sm font-base">
                    {{ auth()->user()->name }}
                </h1>
            </div>
            <div class="my-auto">
                <i class="fa fa-user my-auto"></i>
            </div>
        </div>
    </a>
        <div class="md:hidden flex justify-end items-end my-auto">
            <button id="toggle-button" class="transform transition-transform duration-300">
                <!-- Hamburger icon -->
                <svg id="menu-open" class="block" width="20px" height="40px" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 6H20M4 12H20M4 18H20" stroke="#000000" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                <!-- X icon -->
                <svg id="menu-close" class="hidden" width="20px" height="40px" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg" fill="none">
                    <path fill="#000000" fill-rule="evenodd"
                        d="M18 5a1 1 0 100-2H2a1 1 0 000 2h16zm0 4a1 1 0 100-2h-8a1 1 0 100 2h8zm1 3a1 1 0 01-1 1H2a1 1 0 110-2h16a1 1 0 011 1zm-1 5a1 1 0 100-2h-8a1 1 0 100 2h8z" />
                </svg>
            </button>
        </div>
    </div>
</nav>

<script>
    document.getElementById('toggle-button').addEventListener('click', function() {
        const sidebar = document.getElementById('sidebar');
        const menuOpen = document.getElementById('menu-open');
        const menuClose = document.getElementById('menu-close');

        // Toggle sidebar visibility
        sidebar.classList.toggle('-translate-x-full');

        // Toggle between open and close icons
        menuOpen.classList.toggle('hidden');
        menuClose.classList.toggle('hidden');

        // Add rotation animation
        menuOpen.classList.toggle('rotate');
    });
</script>
