<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../src/output.css">
    <link rel="icon" type="image/x-icon" href="../assets/images/favicon.ico">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-blue-950">
    <?php include '../includes/header.php'; ?>

    <div class="flex container mx-auto px-4 py-12 ">
        <div class="max-w-2xl mx-auto bg-white rounded-[40px] shadow-2xl p-8 w-[45vw]">
            <h1 class="text-3xl font-semibold text-center text-blue-950 mb-8">Create Your Account</h1>
            <form action="../Backend/sign_up_backend.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                <div class="flex space-y-2 flex-col initial">
                    <div class="flex justify-center space-x-4">
                        <p class="text-gray-700 font-medium">Role:</p>
                        <div>
                            <input type="radio" id="admin" name="type" value="admin" onclick="setPage()" checked required>
                            <label for="admin" class="text-blue-900">Admin</label>
                        </div>
                        <div>
                            <input type="radio" id="officer" name="type" value="officer" onclick="setPage()" required>
                            <label for="officer" class="text-blue-900">Officer</label>
                        </div>
                    </div>

                    <div class="flex flex-col">
                        <label for="name" class="text-gray-700 font-medium mb-2">Full Name</label>
                        <input type="text" name="name" id="name" required
                            class="border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="flex flex-col">
                        <label for="email" class="text-gray-700 font-medium mb-2">Email</label>
                        <input type="email" name="email" id="email" required
                            class="border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="flex flex-col">
                        <label for="password" class="text-gray-700 font-medium mb-2 p4">Referal(Code provided by officials)</label>
                        <input type="password" name="password" id="password" required
                            class="border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <button
                        class="bg-orange-500 hover:bg-orange-600 text-white rounded-md px-6 py-3 w-full hover:scale-95 transition" onclick="setRegister()">
                        Sign Up
                    </button>
                </div>
                <div style="display: none;" class="space-y-7 final">
        
                    <div class="border-2 border-gray-50 shadow-xl p-6 rounded-2xl">
                        <h2 class="text-xl font-semibold mb-4">Contact</h2>
                        <div class="space-y-4">
                            <div>
                                <label for="phone" class="block mb-2 font-medium">Phone</label>
                                <input type="tel" name="phone" id="phone" placeholder="Phone" required class="border border-gray-300 rounded-xl p-3 w-full">
                            </div>
                        </div>
                    </div>

                    <div class="border-2 border-gray-50 shadow-xl p-6 rounded-2xl">
                        <h2 class="text-xl font-semibold mb-4">Address</h2>
                        <div class="space-y-4">
                            <div>
                                <label for="p_no" class="block mb-2 font-medium">Plot No</label>
                                <input type="text" name="p_no" id="p_no" placeholder="Plot No" required class="border border-gray-300 rounded-xl p-3 w-full">
                            </div>
                            <div>
                                <label for="strt" class="block mb-2 font-medium">Street</label>
                                <input type="text" name="strt" id="strt" placeholder="Street" class="border border-gray-300 rounded-xl p-3 w-full">
                            </div>
                            <div>
                                <label for="stt" class="block mb-2 font-medium">State</label>
                                <input type="text" name="stt" id="stt" placeholder="State" required class="border border-gray-300 rounded-xl p-3 w-full">
                            </div>
                            <div>
                                <label for="cnt" class="block mb-2 font-medium">Country</label>
                                <input type="text" name="cnt" id="cnt" placeholder="Country" required class="border border-gray-300 rounded-xl p-3 w-full">
                            </div>
                        </div>
                    </div>
                    <div class="border-2 border-gray-50 shadow-xl p-6 rounded-2xl">
                        <h2 class="text-xl font-semibold mb-4 p1">Traffic Police</h2>
                        <div class="space-y-4">
                            <div>
                                <label for="desg" class="block mb-2 font-medium p2">Your Designation</label>
                                <input type="text" name="desg" id="desg" required class="border border-gray-300 rounded-xl p-3 w-full">
                            </div>
                            <div>
                                <label for="wstt" class="block mb-2 font-medium p3">State</label>
                                <input type="text" name="wstt" id="wstt" required class="border border-gray-300 rounded-xl p-3 w-full">
                            </div>
                        </div>
                    </div>

                    <div class="border-2 border-gray-50 shadow-xl p-6 rounded-2xl">
                        <h2 class="text-xl font-semibold mb-4">Identity</h2>
                        <div class="space-y-4">
                            <div>
                                <label for="idn" class="block mb-2 font-medium">Aadhar Card</label>
                                <input type="file" accept="image/*, .pdf" name="idn" id="idn" class="border border-gray-300 rounded-xl p-3 w-full">
                            </div>
                            <div>
                                <label for="prf" class="block mt-4 mb-2 font-medium">Passport Size photo</label>
                                <input type="file" accept="image/*, .pdf" name="prf" id="prf" class="border border-gray-300 rounded-xl p-3 w-full">
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-center">

                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-xl transition duration-300">
                            Submit
                        </button>
                    </div>

                </div>
            </form>

            <p class="text-center text-gray-600 mt-6">
                Already have an account?
                <a href="login.php" class="text-blue-600 hover:underline">Log in</a>
            </p>
        </div>

        <div class="w-[50vw] flex justify-center items-center step">
            <div class="grid grid-rows-3 h-[90vh] gap-y-4">
                <div class="bg-white h-[24vh] w-[24vw] flex flex-col justify-center items-center rounded-[35px] shadow-2xl shadow-red-400 p-6 text-center">
                    <h2 class="text-xl font-semibold text-blue-950 mb-2">Step 1: Fill Details</h2>
                    <p class="text-gray-700 text-sm">Choose your role, enter your username, email, and create a password.</p>
                </div>
                <div class="bg-white h-[24vh] w-[24vw] flex flex-col justify-center items-center rounded-[35px] shadow-2xl shadow-yellow-400 p-6 text-center">
                    <h2 class="text-xl font-semibold text-blue-950 mb-2">Step 2: Submit Form</h2>
                    <p class="text-gray-700 text-sm">After filling all fields, click "Sign Up" to create your account.</p>
                </div>
                <div class="bg-white h-[24vh] w-[24vw] flex flex-col justify-center items-center rounded-[35px] shadow-2xl shadow-green-400 p-6 text-center">
                    <h2 class="text-xl font-semibold text-blue-950 mb-2">Step 3: Verify Documents</h2>
                    <p class="text-gray-700 text-sm">After signing up, upload your verification documents for approval.</p>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
<script src="../JavaScript/sign.js"></script>
</html>