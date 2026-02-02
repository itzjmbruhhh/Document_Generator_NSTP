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
<header class="topbar h-20 bg-white shadow-md flex items-center justify-between px-6"
    data-base-url="<?php echo htmlspecialchars(base_url()); ?>">
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
            <div id="topbarAdminName" class="font-medium">
                <?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></div>
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
            <a href="#" id="topbarSettingsLink" class="block px-4 py-2 hover:bg-gray-100">Settings</a>
            <a href="<?php echo htmlspecialchars(base_url('logout.php')); ?>"
                class="block px-4 py-2 hover:bg-gray-100">Logout</a>
        </div>

        <!-- Settings modal -->
        <div id="topbarSettingsModal"
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 hidden">
            <div class="bg-white rounded-md w-full max-w-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Account Settings</h3>
                    <button id="topbarSettingsClose" class="text-gray-500 hover:text-gray-700">&times;</button>
                </div>

                <form id="topbarSettingsForm" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Display Name</label>
                        <input type="text" name="admin_name" id="settingsName"
                            class="mt-1 block w-full border rounded px-3 py-2" required />
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">New Password (leave blank to
                            keep)</label>
                        <input type="password" name="admin_password" id="settingsPassword"
                            class="mt-1 block w-full border rounded px-3 py-2" />
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Profile Photo</label>
                        <div class="flex items-center gap-4 mt-2">
                            <img id="settingsPhotoPreview" src="" alt="preview"
                                class="w-16 h-16 rounded-full object-cover bg-gray-100" />
                            <input type="file" name="admin_photo" id="settingsPhoto" accept="image/*" />
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" id="settingsCancel" class="px-4 py-2 bg-gray-100 rounded">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-[--color-primary] text-white rounded">Save
                            changes</button>
                    </div>
                </form>
            </div>
        </div>

        <script src="<?php echo htmlspecialchars(base_url('js/topbar-settings.js')); ?>"></script>
    </div>
</header>