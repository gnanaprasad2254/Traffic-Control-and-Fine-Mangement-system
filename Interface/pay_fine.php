<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay Traffic Challan</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../src/output.css">
    <link rel="icon" type="image/x-icon" href="../assets/images/favicon.ico">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .payment-card {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
    </style>
</head>

<body class="bg-blue-950">
    <?php
    include '../includes/db.php';
    include '../includes/header.php';
    $challan_id = isset($_GET['challan_id']) ? intval($_GET['challan_id']) : 0;
    $query = "SELECT 
            v.*,                        
            r.violation_type AS rule_name, 
            r.description AS violation_description
          FROM 
            violation v
          JOIN 
            rules r ON v.violation_type = r.rule_id
          WHERE 
            v.violation_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $challan_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $challan = $result->fetch_assoc();
    $v_number = $challan['vehicle_number'];
    $v_fine = $challan['fine_amount'];
    $v_type = $challan['rule_name'];
    $v_date = $challan['created_at'];
    $v_loc = $challan['location'];
    $v_fin = $challan['fine_amount'];
    if ($challan_id <= 0) {
        die("Invalid challan ID");
    }
    ?>

    <div class="container mx-auto px-4 py-8">
        <!-- Challan Summary -->
        <div class="bg-white rounded-lg shadow-2xl p-6 mb-8">
            <h2 class="text-2xl font-semibold text-blue-950 mb-6">Challan Summary</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="border p-4 rounded-lg">
                    <p class="text-gray-600">Vehicle Number</p>
                    <p class="text-xl font-semibold" id="vehicle-number"><?php echo $v_number ?></p>
                </div>
                <div class="border p-4 rounded-lg">
                    <p class="text-gray-600">Challan Number</p>
                    <p class="text-xl font-semibold" id="challan-number"><?php echo $challan_id ?></p>
                </div>
                <div class="border p-4 rounded-lg">
                    <p class="text-gray-600">Total Amount</p>
                    <p class="text-xl font-semibold text-red-600" id="challan-amount"><?php echo $v_fine ?></p>
                </div>
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-2">Violation Details</h3>
                <table class="min-w-full border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-2 px-4 text-left">Violation</th>
                            <th class="py-2 px-4 text-left">Date&Time</th>
                            <th class="py-2 px-4 text-left">Location</th>
                            <th class="py-2 px-4 text-right">Fine Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-t">
                            <td class="py-2 px-4"><?php echo $v_type ?></td>
                            <td class="py-2 px-4"><?php echo $v_date ?></td>
                            <td class="py-2 px-4"><?php echo $v_loc ?></td>
                            <td class="py-2 px-4 text-right"><?php echo $v_fin ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payment Section -->
        <div class="bg-white rounded-lg shadow-2xl p-6">
            <h2 class="text-2xl font-semibold text-blue-950 mb-6">Payment Details</h2>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Payment Methods -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Select Payment Method</h3>

                    <div class="space-y-4">
                        <div class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 payment-method" onclick="selectPayment('credit-card')">
                            <input type="radio" id="credit-card" name="payment-method" class="mr-3" checked>
                            <label for="credit-card" class="flex items-center w-full cursor-pointer">
                                <img src="https://cdn-icons-png.flaticon.com/512/179/179457.png" alt="Credit Card" class="w-8 h-8 mr-3">
                                <span>Credit/Debit Card</span>
                            </label>
                        </div>

                        <div class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 payment-method" onclick="selectPayment('upi')">
                            <input type="radio" id="upi" name="payment-method" class="mr-3">
                            <label for="upi" class="flex items-center w-full cursor-pointer">
                                <img src="https://cdn-icons-png.flaticon.com/512/825/825454.png" alt="UPI" class="w-8 h-8 mr-3">
                                <span>UPI Payment</span>
                            </label>
                        </div>

                        <div class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 payment-method" onclick="selectPayment('netbanking')">
                            <input type="radio" id="netbanking" name="payment-method" class="mr-3">
                            <label for="netbanking" class="flex items-center w-full cursor-pointer">
                                <img src="https://cdn-icons-png.flaticon.com/512/888/888857.png" alt="Net Banking" class="w-8 h-8 mr-3">
                                <span>Net Banking</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Payment Form (Credit Card by default) -->
                <div id="credit-card-form" class="payment-form">
                    <h3 class="text-lg font-semibold mb-4">Card Details</h3>
                    <form id="payment-form">
                        <div class="mb-4">
                            <label for="card-number" class="block text-gray-700 mb-2">Card Number</label>
                            <input type="text" id="card-number" placeholder="1234 5678 9012 3456" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="expiry-date" class="block text-gray-700 mb-2">Expiry Date</label>
                                <input type="text" id="expiry-date" placeholder="MM/YY" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="cvv" class="block text-gray-700 mb-2">CVV</label>
                                <input type="text" id="cvv" placeholder="123" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="card-name" class="block text-gray-700 mb-2">Name on Card</label>
                            <input type="text" id="card-name" placeholder="John Doe" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <button type="button" onclick="processPayment()" class="w-full bg-orange-500 hover:bg-orange-600 text-white rounded-lg px-6 py-3 mt-4 hover:scale-95 transition">
                            Pay <?php echo $v_fin ?>
                        </button>
                    </form>
                </div>

                <!-- UPI Payment Form (Hidden by default) -->
                <div id="upi-form" class="payment-form hidden">
                    <h3 class="text-lg font-semibold mb-4">UPI Payment</h3>
                    <div class="text-center mb-6">
                        <img src="https://cdn-icons-png.flaticon.com/512/825/825454.png" alt="UPI" class="w-20 h-20 mx-auto mb-4">
                        <p class="text-gray-600 mb-4">Scan the QR code or enter your UPI ID</p>
                        <div class="bg-white p-4 rounded-lg border inline-block mb-4">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=upi://pay?pa=trafficpolice@gov&pn=Traffic%20Police&am=1500&cu=INR" alt="UPI QR Code" class="w-40 h-40">
                        </div>
                        <p class="text-gray-700 font-semibold">OR</p>
                        <div class="mt-4">
                            <input type="text" id="upi-id" placeholder="yourname@upi" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 mb-4">
                            <button type="button" onclick="processUPIPayment()" class="w-full bg-orange-500 hover:bg-orange-600 text-white rounded-lg px-6 py-3 hover:scale-95 transition">
                                Verify & Pay
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Net Banking Form (Hidden by default) -->
                <div id="netbanking-form" class="payment-form hidden">
                    <h3 class="text-lg font-semibold mb-4">Net Banking</h3>
                    <div class="mb-4">
                        <label for="bank-select" class="block text-gray-700 mb-2">Select Bank</label>
                        <select id="bank-select" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select your bank</option>
                            <option value="sbi">State Bank of India</option>
                            <option value="hdfc">HDFC Bank</option>
                            <option value="icici">ICICI Bank</option>
                            <option value="axis">Axis Bank</option>
                            <option value="pnb">Punjab National Bank</option>
                        </select>
                    </div>
                    <button type="button" onclick="processNetBanking()" class="w-full bg-orange-500 hover:bg-orange-600 text-white rounded-lg px-6 py-3 mt-4 hover:scale-95 transition">
                        Proceed to Bank
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Success Modal -->
    <div id="payment-success-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg p-8 max-w-md w-full text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h3 class="text-2xl font-semibold text-gray-800 mb-2">Payment Successful!</h3>
            <p class="text-gray-600 mb-6">Your payment of <?php echo $v_fin ?> has been processed successfully.</p>
            <div class="bg-gray-100 p-4 rounded-lg mb-6 text-left">
                <p class="text-gray-700"><span class="font-semibold">Transaction ID:</span> <?php echo $challan_id ?></p>
                <p class="text-gray-700"><span class="font-semibold">Date:</span> <?php echo date('d M Y, h:i A'); ?></p>
            </div>
            <button onclick="downloadReceipt()" class="bg-blue-500 hover:bg-blue-600 text-white rounded-lg px-6 py-2">
                Download Receipt
            </button>
            <button onclick="closeModal()" class="ml-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg px-6 py-2">
                Close
            </button>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script>
        // Select payment method
        function selectPayment(method) {
            document.querySelectorAll('.payment-form').forEach(form => {
                form.classList.add('hidden');
            });
            document.getElementById(method + '-form').classList.remove('hidden');
        }

        // Process payment (dummy function)
        function processPayment() {
            // Validate form
            const cardNumber = document.getElementById('card-number').value;
            const expiryDate = document.getElementById('expiry-date').value;
            const cvv = document.getElementById('cvv').value;
            const cardName = document.getElementById('card-name').value;

            if (!cardNumber || !expiryDate || !cvv || !cardName) {
                alert('Please fill all card details');
                return;
            }

            // Show loading
            document.getElementById('payment-form').innerHTML = `
                <div class="text-center py-8">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-orange-500 mb-4"></div>
                    <p>Processing your payment...</p>
                </div>
            `;

            // Simulate payment processing
            setTimeout(() => {
                document.getElementById('payment-success-modal').classList.remove('hidden');
            }, 2000);
        }

        // Process UPI payment (dummy function)
        function processUPIPayment() {
            const upiId = document.getElementById('upi-id').value;

            if (!upiId) {
                alert('Please enter your UPI ID');
                return;
            }

            // Show loading
            document.getElementById('upi-form').innerHTML = `
                <div class="text-center py-8">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-orange-500 mb-4"></div>
                    <p>Processing your UPI payment...</p>
                </div>
            `;

            // Simulate payment processing
            setTimeout(() => {
                document.getElementById('payment-success-modal').classList.remove('hidden');
            }, 2000);
        }

        // Process Net Banking (dummy function)
        function processNetBanking() {
            const bank = document.getElementById('bank-select').value;

            if (!bank) {
                alert('Please select your bank');
                return;
            }

            // Show loading
            document.getElementById('netbanking-form').innerHTML = `
                <div class="text-center py-8">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-orange-500 mb-4"></div>
                    <p>Redirecting to your bank...</p>
                </div>
            `;

            // Simulate payment processing
            setTimeout(() => {
                document.getElementById('payment-success-modal').classList.remove('hidden');
            }, 2000);
        }

        function downloadReceipt() {
            const challanId = <?php echo $challan_id; ?>;
            window.open(`download_reciept.php?challan_id=${challanId}`, '_blank');
        }
    </script>
</body>

</html>