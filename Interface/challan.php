<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Traffic Challan Status</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<link rel="stylesheet" href="../src/output.css">
<link rel="icon" type="image/x-icon" href="../assets/images/favicon.ico">
<body class=" bg-blue-950">
    <?php
    include '../includes/header.php';
    ?>
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-2xl p-6 md:p-10 flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="md:w-1/2 space-y-4">
                <h1 class="text-2xl md:text-3xl font-semibold text-blue-950">Check Traffic Challan Status & Pay Online</h1>
                <div class="bg-white rounded-lg p-4 shadow-sm shadow-white">
                    <div class="flex items-center gap-2 mb-4 ">
                        <p class="text-gray-700 text-shadow-2xs text-2xl p1">Enter vehicle number</p>
                    </div>
                    <form class="flex flex-col items-center gap-2" onsubmit="event.preventDefault(); fetchChallanDetails()">
                        <div class="flex space-x-4 justify-start w-[100%]">
                            <input type="radio" id="v_no" name="type" value="v_no" checked  onclick="setPage()">
                            <label for="type" class=" text-blue-900">Vehicle Number</label>
                            <input type="radio" id="l_no" name="type" value="l_no" onclick="setPage()">
                            <label for="type" class=" text-blue-900">License Number</label>
                            <input type="radio" id="c_no" name="type" value="c_no" onclick="setPage()">
                            <label for="type" class=" text-blue-900">Chassis Number</label>
                        </div>
                        <div class="flex justify-between items-center w-[100%]">
                            <p class="text-gray-700 p2">IND</p>
                            <input type="text" name="cred" id="cred" placeholder="DL 01 AB 12XX" class="p3 border border-gray-300 rounded-md px-4 py-2 w-[90%] focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <button class="bg-orange-500 hover:bg-orange-600 text-white rounded-md px-6 py-3 mt-4 w-full hover:scale-95 transition">
                            Get challan details
                        </button>
                    </form>
                </div>
            </div>
            <div class="md:w-1/2 flex justify-center">
                <img src="../assets/images/challan.jpg" alt="Challan" class="rounded-lg max-w-full h-auto" style="max-height: 300px;">
            </div>
        </div>

        <div class="py-8">
            <h2 class="text-2xl font-semibold text-white mb-6">Pay challan in 3 easy steps</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">1. Enter Vehicle Details</h3>
                    <p class="text-gray-600">Enter your vehicle number to check the challan status.</p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">2. Review Challan Details</h3>
                    <p class="text-gray-600">Check the challan amount and other details.</p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">3. Pay Online</h3>
                    <p class="text-gray-600">Pay the challan amount online using your preferred payment method.</p>
                </div>
            </div>
        </div>
    </div>
    <?php
    include '../includes/footer.php';
    ?>
    <script src="../JavaScript/challan.js"></script>
</body>

</html>