<?php
require_once __DIR__ . '/helper/conn.php';
require_once __DIR__ . '/helper/auth.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="style.css">
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
    <title>Dashboard - Brgy. Balintawak Document Generator</title>
</head>

<body>
    <section class="flex min-h-screen">
        <?php
        include('components/navbar.php');
        ?>

        <!-- Main content -->
        <div id="mainContent" class="flex-1 transition-all duration-300 bg-[--color-gray]">

            <div class="w-[100%]">

                <?php
                // include reusable topbar
                $topTitle = 'Welcome';
                $topSubtitle = 'Brgy. Balintawak Document Generator';
                include_once __DIR__ . '/components/topbar.php';
                ?>

                <!-- Landing hero -->
                <main class="p-8">
                    <section class="bg-white rounded-lg shadow p-10">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                            <div>
                                <h1 class="text-4xl md:text-5xl font-extrabold text-[--color-primary] mb-4">Brgy.
                                    Balintawak
                                    Document Generator</h1>
                                <p class="text-lg text-gray-700 mb-6">A simple, fast tool built by students to make
                                    community services easier — creating documents, keeping records, and helping our
                                    barangay move toward a better future.</p>
                                <blockquote class="pl-4 border-l-4 border-[--color-primary] text-gray-600 italic mb-6">
                                    "Made by
                                    students, for our community — empowering a better future, one document at a time."
                                </blockquote>

                                <div class="flex flex-col sm:flex-row gap-3">
                                    <a href="<?php echo htmlspecialchars(base_url('pages/document.php')); ?>"
                                        class="inline-block px-6 py-3 bg-[--color-primary] text-white rounded shadow">Generate
                                        Document</a>
                                    <a href="<?php echo htmlspecialchars(base_url('pages/residents.php')); ?>"
                                        class="inline-block px-6 py-3 border border-[--color-primary] text-[--color-primary] rounded">Manage
                                        Residents</a>
                                </div>
                            </div>

                            <div>
                                <img class="mx-auto h-[350px]" src="<?php echo htmlspecialchars(base_url('src/images/hero.png')); ?>"
                                    alt="community" class="w-full rounded">
                            </div>
                        </div>
                    </section>

                    <section class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="bg-white p-6 rounded shadow">
                            <h3 class="font-semibold text-lg">Fast Generation</h3>
                            <p class="text-sm text-gray-600 mt-2">Create common barangay documents quickly with
                                prebuilt templates.</p>
                        </div>
                        <div class="bg-white p-6 rounded shadow">
                            <h3 class="font-semibold text-lg">Record Requests</h3>
                            <p class="text-sm text-gray-600 mt-2">Saved requests allow tracking and re-downloading PDFs
                                as
                                needed.</p>
                        </div>
                        <div class="bg-white p-6 rounded shadow">
                            <h3 class="font-semibold text-lg">Resident Registry</h3>
                            <p class="text-sm text-gray-600 mt-2">Manage residents, search quickly, and reuse data when
                                generating documents.</p>
                        </div>
                    </section>
                </main>

            </div>

        </div>
    </section>

    <script src="js/navbar.js"></script>
    <script src="js/topbar.js"></script>
</body>

</html>