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
                <form method="post" action="login.php">
                    <label class="block mb-3">
                        <span class="text-sm font-medium">Username or Email</span>
                        <input name="username" type="text" required class="mt-1 p-2 border rounded w-full" />
                    </label>
                    <label class="block mb-3">
                        <span class="text-sm font-medium">Password</span>
                        <input name="password" type="password" required class="mt-1 p-2 border rounded w-full" />
                    </label>
                    <div class="flex items-center justify-between mb-4">
                        <label class="text-sm"><input type="checkbox" name="remember" class="mr-1"> Remember me</label>
                        <a href="#" class="text-sm text-[--color-primary]">Forgot?</a>
                    </div>
                    <button type="submit"
                        class="w-full px-4 py-2 bg-[--color-primary] text-white rounded">Login</button>
                </form>

                <p class="text-xs text-gray-500 mt-4">This login form is UI-only. Implement authentication in
                    server-side handler.</p>
            </div>
        </div>
    </section>
</body>

</html>