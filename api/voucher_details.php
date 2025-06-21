<?php
include '../util_config.php';

$voucher_id = isset($_GET['voucher_id']) ? intval($_GET['voucher_id']) : 0;
$user_id = isset($_GET['define']) ? $_GET['define'] : 0;
$lang = isset($_GET['lang']) ? $_GET['lang'] : 'en';


$sql = "SELECT `user_id`,`analytics_google`,`header_color`,`button_color`,`font_family`,`text_color`,`button_text_color`,`link_color`,`button_hover`
 FROM `tbl_user` WHERE `user_code` = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
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

$translations = [
    'en' => [
        'all_vouchers' => 'All Vouchers',
        'change_image' => 'Change Image',
        'description' => 'Description',
        'amount' => 'Amount',
        'custom_message' => 'Custom Message',
        'next' => 'Next'
    ],
    'de' => [
        'all_vouchers' => 'Alle Gutscheine',
        'change_image' => 'Bild ändern',
        'description' => 'Beschreibung',
        'amount' => 'Betrag',
        'custom_message' => 'Benutzerdefinierte Nachricht',
        'next' => 'Weiter',
        'view_voucher' => 'Gutschein anzeigen'
    ],
    'it' => [
        'all_vouchers' => 'Tutti i voucher',
        'change_image' => 'Cambia immagine',
        'description' => 'Descrizione',
        'amount' => 'Importo',
        'custom_message' => 'Messaggio personalizzato',
        'next' => 'Avanti',
        'view_voucher' => 'Visualizza voucher'
    ]
];

$all_vouchers = $translations[$lang]['all_vouchers'];
$change_image = $translations[$lang]['change_image'];
$description_text = $translations[$lang]['description'];
$amount = $translations[$lang]['amount'];
$custom_message = $translations[$lang]['custom_message'];
$next = $translations[$lang]['next'];

if ($voucher_id == 0) {
    exit;

}

$stmt = $conn->prepare("SELECT * FROM tbl_voucher WHERE id = ?");
$stmt->bind_param("i", $voucher_id); // Bind the voucher_id as an integer
$stmt->execute();
$result = $stmt->get_result();
$voucher = $result->fetch_assoc();
$stmt->close();

if (!$voucher) {
    die("Voucher not found.");
}

$locale = ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1');
$base = $locale ? 'http://localhost/vouchers/' : 'https://vouchers.qualityfriend.solutions/';

// Handle Image Upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
    $upload_dir = "images/profile_img/";
    $image_name = time() . "_" . basename($_FILES["image"]["name"]);
    $target_file = $upload_dir . $image_name;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $image_url = "http://localhost/vouchers/" . $target_file;
        $conn->query("UPDATE tbl_voucher SET image='$image_name' WHERE id=$voucher_id");
        echo json_encode(["status" => "success", "image" => $image_url]);
    } else {
        echo json_encode(["status" => "error"]);
    }
    exit();
}



$cat_id = $voucher['cat_id'];
$stmt = $conn->prepare("SELECT * FROM tbl_category WHERE id = ?");
$stmt->bind_param("i", $cat_id); // Bind the voucher_id as an integer
$stmt->execute();
$result = $stmt->get_result();
$cat = $result->fetch_assoc();
$stmt->close();

$name = $cat['name'];

$is_fixed = $cat['is_fixed'];

$disable = '';
if ($is_fixed == 0) {
    $disable = '';
} else {
    $disable = 'disabled';
}


//

$title = '';
if ($lang == 'en') {

    if ($voucher['title'] != "") {
        $title = $voucher['title'];

    } else if ($voucher['title_it'] != "") {
        $title = $voucher['title_it'];
    } else if ($voucher['title_de'] != "") {
        $title = $voucher['title_de'];
    }
} else if ($lang == 'it') {
    if ($voucher['title_it'] != "") {
        $title = $voucher['title_it'];

    } else if ($voucher['title'] != "") {
        $title = $voucher['title'];
    } else if ($voucher['title_de'] != "") {
        $title = $voucher['title_de'];
    }

} else {
    if ($voucher['title_de'] != "") {
        $title = $voucher['title_de'];

    } else if ($voucher['title'] != "") {
        $title = $voucher['title'];
    } else if ($voucher['title_it'] != "") {
        $title = $voucher['title_it'];
    }

}


$description = '';


