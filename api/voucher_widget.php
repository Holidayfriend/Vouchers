<?php include '../util_config.php'; // Database connection

// Get user_id dynamically
$user_id = 0;
$user_code = isset($_GET['define']) ? $_GET['define'] : 'k';

$lang = isset($_GET['lang']) ? $_GET['lang'] : 'en';

$type = isset($_GET['type']) ? $_GET['type'] : 'NORMAL';




// Default language is English
$translations = [
    'en' => [
        'view_voucher' => 'View Voucher',
        'valid_until' => 'Valid until',


    ],
    'de' => [
        'view_voucher' => 'Gutschein anzeigen',
        'valid_until' => 'Gültig bis',

    ],
    'it' => [
        'view_voucher' => 'Visualizza voucher',
        'valid_until' => 'Valido fino a',

    ]
];
$view_voucher = $translations[$lang]['view_voucher'];
$vu = $translations[$lang]['valid_until'];


if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
$sql = "SELECT `user_id`,`analytics_google`,`header_color`,`button_color`,`font_family`,`text_color`,`button_text_color`,`link_color`,`button_hover`
 FROM `tbl_user` WHERE `user_code` = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_code);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $user_id = $row['user_id'];
    $analytics_google = $row['analytics_google'];
    $header_color = $row['header_color'];
    $link_color = $row['link_color'];
    $button_color = $row['button_color'];
    $font_family = $row['font_family'];
    $text_color = $row['text_color'];
    $button_text_color = $row['button_text_color'];
    $button_hover = $row['button_hover'];
}
$stmt->close();

$locale = ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1');
$base = $locale ? 'http://localhost/vouchers/' : 'https://vouchers.qualityfriend.solutions/';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vouchers</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            font-family:
                <?php echo htmlspecialchars($font_family ?? 'Arial'); ?>, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }

        .voucher-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            /* Responsive grid */
            gap: 20px;
            max-width: 1000px;
            margin: auto;
        }

        .voucher {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            margin-top: 15px;
            /* Added margin to separate rows */
            overflow: hidden;
            /* Ensures image does not overflow */
        }

        .voucher img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            /* Fills the box properly */
            border-radius: 10px 10px 0 0;
            /* Matches the box shape */
            margin: 0;
            /* Removed extra padding */
        }

        .voucher-title {
            color:
                <?php echo htmlspecialchars($header_color ?? '#70706E'); ?>;
            font-size: 18px;
            font-weight: bold;
            margin: 10px 15px 5px;
            text-align: left;
        }

        .voucher-to {
            color:
                <?php echo htmlspecialchars($button_color ?? '#AF2236'); ?>;
            font-size: 10px;
            font-weight: bold;
            margin: 2px 15px 10px;
            text-align: right;
        }

        .category-title {
            color:
                <?php echo htmlspecialchars($header_color ?? '#70706E'); ?>;
            font-size: 16px;
            font-weight: bold;
            margin: 10px 15px 5px;
            text-align: left;
        }

        .voucher-description {
            color:
                <?php echo htmlspecialchars($header_color ?? '#70706E'); ?>;
            font-size: 14px;
            margin: 5px 15px;
            text-align: left;
            flex-grow: 1;
            /* Ensures equal height for all boxes */
        }

        .voucher-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 15px;
        }

        .voucher-price {
            font-size: 18px;
            font-weight: bold;
            color:
                <?php echo htmlspecialchars($button_color ?? '#AF2236'); ?>;
        }

        .btn {
            background:
                <?php echo htmlspecialchars($button_color ?? '#AF2236'); ?>;
            color:
                <?php echo htmlspecialchars($button_text_color ?? 'white'); ?>;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            transition: background 0.2s ease;
        }

        .btn:hover {
            background:
                <?php echo htmlspecialchars($button_hover ?? '#C72B42'); ?>
        }
    </style>
    <script>
        function sendHeight() {
            const height = document.body.scrollHeight;
            window.parent.postMessage({
                type: 'setHeight',
                height: height
            }, '*');
        }
        window.addEventListener('load', sendHeight);
        window.addEventListener('resize', sendHeight);
    </script>
    <?php echo $analytics_google; ?>
</head>

