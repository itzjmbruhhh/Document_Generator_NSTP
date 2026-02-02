<!-- Sidebar -->
<aside id="sidebar"
    class="fixed left-0 top-0 h-screen w-64 bg-[--color-primary] border border-red-400 p-5 flex flex-col transition-all duration-300 overflow-hidden">

    <!-- Navbar Header -->
    <div class="flex flex-row items-center gap-2">
        <img id="sidebarLogo" src="src/images/balintawak-seal.png" alt="balintawak-seal"
            class="w-auto h-[60px] transition-all duration-300">
        <div id="sidebarText" class="transition-opacity duration-300">
            <h1 class="anton-regular text-white text-[27px]">BALINTAWAK</h1>
            <h2 class="anton-regular text-white text-[15px]">DOCUMENT GENERATOR</h2>
        </div>
    </div>

    <!-- Navbar Links -->
    <nav class="mt-10 flex flex-col gap-4 flex-1 transition-all duration-300" id="sidebarNav">
        <div id="navWrapper" class="flex flex-col gap-4 flex-1">
            <ul class="flex flex-col gap-2">
                <li class="p-0">
                    <a href="index.php"
                        class="text-white text-xl flex items-center gap-2 p-2 rounded hover:bg-[--color-secondary] hover:cursor-pointer transition-colors duration-200">
                        <i class="las la-home text-2xl"></i><span class="link-text">Dashboard</span>
                    </a>
                </li>
                <li class="p-0">
                    <a href="pages/document.php"
                        class="text-white text-xl flex items-center gap-2 p-2 rounded hover:bg-[--color-secondary] hover:cursor-pointer transition-colors duration-200">
                        <i class="lar la-file-alt text-2xl"></i><span class="link-text">Documents</span>
                    </a>
                </li>
                <li class="p-0">
                    <a href="pages/residents.php"
                        class="text-white text-xl flex items-center gap-2 p-2 rounded hover:bg-[--color-secondary] hover:cursor-pointer transition-colors duration-200">
                        <i class="las la-users text-2xl"></i><span class="link-text">Residents</span>
                    </a>
                </li>
            </ul>

        </div>

        <button id="collapseBtn"
            class="mt-auto text-white flex items-center gap-2 text-xl p-2 rounded hover:bg-[--color-secondary] transition-colors duration-200">
            <i class="las la-angle-double-left text-2xl"></i><span class="link-text">Collapse sidebar</span>
        </button>
    </nav>

</aside>