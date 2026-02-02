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

                <!-- Steps container -->
                <div class="flex flex-col items-center justify-center m-auto py-10 w-full">
                    <!-- Progress bar -->
                    <div class="w-full max-w-3xl mb-6">
                        <div id="stepsProgress" class="flex items-center justify-between">
                            <div class="step-item text-center">
                                <div class="step-dot" data-step="1"></div>
                                <div class="text-sm mt-2">Select type</div>
                            </div>
                            <div class="flex-1 h-0.5 bg-gray-200 mx-2" data-connector="1-2"></div>
                            <div class="step-item text-center">
                                <div class="step-dot" data-step="2"></div>
                                <div class="text-sm mt-2">Fill details</div>
                            </div>
                            <div class="flex-1 h-0.5 bg-gray-200 mx-2" data-connector="2-3"></div>
                            <div class="step-item text-center">
                                <div class="step-dot" data-step="3"></div>
                                <div class="text-sm mt-2">Preview</div>
                            </div>
                        </div>
                    </div>

                    <div class="border border-white h-full w-full bg-white p-5 rounded-[10px]">
                        <div id="stepsContainer">
                            <?php include(__DIR__ . '/../components/steps/select_document.php'); ?>
                            <?php include(__DIR__ . '/../components/steps/fill_details.php'); ?>
                            <?php include(__DIR__ . '/../components/steps/preview.php'); ?>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </section>

    <script src="../js/navbar.js"></script>
    <script src="../js/document.js"></script>
</body>

</html>