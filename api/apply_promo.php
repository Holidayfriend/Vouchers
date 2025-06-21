<?php
include '../util_config.php';
include '../util_session.php';

header('Content-Type: application/json');

$code = isset($_POST['code']) ? trim($_POST['code']) : '';
$user_code = isset($_POST['user_id']) ? trim($_POST['user_id']) : '';
$voucher_id = isset($_POST['voucher_id']) ? intval($_POST['voucher_id']) : 0;
$response = ['success' => false, 'message' => 'Invalid or expired promo code.', 'sql' => ''];

if ($code && $user_code && $voucher_id) {
    // Get user_id from user_code
    $sql_user = "SELECT user_id FROM tbl_user WHERE user_code = '$user_code'";
    $response['sql'] = $sql_user;
    $result_user = $conn->query($sql_user);
    
    if ($result_user && $result_user->num_rows > 0) {
        $user = $result_user->fetch_assoc();
        $user_id = $user['user_id'];
        
        // Get category_id from voucher_id
        $sql_voucher = "SELECT cat_id FROM tbl_voucher WHERE id = $voucher_id AND user_id = $user_id AND is_delete = 0";
        $response['sql'] .= "; $sql_voucher";
        $result_voucher = $conn->query($sql_voucher);
        
        if ($result_voucher && $result_voucher->num_rows > 0) {
            $voucher = $result_voucher->fetch_assoc();
            $cat_id = $voucher['cat_id'];
            
            // Validate promo code
            $sql_promo = "SELECT promo_code_id, discount_type, discount_value, max_uses, current_uses, voucher_id, category_id, what
                          FROM tbl_promocodes
                          WHERE user_id = $user_id 
                          AND code = '$code' 
                          AND is_delete = 0 
                          AND start_date <= NOW() 
                          AND end_date >= NOW()";
            $response['sql'] .= "; $sql_promo";
            
            $result_promo = $conn->query($sql_promo);
            if ($result_promo && $result_promo->num_rows > 0) {
                $row = $result_promo->fetch_assoc();
                if ($row['current_uses'] < $row['max_uses'] || $row['max_uses'] == 0) {
                    if ($row['what'] == 'all' || 
                        ($row['what'] == 'voucher' && $row['voucher_id'] == $voucher_id) || 
                        ($row['what'] == 'category' && $row['category_id'] == $cat_id)) {
                        $response['success'] = true;
                        $response['message'] = 'Promo code applied.';
                        $response['promo_code_id'] = $row['promo_code_id'];
                        $response['discount_type'] = $row['discount_type'];
                        $response['discount_value'] = $row['discount_value'];
                    } else {
                        $response['message'] = 'Promo code not applicable to this voucher or category.';
                    }
                } else {
                    $response['message'] = 'Promo code has reached maximum usage limit.';
                }
            }
        } else {
            $response['message'] = 'Invalid voucher.';
        }
    } else {
        $response['message'] = 'Invalid user.';
    }
}

echo json_encode($response);
?>