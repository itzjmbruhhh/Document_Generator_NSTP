<?php
require_once __DIR__ . '/../helper/config.php';

// Current request path (no query)
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Navigation items: label, icon class, target path (relative to project root)
$navItems = [
    ['label' => 'Dashboard', 'icon' => 'las la-home', 'path' => 'index.php'],
    ['label' => 'Documents', 'icon' => 'lar la-file-alt', 'path' => 'pages/document.php'],
    ['label' => 'Residents', 'icon' => 'las la-users', 'path' => 'pages/residents.php'],
];

function nav_is_active($itemPath, $currentPath)
{
    $target = rtrim(base_url($itemPath), '/');
    $current = rtrim($currentPath, '/');
    return $target === $current || strpos($current, $target) === 0;
}
?>

<!-- Sidebar -->
<aside id="sidebar"
    class="fixed left-0 top-0 h-screen w-64 bg-[--color-primary] p-5 flex flex-col transition-all duration-300 overflow-hidden shadow-[2px_0_8px_rgba(0,0,0,0.5)]">


    <!-- Navbar Header -->
    <div class="flex flex-row items-center gap-2">
        <img id="sidebarLogo" src="<?php echo htmlspecialchars(base_url('src/images/balintawak-seal.png')); ?>"
            alt="balintawak-seal" class="w-auto h-[60px] transition-all duration-300">
        <div id="sidebarText" class="transition-opacity duration-300">
            <h1 class="anton-regular text-white text-[27px]">BALINTAWAK</h1>
            <h2 class="anton-regular text-white text-[15px]">DOCUMENT GENERATOR</h2>
        </div>
    </div>

    <!-- Navbar Links -->
    <nav class="mt-10 flex flex-col gap-4 flex-1 transition-all duration-300" id="sidebarNav">
        <div id="navWrapper" class="flex flex-col gap-4 flex-1">
            <ul class="flex flex-col gap-2">
                <?php foreach ($navItems as $item):
                    $href = base_url($item['path']);
                    $active = nav_is_active($item['path'], $currentPath) ? 'bg-[--color-secondary]' : '';
                    ?>
                    <li class="p-0">
                        <a href="<?php echo htmlspecialchars($href); ?>"
                            class="text-white text-xl flex items-center gap-2 p-2 rounded <?php echo $active; ?> hover:cursor-pointer transition-colors duration-200">
                            <i class="<?php echo htmlspecialchars($item['icon']); ?> text-2xl"></i><span
                                class="link-text"><?php echo htmlspecialchars($item['label']); ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>

        </div>

        <button id="collapseBtn"
            class="mt-auto text-white flex items-center gap-2 text-xl p-2 rounded hover:bg-[--color-secondary] transition-colors duration-200">
            <i class="las la-angle-double-left text-2xl"></i><span class="link-text">Collapse sidebar</span>
        </button>
    </nav>

</aside>