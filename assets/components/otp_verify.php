<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
function takeOtp($string)
{
    echo '<div class="flex justify-center items-center w-screen h-screen"><div class="bg-white p-8 rounded-2xl shadow-lg">
    <h2 class="text-2xl font-bold text-center mb-6">OTP SENT</h2>
    <form action="" method="GET" class="flex flex-col  space-x-4">
        <div>
        <input type="text" name="1" maxlength="1" class="w-[4vw] h-14 text-center text-2xl border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500" required autofocus>
        <input type="text" name="2" maxlength="1" class="w-[4vw] h-14 text-center text-2xl border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500" required>
        <input type="text" name="3" maxlength="1" class="w-[4vw] h-14 text-center text-2xl border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500" required>
        <input type="text" name="4" maxlength="1" class="w-[4vw] h-14 text-center text-2xl border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500" required>
        </div>
        <button type="submit" name="v_no" value=' . $string . ' class="mt-6 w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition duration-300">
        Verify OTP
    </button>
    </form>
</div></div>';
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['1']) && isset($_GET['2']) && isset($_GET['3']) && isset($_GET['4']) && isset($_GET['v_no'])) {
    $cred = explode(",", $_GET['v_no']);
    $v_no = $cred[1];
    $otp = $cred[0];
    $v_f = $cred[2];
    $table = $cred[3];
    $otp_verify = $_GET['1'] . $_GET['2'] . $_GET['3'] . $_GET['4'];

    if ($otp_verify == $otp) {
        $sql = "UPDATE `$table` SET `status`='email verified' WHERE `$v_f` = '$v_no'";
        mysqli_query($conn, $sql);
        echo "<script>alert('YOUR EMAIL IS SUCCESSFULLY VERIFIED'); window.location.href = '/Fine-T/Interface/home.php';</script>";
    } else {
        $sql = "DELETE FROM `$table` WHERE `$v_f` = '$v_no'";
        mysqli_query($conn, $sql);
        echo "<script>alert('YOUR EMAIL IS NOT SUCCESSFULLY VERIFIED'); window.location.href = '/Fine-T/Interface/home.php';</script>";
    }
}

?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const otpInputs = document.querySelectorAll('input[name="1"], input[name="2"], input[name="3"], input[name="4"]');

    otpInputs.forEach((input, index) => {
        input.addEventListener('input', (e) => {
            if (e.target.value.length === 1 && index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
            }
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !e.target.value && index > 0) {
                otpInputs[index - 1].focus();
            }
        });
    });
});
</script>

</body>
</html>