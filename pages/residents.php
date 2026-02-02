<?php
require_once __DIR__ . '/../helper/conn.php';

// Handle POST actions: delete, update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'delete' && !empty($_POST['resident_id'])) {
        $id = (int) $_POST['resident_id'];
        $stmt = $conn->prepare('DELETE FROM residents WHERE resident_id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }

    if (isset($_POST['action']) && $_POST['action'] === 'update' && !empty($_POST['resident_id'])) {
        $id = (int) $_POST['resident_id'];
        $first = trim($_POST['resident_firstname'] ?? '');
        $middle = trim($_POST['resident_middlename'] ?? '');
        $last = trim($_POST['resident_lastname'] ?? '');
        $purok = trim($_POST['resident_purok'] ?? '');
        $birth = trim($_POST['resident_birthdate'] ?? '');

        $stmt = $conn->prepare('UPDATE residents SET resident_firstname = ?, resident_middlename = ?, resident_lastname = ?, resident_purok = ?, resident_birthdate = ? WHERE resident_id = ?');
        $stmt->bind_param('sssssi', $first, $middle, $last, $purok, $birth, $id);
        $stmt->execute();
        $stmt->close();
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }
}

// Pagination params
$page = max(1, (int) ($_GET['page'] ?? 1));
$per_page = (int) ($_GET['per_page'] ?? 10);
if ($per_page < 1)
    $per_page = 10;
if ($per_page > 100)
    $per_page = 100;
$offset = ($page - 1) * $per_page;

// Total count
$total_count = 0;
$countStmt = $conn->prepare('SELECT COUNT(*) FROM residents');
$countStmt->execute();
$countStmt->bind_result($total_count);
$countStmt->fetch();
$countStmt->close();

$total_pages = $total_count > 0 ? (int) ceil($total_count / $per_page) : 1;

// Fetch rows
$stmt = $conn->prepare('SELECT resident_id, resident_firstname, resident_middlename, resident_lastname, resident_purok, resident_birthdate FROM residents ORDER BY resident_lastname ASC LIMIT ? OFFSET ?');
$stmt->bind_param('ii', $per_page, $offset);
$stmt->execute();
$result = $stmt->get_result();
$residents = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

