<?php
include '../util_config.php'; // Database connection

header('Content-Type: application/json'); // Set JSON response header

try {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        throw new Exception("Invalid request method");
    }

    // Get and validate cart data
    $cartData = json_decode($_POST['cart'] ?? '', true);
    if (empty($cartData) || !is_array($cartData) || !isset($cartData[0])) {
        throw new Exception("Invalid cart data");
    }

    $cart = $cartData[0]; // First item in cart

    // Extract data - DON'T use htmlspecialchars for HTML content
    $voucher_id = filter_var($cart['voucher_id'] ?? null, FILTER_VALIDATE_INT);
    $title = filter_var($cart['title'] ?? '', FILTER_SANITIZE_STRING); // Sanitize but keep as text
    $amount = filter_var($cart['amount'] ?? null, FILTER_VALIDATE_FLOAT);
    $description = $cart['description'] ?? ''; // Keep HTML intact
    $ourDescription = $cart['ourDescription'] ?? ''; // Keep HTML intact
    $quantity = filter_var($cart['quantity'] ?? null, FILTER_VALIDATE_INT);

    // Process image URL
    $image = $cart['image'] ?? '';
    $image = str_replace(
        ['http://localhost/vouchers/', 'https://vouchers.qualityfriend.solutions/', '../'],
        ['', '', './'],
        $image
    );

    // Validate voucher_id
    if (!$voucher_id) {
        throw new Exception("Invalid voucher ID");
    }

    // Prepare SQL statement to prevent SQL injection
    $sql = "SELECT a.id, a.user_id, b.logo ,a.active_to,a.create_at,a.is_recurring,a.recurring_end_day,a.recurring_end_month
            FROM tbl_voucher AS a 
            INNER JOIN tbl_user AS b ON a.user_id = b.user_id 
            WHERE a.id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $voucher_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Determine base URL
    $base = ($_SERVER['HTTP_HOST'] === 'localhost')
        ? 'http://localhost/vouchers/'
        : 'https://vouchers.qualityfriend.solutions/';

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_image = $base . $row['logo'];

        $active_to = $row['active_to'];
        $create_at = $row['create_at'];

        $is_recurring = $row['is_recurring'];
        $recurring_end_day = $row['recurring_end_day'];
        $recurring_end_month = $row['recurring_end_month'];
        $current_year = date('Y');

        $valid = '';


        if ($is_recurring == 1) {
            $valid = sprintf('%02d/%02d/%04d', $recurring_end_day, $recurring_end_month, $current_year);
        } else {
            if ($active_to == '' || is_null($active_to)) {
                $valid = date('d/m/Y', strtotime($create_at . ' +1 year'));
            } else {
                $valid = date('d/m/Y', strtotime($active_to));
            }
        }







        // Return success response with JSON_UNESCAPED_SLASHES to preserve HTML
        echo json_encode([
            'success' => true,
            'data' => [
                'voucher_id' => $voucher_id,
                'title' => $title,
                'amount' => $amount,
                'description' => $description,
                'ourDescription' => $ourDescription,
                'image' => $base . $image,
                'quantity' => $quantity,
                'user_image' => $user_image,
                'valid' => $valid
            ]
        ], JSON_UNESCAPED_SLASHES);
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'No voucher found'
        ]);
    }

    $stmt->close();

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

$conn->close();
?>