<?php
// Simple login page UI. Authentication not implemented here.
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Brgy. Balintawak</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../css/login.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="antialiased bg-[--color-gray]">
    <section class="flex min-h-screen">
        <!-- Left visual (80%) -->
        <div class="login-left flex-1 hidden sm:flex">
            <div class="left-overlay px-6 text-center">
                <img src="../src/images/balintawak-seal.png" alt="Seal" class="seal">
                <h1 class="text-2xl font-semibold">Brgy. Balintawak, Lipa City</h1>
            </div>
        </div>

        <!-- Right login form (~30%) -->
        <div class="right-panel bg-white p-8 w-full sm:w-[30%] shadow-lg flex items-center justify-center">
            <div style="width:100%; max-width:320px;">
                <h2 class="text-xl font-bold mb-4">Admin Login</h2>
                <?php
                session_start();
                $error = '';
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    require_once __DIR__ . '/../helper/conn.php';
                    $username = trim($_POST['username'] ?? '');
                    $password = $_POST['password'] ?? '';
                    if ($username && $password) {
                        $stmt = $conn->prepare('SELECT admin_id, admin_name, admin_password, admin_photo FROM admin WHERE admin_name = ? LIMIT 1');
                        $stmt->bind_param('s', $username);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        $row = $res->fetch_assoc();
                        $stmt->close();
                        if ($row) {
                            $hash = $row['admin_password'];
                            $ok = false;
                            if (function_exists('password_verify') && password_verify($password, $hash))
                                $ok = true;
                            // fallback to plain compare (if database stores plain text)
                            if (!$ok && $password === $hash)
                                $ok = true;
                            if ($ok) {
                                // success
                                $_SESSION['admin_logged_in'] = true;
                                $_SESSION['admin_id'] = (int) $row['admin_id'];
                                $_SESSION['admin_name'] = $row['admin_name'];
                                $_SESSION['admin_photo'] = $row['admin_photo'];
                                header('Location: ../index.php');
                                exit;
                            } else {
                                $error = 'Invalid credentials';
                            }
                        } else {
                            $error = 'Invalid credentials';
                        }
                    } else {
                        $error = 'Please enter username and password';
                    }
                }
                ?>

                <form method="post" action="login.php">
                    <label class="block mb-3">
                        <span class="text-sm font-medium">Username or Email</span>
                        <input name="username" type="text" required class="mt-1 p-2 border rounded w-full" />
                    </label>
                    <label class="block mb-3">
                        <span class="text-sm font-medium">Password</span>
                        <input name="password" type="password" required class="mt-1 p-2 border rounded w-full" />
                    </label>
                    <?php if ($error): ?>
                        <div class="mb-3 text-sm text-red-600"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>
                    <button type="submit"
                        class="w-full px-4 py-2 bg-[--color-primary] text-white rounded">Login</button>
                </form>

            </div>
        </div>
    </section>
</body>

</html>