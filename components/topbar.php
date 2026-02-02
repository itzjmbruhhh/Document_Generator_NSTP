<?php
// Reusable topbar component
// Optional variables: $topTitle (string) and $topSubtitle (string)
require_once __DIR__ . '/../helper/config.php';
if (session_status() === PHP_SESSION_NONE)
    session_start();

if (!isset($topTitle))
    $topTitle = 'Document';
if (!isset($topSubtitle))
    $topSubtitle = 'Select type';

?>
<header class="topbar h-20 bg-white shadow-md flex items-center justify-between px-6">
    <div class="flex flex-col">
        <div id="pageTitle" class="text-lg font-semibold flex items-center gap-2">
            <span class="text-gray-700"><?php echo htmlspecialchars($topTitle); ?></span>
            <i class="las la-angle-right text-gray-400"></i>
            <span id="selectedDocLabel"
                class="text-[--color-primary] font-medium"><?php echo htmlspecialchars($topSubtitle); ?></span>
        </div>
    </div>

    <div class="flex items-center gap-3 relative">
        <div class="text-right mr-2">
            <div class="text-sm text-gray-500">Hello,</div>
            <div class="font-medium"><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></div>
        </div>

        <button id="topbarAvatarBtn" class="topbar-avatar" aria-expanded="false" aria-haspopup="true">
            <?php if (!empty($_SESSION['admin_photo'])): ?>
                <img src="<?php echo htmlspecialchars($_SESSION['admin_photo']); ?>" alt="avatar" class="rounded-full"
                    width="40" height="40" />
            <?php else: ?>
                <!-- simple avatar fallback SVG -->
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                    class="rounded-full bg-gray-200">
                    <circle cx="12" cy="8" r="3.2" fill="#d1d5db"></circle>
                    <path d="M4 20c0-3.314 2.686-6 6-6h4c3.314 0 6 2.686 6 6" fill="#d1d5db"></path>
                </svg>
            <?php endif; ?>
        </button>

        <div id="topbarDropdown" class="topbar-dropdown hidden">
            <a href="<?php echo htmlspecialchars(base_url('logout.php')); ?>"
                class="block px-4 py-2 hover:bg-gray-100">Logout</a>
        </div>
    </div>
</header>