if ($lang == 'en') {

    if ($voucher['description'] != "") {
        $description = $voucher['description'];

    } else if ($voucher['description_it'] != "") {
        $description = $voucher['description_it'];
    } else if ($rovoucherw['description_de'] != "") {
        $description = $voucher['description_de'];
    }
} else if ($lang == 'it') {
    if ($voucher['description_it'] != "") {
        $description = $voucher['description_it'];

    } else if ($voucher['description'] != "") {
        $description = $voucher['description'];
    } else if ($voucher['description_de'] != "") {
        $description = $voucher['description_de'];
    }

} else {
    if ($voucher['description_de'] != "") {
        $description = $voucher['description_de'];

    } else if ($voucher['description'] != "") {
        $description = $voucher['description'];
    } else if ($voucher['description_it'] != "") {
        $description = $voucher['description_it'];
    }

}
$type = $voucher['type'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voucher Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.js"></script>

    <style>
        body {
            font-family:
                <?php echo htmlspecialchars($font_family ?? 'Arial'); ?>

        }

        .container {
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background: #fff;
        }

        .breadcrumb-item.active {
            color:
                <?php echo htmlspecialchars($link_color ?? '#70706E'); ?>
                !important;
        }

        .breadcrumb {
            color:
                <?php echo htmlspecialchars($link_color ?? '#70706E'); ?>
                !important;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .voucher-img-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .voucher-img-container img {
            width: 100%;
            max-width: 400px;
            height: auto;
            border-radius: 10px;
            border: 1px solid #ddd;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .btn {
            width: 100%;
        }

        .quantity-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 10px;
        }

        .quantity-btn {
            width: 40px;
            height: 40px;
            border: none;
            background:
                <?php echo htmlspecialchars($button_color ?? '#AF2236'); ?>
            ;
            color:
                <?php echo htmlspecialchars($button_text_color ?? 'white'); ?>
            ;
            font-size: 20px;
            cursor: pointer;
        }

        .quantity-btn:hover {
            background: #C72B42;
        }

        .quantity-btn:disabled {
            background: #ccc;
        }

        .quantity-input {
            width: 60px;
            height: 40px;
            text-align: center;
            font-size: 18px;
            border: 1px solid #ddd;
            margin: 0 5px;
        }

        .btn {
            background:
                <?php echo htmlspecialchars($button_color ?? '#AF2236'); ?>
            ;
            color:
                <?php echo htmlspecialchars($button_text_color ?? 'white'); ?>
            ;
            border: 2px solid
                <?php echo htmlspecialchars($button_color ?? '#AF2236'); ?>
                !important;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            transition: background 0.2s ease;
        }

        .text-color {
            color:
                <?php echo htmlspecialchars($text_color ?? '#70706E'); ?>
            ;
        }

      

        .btn:hover {
            background:
                <?php echo htmlspecialchars($button_hover ?? '#C72B42'); ?>
                !important;
            border: 2px solid
                <?php echo htmlspecialchars($button_hover ?? '#C72B42'); ?>
                !important;
        }

        .margein_top {
            margin-top: 8px;
        }

        .margein_b {
            margin-bottom: 8px;
        }

        .link-c {
            color:
                <?php echo htmlspecialchars($link_color ?? '#C72B42'); ?>
                !important;
        }
        .breadcrumb-item+.breadcrumb-item::before {
         color:   <?php echo htmlspecialchars($link_color ?? '#C72B42'); ?>
                !important;
        }
    </style>
    <script>
        function sendHeight() {
            const height = document.body.scrollHeight;
            window.parent.postMessage({ type: 'setHeight', height: height }, '*');
        }
        window.addEventListener('load', sendHeight);
        window.addEventListener('resize', sendHeight);
    </script>
    <?php echo $analytics_google; ?>
</head>

<body>

    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a class="link-c"
                        href="voucher_widget.php?define=<?php echo $user_id; ?>&lang=<?php echo $lang; ?>&type=<?php echo $type; ?>"><?php echo $all_vouchers; ?></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"><b><?php echo $title; ?></b></li>


            </ol>
        </nav>

        <div class="voucher-img-container">
            <img id="voucherImage" src="<?php echo $base . $voucher['image']; ?>" alt="Voucher Image">
        </div>

        <div class="form-group ">
            <label class="text-color"><?php echo $change_image ?>:</label>
            <input type="file" id="imageUpload" class="form-control margein_top" accept="image/*">
        </div>

        <div class="form-group">

            <input type="<?php echo ($type == 'NORMAL') ? 'hidden' : 'text'; ?>" id="voucherTitle"
                class="form-control margein_top" value="<?php echo htmlspecialchars($title); ?>">
        </div>


        <div class="form-group">
            <label class="margein_b text-color"><?php echo $description_text; ?>:</label>
            <textarea disabled id="ourDescription"
                class="form-control "><?php echo htmlspecialchars($description); ?></textarea>
        </div>




        <div class="form-group">
            <label class="text-color"><?php echo $amount ?>:€</label>
            <input <?php echo $disable; ?> type="number" id="voucherAmount" class="form-control margein_top"
                value="<?php echo htmlspecialchars($voucher['amount']); ?>">
        </div>

        <div class="form-group">
            <label class="margein_b text-color"><?php echo $custom_message; ?>:</label>
            <textarea id="voucherDescription" class="form-control "><?php echo ''; ?></textarea>
        </div>

        <div hidden class="quantity-container">
            <button class="quantity-btn" id="decrease">-</button>
            <input type="text" id="quantity" class="quantity-input" value="1" readonly>
            <button class="quantity-btn margein_top" id="increase">+</button>
        </div>

        <button class="btn btn-danger mt-3" id="addToCart"><?php echo $next; ?></button>
        <!-- <button class="btn btn-danger mt-3" id="emptyCart">Empty Cart</button> -->
    </div>

    <script>
        $(document).ready(function () {
            localStorage.removeItem("cart");
            $("#imageUpload").on("change", function () {
                var fileData = new FormData();
                var file = this.files[0]; // Get the selected file

                if (!file) return; // Exit if no file selected

                fileData.append("image", file); // Append file to FormData

                $.ajax({
                    url: "upload_image.php", // Your PHP upload handler
                    type: "POST",
                    data: fileData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        // Assuming response returns the new image URL

                        console.log(response);
                        $("#voucherImage").attr("src", response);

                        updateCartImage(imageUrl)
                    },
                    error: function () {
                        alert("Image upload failed. Please try again.");
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $("#voucherDescription").summernote({
                height: 150,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline']],
                    ['font', ['fontname', 'color']],
                    ['clear', ['clear']]
                ]
            });
            var type = "<?php echo $type ?>";

            var toolbar1 = [];

            // Conditionally add style group if not NORMAL
            if (type !== "NORMAL") {
                toolbar1.push(['style', ['bold', 'italic', 'underline']]);
                toolbar1.push(['font', ['fontname', 'color']]);
                toolbar1.push(['clear', ['clear']]);
                $("#ourDescription").summernote({
                    height: 150,
                    toolbar: toolbar1
                });
            } else {
                $("#ourDescription").summernote({
                    height: 150,
                    toolbar: false, // ✅ Hide toolbar
                    disableResizeEditor: true,
                    airMode: false, // Make sure airMode is off
                    callbacks: {
                        onInit: function () {
                            // Disable editing as well
                            $("#ourDescription").summernote('disable');
                        }
                    }
                });
            }

            // Always add font and clear groups




            // $("#imageUpload").change(function () {
            //     var formData = new FormData();
            //     formData.append("image", $("#imageUpload")[0].files[0]);


            //     console.log(formData);

            //     // $.ajax({
            //     //     url: "voucher_details.php?voucher_id=< ?php echo $voucher_id; ?>",
            //     //     type: "POST",
            //     //     data: formData,
            //     //     processData: false,
            //     //     contentType: false,
            //     //     success: function (response) {
            //     //         var data = JSON.parse(response);
            //     //         if (data.status === "success") {
            //     //             $("#voucherImage").attr("src", data.image);
            //     //             updateCartImage(data.image);
            //     //         } else {
            //     //             alert("Image upload failed!");
            //     //         }
            //     //     }
            //     // });
            // });

            // $("#increase").click(function () {
            //     let quantity = parseInt($("#quantity").val());
            //     $("#quantity").val(quantity + 1);
            // });

            // $("#decrease").click(function () {
            //     let quantity = parseInt($("#quantity").val());
            //     if (quantity > 1) {
            //         $("#quantity").val(quantity - 1);
            //     }
            // });

            $("#addToCart").click(function () {
                localStorage.removeItem("cart");
                let cartData = localStorage.getItem("cart");
                cartData = cartData ? JSON.parse(cartData) : [];

                if (!Array.isArray(cartData)) {
                    cartData = [];
                }

                let newVoucher = {
                    voucher_id: "<?php echo $voucher_id; ?>",
                    title: $("#voucherTitle").val(),
                    amount: $("#voucherAmount").val(),
                    ourDescription: $("#ourDescription").val(),
                    description: $("#voucherDescription").val(),
                    image: $("#voucherImage").attr("src"),
                    quantity: 1
                };

                cartData.push(newVoucher);
                localStorage.setItem("cart", JSON.stringify(cartData));

                var voucher_id = '<?php echo $voucher_id; ?>';
                var define = '<?php echo $user_id; ?>';
                var lang = '<?php echo $lang; ?>';
                window.location.href = "checkout.php?voucher_id=" + voucher_id + "&define=" + define + "&lang=" + lang;


                // alert(JSON.stringify(cartData, null, 2));
            });

            $("#emptyCart").click(function () {
                if (confirm("Are you sure you want to empty the cart?")) {
                    localStorage.removeItem("cart");
                    alert("Cart has been emptied!");
                }
            });

            function updateCartImage(imageUrl) {
                let cartData = JSON.parse(localStorage.getItem("cart")) || [];
                cartData.forEach((item) => {
                    if (item.voucher_id == "<?php echo $voucher_id; ?>") {
                        item.image = imageUrl;
                    }
                });
                localStorage.setItem("cart", JSON.stringify(cartData));
            }
        });
    </script>

</body>

</html>