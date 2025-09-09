<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="../src/output.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="../assets/images/favicon.ico">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<?php
include '../includes/db.php';
include '../assets/components/email_sender.php';
include '../assets/components/photo_uploader.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['first_name'] . " " . $_POST['middle_name'] . " " . $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $Address = $_POST['p_no'] . ", " . $_POST['strt'] . ", " . $_POST['stt'] . ", " . $_POST['cnt'];
    if(isset($_POST['l_no']))
        $license_no = $_POST['l_no'];
    else
        $license_no = null;
    $rc_no = $_POST['rc_no'];
    $vehicle_no = $_POST['veh_no'];
    $e_no = $_POST['e_no'];
    $chassis = $_POST['c_no'];
    $stmt = $conn->prepare("SELECT * FROM `user` WHERE `rc` = ? OR `vehicle_no` = ? OR `chassis` = ? OR `engine no` = ?");
    $stmt->bind_param("ssss",$rc_no, $vehicle_no,$chassis,$e_no);
    $result = $stmt->execute();
    if ($result) {
        $stmt->store_result();
        if ($stmt->num_rows == 0) {
            $stmt = $conn->prepare("INSERT INTO `user` ( `name` ,`email`,`phone`,`address`,`license`, `rc`, `vehicle_no`, `chassis`, `engine no`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssss", $name, $email, $phone, $Address, $license_no, $rc_no, $vehicle_no,$chassis,$e_no);
            $stmt->execute();
            $stmt->close();
        }
    }

    if (!empty($_FILES['lic_img']['tmp_name']) || (!empty($_FILES['rc_img']['tmp_name']) && !empty($_FILES['prf']['tmp_name']) && !empty($_FILES['idn']['tmp_name']))) {

        $baseUploadPath = "../assets/images/users";

        $userFolder = $baseUploadPath . "/" . $vehicle_no;

        if(isset($_POST['lic_img']))
        {
            if( !uploadFile($_FILES['lic_img'], $userFolder, "license") ){
                die("Failed to upload one or more files.");
            }
        }
        if (
            !uploadFile($_FILES['rc_img'], $userFolder, "rc") ||
            !uploadFile($_FILES['prf'], $userFolder, "photo") ||
            !uploadFile($_FILES['idn'], $userFolder, "aadhar")
        ) {

            die("Failed to upload one or more files.");
        }
    }
    sendEmail($email,$name,  'Fine-T OTP VERIFICATION', $vehicle_no, 'vehicle_no','user');
}
?>

<body class="flex flex-col items-center justify-center min-h-screen bg-gray-100 p-4">

    <form action="" method="post" class="bg-white register p-10 rounded-2xl shadow-xl w-[75vw] space-y-8" enctype="multipart/form-data">
        <div class="flex items-center w-[100%] space-x-[13vw] p-2 border-2 border-gray-50 shadow-2xl">
            <img src="../assets/images/fine-t_logo-removebg-preview.png" alt="" class="w-[10vw]">
            <p class="text-3xl font-bold text-center mb-8 underline">Registration Form</p>
        </div>
        <div class="border-2 border-gray-50 shadow-xl p-6 rounded-2xl">
            <h2 class="text-xl font-semibold mb-4">Name</h2>
            <div class="flex flex-col md:flex-row md:space-x-4 space-y-4 md:space-y-0">
                <input type="text" name="first_name" id="first_name" placeholder="First Name" required class="border border-gray-300 rounded-xl p-3 flex-1">
                <input type="text" name="middle_name" id="middle_name" placeholder="Middle Name" class="border border-gray-300 rounded-xl p-3 flex-1">
                <input type="text" name="last_name" id="last_name" placeholder="Last Name" required class="border border-gray-300 rounded-xl p-3 flex-1">
            </div>
        </div>
        <div class="border-2 border-gray-50 shadow-xl p-6 rounded-2xl">
            <h2 class="text-xl font-semibold mb-4">Contact</h2>
            <div class="space-y-4">
                <div>
                    <label for="email" class="block mb-2 font-medium">Email</label>
                    <input type="email" name="email" id="email" placeholder="Email" required class="border border-gray-300 rounded-xl p-3 w-full">
                </div>
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
            <h2 class="text-xl font-semibold mb-4">Vehicle Details</h2>
            <div class="space-y-4">
                <div>
                    <label for="c_no" class="block mb-2 font-medium">Chassis No</label>
                    <input type="text" name="c_no" id="c_no" required class="border border-gray-300 rounded-xl p-3 w-full">
                </div>
                <div>
                    <label for="e_no" class="block mb-2 font-medium">Engine No</label>
                    <input type="text" name="e_no" id="e_no" required class="border border-gray-300 rounded-xl p-3 w-full">
                </div>
                <div>
                    <label for="rc_no" class="block mb-2 font-medium">RC Number</label>
                    <input type="text" name="rc_no" id="rc_no" required class="border border-gray-300 rounded-xl p-3 w-full">
                </div>
                <div>
                    <label for="rc_img" class="block mb-2 font-medium">Upload RC</label>
                    <input type="file" name="rc_img" id="rc_img" accept="image/*, .pdf" class="border border-gray-300 rounded-xl p-3 w-full">
                </div>
                <div>
                    <label for="veh_no" class="block mb-2 font-medium">Vehicle Number</label>
                    <input type="text" name="veh_no" id="veh_no" required class="border border-gray-300 rounded-xl p-3 w-full">
                </div>
                <div>
                    <p>Do you have a License?</p>
                    <input type="radio" id="v_yes" name="type" onclick="setPage()">
                    <label for="type" class=" text-green-900">Yes</label>
                    <input type="radio" id="v_no" name="type" onclick="setPage()">
                    <label for="type" class=" text-red-900">No</label>
                </div>
                <div id="licenseSection" style="display: none;">
                    <div>
                        <label for="l_no" class="block mb-2 font-medium">License Number</label>
                        <input type="text" name="l_no" id="l_no" class="border border-gray-300 rounded-xl p-3 w-full">
                    </div>

                    <div>
                        <label for="lic_img" class="block mb-2 font-medium">Upload License</label>
                        <input type="file" name="lic_img" id="lic_img" accept="image/*, .pdf" class="border border-gray-300 rounded-xl p-3 w-full">
                    </div>
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
    </form>
    
    <script src="../JavaScript/form.js"></script>
</body>

</html>