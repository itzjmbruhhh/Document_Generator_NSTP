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
    <title>Documents - Brgy. Balintawak Document Generator</title>
</head>

<body>
    <section class="flex min-h-screen">
        <?php
        include('../components/navbar.php');
        ?>

        <!-- Main content -->
        <div id="mainContent" class="flex-1 transition-all duration-300 bg-[--color-gray]">

            <div class="w-[100%]">

                <!-- Top navbar-ish -->
                <div class="h-20 bg-white shadow-md">
                    This
                </div>

                <div class="container flex flex-col items-center justify-center m-auto py-10">
                    <div class="m-auto p-10">Progress Bar</div>
                    <div class="border border-white h-full w-full bg-white p-5 rounded-[10px]">
                        <h2 class="text-2xl font-semibold mb-4"><i
                                class="las la-file-alt text-[--color-primary] mr-2"></i>Document Options</h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <button data-doc="barangay_clearance"
                                class="flex items-center justify-between p-5 bg-[--color-gray] hover:bg-gray-100 rounded shadow-sm transition">
                                <div class="text-left">
                                    <h3 class="text-xl font-semibold">Barangay Clearance</h3>
                                    <p class="text-sm text-gray-600">Official clearance issued by the barangay.</p>
                                </div>
                                <i class="las la-id-card text-3xl text-[--color-primary]"></i>
                            </button>

                            <button data-doc="business_permit"
                                class="flex items-center justify-between p-5 bg-[--color-gray] hover:bg-gray-100 rounded shadow-sm transition">
                                <div class="text-left">
                                    <h3 class="text-xl font-semibold">Business Permit</h3>
                                    <p class="text-sm text-gray-600">Apply for or renew a business permit.</p>
                                </div>
                                <i class="las la-briefcase text-3xl text-[--color-primary]"></i>
                            </button>

                            <button data-doc="certificate_residency"
                                class="flex items-center justify-between p-5 bg-[--color-gray] hover:bg-gray-100 rounded shadow-sm transition">
                                <div class="text-left">
                                    <h3 class="text-xl font-semibold">Certificate of Residency</h3>
                                    <p class="text-sm text-gray-600">Proof of residence within the barangay.</p>
                                </div>
                                <i class="las la-home text-3xl text-[--color-primary]"></i>
                            </button>

                            <button data-doc="indigency"
                                class="flex items-center justify-between p-5 bg-[--color-gray] hover:bg-gray-100 rounded shadow-sm transition">
                                <div class="text-left">
                                    <h3 class="text-xl font-semibold">Indigency</h3>
                                    <p class="text-sm text-gray-600">Certification of indigent status.</p>
                                </div>
                                <i class="las la-hand-holding-usd text-3xl text-[--color-primary]"></i>
                            </button>

                            <button data-doc="no_low_income"
                                class="flex items-center justify-between p-5 bg-[--color-gray] hover:bg-gray-100 rounded shadow-sm transition">
                                <div class="text-left">
                                    <h3 class="text-xl font-semibold">No Income / Low Income</h3>
                                    <p class="text-sm text-gray-600">Declaration for no or low household income.</p>
                                </div>
                                <i class="las la-wallet text-3xl text-[--color-primary]"></i>
                            </button>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </section>

    <script src="../js/navbar.js"></script>
</body>

</html>