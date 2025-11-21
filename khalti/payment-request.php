<?php
session_start();
header('Content-Type: application/json');

// Only run if form is submitted
if (isset($_POST['submit'])) {

    // Collect and sanitize user inputs
    $amount = isset($_POST['inputAmount4']) ? intval($_POST['inputAmount4']) : 0;
    $orderId = isset($_POST['inputPurchasedOrderId4']) ? trim($_POST['inputPurchasedOrderId4']) : '';
    $name = isset($_POST['inputName']) ? trim($_POST['inputName']) : 'Guest';
    $email = isset($_POST['inputEmail']) ? trim($_POST['inputEmail']) : 'guest@example.com';
    $phone = isset($_POST['inputPhone']) ? trim($_POST['inputPhone']) : '0000000000';

    // Validate essential inputs
    if ($amount <= 0 || empty($orderId) || empty($phone)) {
        echo json_encode(['success' => false, 'message' => 'Invalid input data.']);
        exit;
    }

    // Prepare the Khalti payload
    $payload = [
        "amount" => $amount * 100, // Rs to paisa
        "purchase_order_id" => $orderId,
        "purchase_order_name" => "Cart Payment",
        "customer_info" => [
            "name" => $name,
            "email" => $email,
            "phone" => $phone
        ],
        "amount_breakdown" => [
            [
                "label" => "Total Amount",
                "amount" => $amount * 100
            ]
        ],
        "payment_preferences" => ["KHALTI"],
        "return_url" => "https://localhost/PHPSITE/payment/success.php",
        "website_url" => "https://localhost/PHPSITE"
    ];

    // Initiate cURL request
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://a.khalti.com/api/v2/epayment/initiate/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => [
            'Authorization: Key 428dd873cc07496788d635edba3aa0bc', // ⚠ Replace with environment-secured key in production
            'Content-Type: application/json'
        ]
    ]);

    // Execute and close
    $response = curl_exec($curl);
    $error = curl_error($curl);
    curl_close($curl);

    if ($error) {
        echo json_encode(['success' => false, 'message' => 'Curl error: ' . $error]);
    } else {
        $responseData = json_decode($response, true);

        if (isset($responseData['error_key'])) {
            echo json_encode(['success' => false, 'message' => 'Khalti error: ' . $responseData['detail']]);
        } else {
            echo json_encode([
                'success' => true,
                'message' => 'Payment initiated successfully.',
                'payment_url' => $responseData['payment_url'],
                'data' => $responseData
            ]);
        }
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Form not submitted.']);
}
?>