function calc_age($dob)
{
    if (!$dob)
        return '';
    $birth = new DateTime($dob);
    $today = new DateTime();
    $diff = $today->diff($birth);
    return $diff->y;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="../style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Anton&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet"
        href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Residents - Brgy. Balintawak Document Generator</title>
</head>

<body>
    <section class="flex min-h-screen">
        <?php
        include('../components/navbar.php');
        ?>

        <!-- Main content -->
        <div id="mainContent" class="flex-1 transition-all duration-300 bg-[--color-gray]">

            <div class="w-[100%]">

                <?php
                // include reusable topbar
                $topTitle = 'Residents';
                $topSubtitle = 'All residents';
                include_once __DIR__ . '/../components/topbar.php';
                ?>

                <div class="container mx-auto p-6">
                    <div class="flex items-center justify-between mb-4">
                        <form method="get" class="flex items-center gap-2">
                            <label class="text-sm">Per page:</label>
                            <input name="per_page" type="number" min="1" max="100"
                                value="<?php echo htmlspecialchars($per_page); ?>" class="p-1 border rounded w-20">
                            <button class="px-3 py-1 bg-[--color-primary] text-white rounded">Apply</button>
                        </form>

                        <div class="text-sm">Total residents: <strong><?php echo (int) $total_count; ?></strong></div>
                    </div>

                    <div class="overflow-x-auto bg-white p-4 rounded shadow">
                        <table class="min-w-full">
                            <thead>
                                <tr class="text-left">
                                    <th class="p-2">ID</th>
                                    <th class="p-2">Full name</th>
                                    <th class="p-2">Purok</th>
                                    <th class="p-2">Birthdate</th>
                                    <th class="p-2">Age</th>
                                    <th class="p-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($residents as $r):
                                    $age = calc_age($r['resident_birthdate']);
                                    $fullname = trim($r['resident_firstname'] . ' ' . ($r['resident_middlename'] ? $r['resident_middlename'] . ' ' : '') . $r['resident_lastname']);
                                    ?>
                                    <tr class="border-t">
                                        <td class="p-2 align-top"><?php echo (int) $r['resident_id']; ?></td>
                                        <td class="p-2 align-top"><?php echo htmlspecialchars($fullname); ?></td>
                                        <td class="p-2 align-top"><?php echo htmlspecialchars($r['resident_purok']); ?></td>
                                        <td class="p-2 align-top"><?php echo htmlspecialchars($r['resident_birthdate']); ?>
                                        </td>
                                        <td class="p-2 align-top"><?php echo htmlspecialchars($age); ?></td>
                                        <td class="p-2 align-top">
                                            <div class="flex gap-2">
                                                <button type="button"
                                                    class="px-3 py-1 bg-yellow-500 text-white rounded edit-btn"
                                                    data-id="<?php echo (int) $r['resident_id']; ?>"
                                                    data-first="<?php echo htmlspecialchars($r['resident_firstname']); ?>"
                                                    data-middle="<?php echo htmlspecialchars($r['resident_middlename']); ?>"
                                                    data-last="<?php echo htmlspecialchars($r['resident_lastname']); ?>"
                                                    data-purok="<?php echo htmlspecialchars($r['resident_purok']); ?>"
                                                    data-birth="<?php echo htmlspecialchars($r['resident_birthdate']); ?>">Edit</button>

                                                <form method="post" onsubmit="return confirm('Delete this resident?');">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="resident_id"
                                                        value="<?php echo (int) $r['resident_id']; ?>">
                                                    <button type="submit"
                                                        class="px-3 py-1 bg-red-600 text-white rounded">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="flex items-center justify-between mt-4">
                        <div>
                            <form method="get" class="flex items-center gap-2">
                                <input type="hidden" name="per_page" value="<?php echo htmlspecialchars($per_page); ?>">
                                <button class="px-3 py-1 border rounded" <?php if ($page <= 1)
                                    echo 'disabled'; ?>
                                    name="page" value="<?php echo max(1, $page - 1); ?>">&laquo; Prev</button>
                                <span class="px-3">Page <?php echo $page; ?> / <?php echo $total_pages; ?></span>
                                <button class="px-3 py-1 border rounded" <?php if ($page >= $total_pages)
                                    echo 'disabled'; ?> name="page" value="<?php echo min($total_pages, $page + 1); ?>">Next
                                    &raquo;</button>
                            </form>
                        </div>

                        <div class="text-sm">Go to page:
                            <form method="get" class="inline-block ml-2">
                                <input type="number" name="page" min="1" max="<?php echo $total_pages; ?>"
                                    value="<?php echo $page; ?>" class="w-20 p-1 border rounded">
                                <input type="hidden" name="per_page" value="<?php echo htmlspecialchars($per_page); ?>">
                                <button class="px-3 py-1 border rounded">Go</button>
                            </form>
                        </div>
                    </div>

                    <!-- Edit modal -->
                    <div id="editModal"
                        class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                        <div class="bg-white p-6 rounded w-full max-w-md">
                            <h3 class="text-lg font-semibold mb-4">Edit Resident</h3>
                            <form id="editForm" method="post">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="resident_id" id="edit_resident_id">

                                <label class="block mb-2">
                                    <div class="text-sm">First name</div>
                                    <input type="text" name="resident_firstname" id="edit_first"
                                        class="mt-1 p-2 border rounded w-full">
                                </label>
                                <label class="block mb-2">
                                    <div class="text-sm">Middle name</div>
                                    <input type="text" name="resident_middlename" id="edit_middle"
                                        class="mt-1 p-2 border rounded w-full">
                                </label>
                                <label class="block mb-2">
                                    <div class="text-sm">Last name</div>
                                    <input type="text" name="resident_lastname" id="edit_last"
                                        class="mt-1 p-2 border rounded w-full">
                                </label>
                                <label class="block mb-2">
                                    <div class="text-sm">Purok</div>
                                    <input type="text" name="resident_purok" id="edit_purok"
                                        class="mt-1 p-2 border rounded w-full">
                                </label>
                                <label class="block mb-2">
                                    <div class="text-sm">Birthdate</div>
                                    <input type="date" name="resident_birthdate" id="edit_birth"
                                        class="mt-1 p-2 border rounded w-full">
                                </label>

                                <div class="mt-4 flex justify-end gap-2">
                                    <button type="button" id="editCancel"
                                        class="px-4 py-2 border rounded">Cancel</button>
                                    <button type="submit"
                                        class="px-4 py-2 bg-[--color-primary] text-white rounded">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <script src="../js/navbar.js"></script>
    <script src="../js/topbar.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const editModal = document.getElementById('editModal');
            const editCancel = document.getElementById('editCancel');

            document.querySelectorAll('.edit-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.getElementById('edit_resident_id').value = btn.dataset.id || '';
                    document.getElementById('edit_first').value = btn.dataset.first || '';
                    document.getElementById('edit_middle').value = btn.dataset.middle || '';
                    document.getElementById('edit_last').value = btn.dataset.last || '';
                    document.getElementById('edit_purok').value = btn.dataset.purok || '';
                    document.getElementById('edit_birth').value = btn.dataset.birth || '';
                    if (editModal) editModal.classList.remove('hidden');
                });
            });

            if (editCancel) editCancel.addEventListener('click', () => editModal.classList.add('hidden'));

            // close when clicking outside modal content
            window.addEventListener('click', (e) => {
                if (e.target === editModal) editModal.classList.add('hidden');
            });
        });
    </script>
</body>

</html>