<body>
    <div class="voucher-container">
        <?php
        $stmt = $conn->prepare("
    SELECT a.*, b.name, b.name_it, b.name_de, b.is_fixed 
    FROM `tbl_voucher` AS a 
    INNER JOIN tbl_category AS b ON a.`cat_id` = b.id 
    WHERE a.`user_id` = ? 
    AND a.is_delete = 0 
    AND a.status = 'ACTIVE' 
    AND a.type = ? 
    AND (
        (a.is_recurring = 0 AND (
            (a.active_from != '' AND a.active_to != '' AND CURDATE() BETWEEN DATE(a.active_from) AND DATE(a.active_to))
            OR (a.active_from = '' AND a.active_to = '')
        ))
        OR (
            a.is_recurring = 1 
            AND MONTH(CURDATE()) * 100 + DAY(CURDATE()) 
                BETWEEN a.recurring_start_month * 100 + a.recurring_start_day 
                AND a.recurring_end_month * 100 + a.recurring_end_day
        )
    )
");
        $stmt->bind_param("is", $user_id, $type);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {


            $create_at = date('Y-m-d H:i:s');


            $cat_name = '';

            $active_from = $row['active_from'];
            $active_to = $row['active_to'];
            $is_recurring = $row['is_recurring'];
            $recurring_end_day = $row['recurring_end_day'];
            $recurring_end_month = $row['recurring_end_month'];
            $current_year = date('Y');

            if ($is_recurring == 1) {
                $v = sprintf('%02d/%02d/%04d', $recurring_end_day, $recurring_end_month, $current_year);
            } else {
                if ($active_to == '' || is_null($active_to)) {
                    $v = date('d/m/Y', strtotime($create_at . ' +1 year'));
                } else {
                    $v = date('d/m/Y', strtotime($active_to));
                }
            }








            if ($lang == 'en') {

                if (!empty($row['name'])) {
                    $cat_name = $row['name'];
                } elseif (!empty($row['name_it'])) {
                    $cat_name = $row['name_it'];
                } elseif (!empty($row['name_de'])) {
                    $cat_name = $row['name_de'];
                }
            } else if ($lang == 'it') {
                if (!empty($row['name_it'])) {
                    $cat_name = $row['name_it'];
                } elseif (!empty($row['name'])) {
                    $cat_name = $row['name'];
                } elseif (!empty($row['name_de'])) {
                    $cat_name = $row['name_de'];
                }
            } else {
                if (!empty($row['name_de'])) {
                    $cat_name = $row['name_de'];
                } elseif (!empty($row['name'])) {
                    $cat_name = $row['name'];
                } elseif (!empty($row['name_it'])) {
                    $cat_name = $row['name_it'];
                }
            }


            $title = '';
            if ($lang == 'en') {

                if ($row['title'] != "") {
                    $title = $row['title'];
                } else if ($row['title_it'] != "") {
                    $title = $row['title_it'];
                } else if ($row['title_de'] != "") {
                    $title = $row['title_de'];
                }
            } else if ($lang == 'it') {
                if ($row['title_it'] != "") {
                    $title = $row['title_it'];
                } else if ($row['title'] != "") {
                    $title = $row['title'];
                } else if ($row['title_de'] != "") {
                    $title = $row['title_de'];
                }
            } else {
                if ($row['title_de'] != "") {
                    $title = $row['title_de'];
                } else if ($row['title'] != "") {
                    $title = $row['title'];
                } else if ($row['title_it'] != "") {
                    $title = $row['title_it'];
                }
            }



            $description = '';


            if ($lang == 'en') {

                if ($row['description'] != "") {
                    $description = $row['description'];
                } else if ($row['description_it'] != "") {
                    $description = $row['description_it'];
                } else if ($row['description_de'] != "") {
                    $description = $row['description_de'];
                }
            } else if ($lang == 'it') {
                if ($row['description_it'] != "") {
                    $description = $row['description_it'];
                } else if ($row['description'] != "") {
                    $description = $row['description'];
                } else if ($row['description_de'] != "") {
                    $description = $row['description_de'];
                }
            } else {
                if ($row['description_de'] != "") {
                    $description = $row['description_de'];
                } else if ($row['description'] != "") {
                    $description = $row['description'];
                } else if ($row['description_it'] != "") {
                    $description = $row['description_it'];
                }
            }

        ?>
            <div class="voucher">
                <img src="<?php echo $base . htmlspecialchars($row['image']); ?>" alt="Voucher Image">
                <div class="category-title "><?php echo htmlspecialchars($cat_name); ?></div>
                <div class="voucher-title"><?php echo htmlspecialchars($title); ?></div>


                <div class="voucher-description">
                    <?php
                    $clean_desc = strip_tags($description); // Remove HTML tags
                    $display_desc = (strlen($clean_desc) > 100) ? substr($clean_desc, 0, 100) . "..." : $clean_desc;
                    echo $display_desc;
                    ?>
                </div>

                <div class="voucher-footer">
                    <div class="voucher-price">€<?php echo number_format($row['amount'], 2); ?></div>
                    <a href="voucher_details.php?voucher_id=<?php echo $row['id']; ?>&define=<?php echo $user_code; ?>&lang=<?php echo $lang; ?>"
                        class="btn"> <?php echo $view_voucher; ?></a>
                </div>
                <div class="voucher-to"><?php echo $vu; ?>:<?php echo htmlspecialchars($v); ?></div>


            </div>
        <?php }
        $stmt->close();
        ?>
    </div>
</body>

</html>