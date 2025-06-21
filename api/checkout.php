<?php
// checkout.php
include '../util_config.php';

$voucher_id = isset($_GET['voucher_id']) ? intval($_GET['voucher_id']) : 0;
$user_id = isset($_GET['define']) ? $_GET['define'] : 0;

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

$lang = isset($_GET['lang']) ? $_GET['lang'] : 'en';



$sql = "SELECT b.is_fixed,a.type 
        FROM tbl_voucher AS a 
        INNER JOIN tbl_category AS b ON a.cat_id = b.id 
        WHERE a.id = $voucher_id";

$result = mysqli_query($conn, $sql);
$type = 'NORMAL';
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $is_fixed = $row['is_fixed'];
    $type = $row['type'];
} else {
    $is_fixed = 0;
}


$translations = [
    'en' => [
        'voucher_preview' => 'Voucher Preview',
        'valid_until' => 'Valid until',
        'all_vouchers' => 'All Vouchers',
        'vouchers_detail' => 'Vouchers Detail',
        'checkout' => 'Checkout',
        'cart_summary' => 'Cart Summary',
        'total_amount' => 'Total Amount',
        'billing_information' => 'Billing Information',
        'gender' => 'Gender',
        'select_gender' => 'Select Gender',
        'male' => 'Male',
        'female' => 'Female',
        'first_name' => 'First Name',
        'last_name' => 'Last Name',
        'email' => 'Email',
        'phone' => 'Phone',
        'country' => 'Country',
        'tax_number' => 'Tax Number',
        'address' => 'Address',
        'agree_terms' => 'I agree to the Terms and Conditions',
        'terms_error' => 'You must agree to the terms and conditions.',
        'pay' => 'Pay',
        'amount' => 'Amount',  // New
        'quantity' => 'Quantity',  // New
        'total' => 'Total', // New
        'select_country' => 'Select Country',
        'promo_code' => 'Promo Code', // 'Code promozionale' (it), 'Promocode' (de)
        'apply' => 'Apply', // 'Applica' (it), 'Anwenden' (de)
        'placeholder' => 'Enter your code',
        'invalid_promo' => 'Invalid or expired promo code.', // Translate accordingly
        'savings' => 'You saved €{amount}!',
        'discount' => 'Promo Code Discount'

    ],
    'de' => [
        'voucher_preview' => 'Gutschein-Vorschau',
        'valid_until' => 'Gültig bis',
        'all_vouchers' => 'Alle Gutscheine',
        'vouchers_detail' => 'Gutschein-Details',
        'checkout' => 'Kasse',
        'cart_summary' => 'Warenkorbübersicht',
        'total_amount' => 'Gesamtbetrag',
        'billing_information' => 'Rechnungsinformationen',
        'gender' => 'Geschlecht',
        'select_gender' => 'Geschlecht auswählen',
        'male' => 'Männlich',
        'female' => 'Weiblich',
        'first_name' => 'Vorname',
        'last_name' => 'Nachname',
        'email' => 'E-Mail',
        'phone' => 'Telefon',
        'country' => 'Land',
        'tax_number' => 'Steuernummer',
        'address' => 'Adresse',
        'agree_terms' => 'Ich stimme den Allgemeinen Geschäftsbedingungen zu',
        'terms_error' => 'Sie müssen den Allgemeinen Geschäftsbedingungen zustimmen.',
        'pay' => 'Bezahlen',
        'amount' => 'Betrag',  // New
        'quantity' => 'Menge',  // New
        'total' => 'Gesamt',  // New
        'select_country' => 'Land auswählen',
        'promo_code' => 'Promo Code', // 'Code promozionale' (it), 'Promocode' (de)
        'apply' => 'Anwenden', // 'Applica' (it), 'Anwenden' (de)
        'placeholder' => 'Gib deinen Code ein',
        'invalid_promo' => 'Invalid or expired promo code.', // Translate accordingly
        'savings' => 'You saved €{amount}!',
        'discount' => 'Discount'
    ],
    'it' => [
        'voucher_preview' => 'Anteprima voucher',
        'valid_until' => 'Valido fino a',
        'all_vouchers' => 'Tutti i voucher',
        'vouchers_detail' => 'Dettagli voucher',
        'checkout' => 'Cassa',
        'cart_summary' => 'Riepilogo carrello',
        'total_amount' => 'Importo totale',
        'billing_information' => 'Informazioni di fatturazione',
        'gender' => 'Genere',
        'select_gender' => 'Seleziona genere',
        'male' => 'Maschio',
        'female' => 'Femmina',
        'first_name' => 'Nome',
        'last_name' => 'Cognome',
        'email' => 'Email',
        'phone' => 'Telefono',
        'country' => 'Paese',
        'tax_number' => 'Numero di partita IVA',
        'address' => 'Indirizzo',
        'agree_terms' => 'Accetto i Termini e le Condizioni',
        'terms_error' => 'Devi accettare i termini e le condizioni.',
        'pay' => 'Paga',
        'amount' => 'Importo',  // New
        'quantity' => 'Quantità',  // New
        'total' => 'Totale',  // New
        'select_country' => 'Seleziona Paese',
        'promo_code' => 'Promo Code', // 'Code promozionale' (it), 'Promocode' (de)
        'apply' => 'Convalida', // 'Applica' (it), 'Anwenden' (de)
        'placeholder' => 'Inserisci il tuo codice',
        'invalid_promo' => 'Invalid or expired promo code.', // Translate accordingly
        'savings' => 'You saved €{amount}!',
        'discount' => 'Discount'
    ]
];


// Define all variables
$voucher_preview = $translations[$lang]['voucher_preview'];
$valid_until = $translations[$lang]['valid_until'];
$all_vouchers = $translations[$lang]['all_vouchers'];
$vouchers_detail = $translations[$lang]['vouchers_detail'];
$checkout = $translations[$lang]['checkout'];
$cart_summary = $translations[$lang]['cart_summary'];
$total_amount = $translations[$lang]['total_amount'];
$billing_information = $translations[$lang]['billing_information'];
$gender = $translations[$lang]['gender'];
$select_gender = $translations[$lang]['select_gender'];
$male = $translations[$lang]['male'];
$female = $translations[$lang]['female'];
$first_name = $translations[$lang]['first_name'];
$last_name = $translations[$lang]['last_name'];
$email = $translations[$lang]['email'];
$phone = $translations[$lang]['phone'];
$country = $translations[$lang]['country'];
$tax_number = $translations[$lang]['tax_number'];
$address = $translations[$lang]['address'];
$agree_terms = $translations[$lang]['agree_terms'];
$terms_error = $translations[$lang]['terms_error'];
$pay = $translations[$lang]['pay'];
$amount = $translations[$lang]['amount'];
$quantity = $translations[$lang]['quantity'];
$discount = $translations[$lang]['discount'];


$total = $translations[$lang]['total'];
$select_country = $translations[$lang]['select_country'];


$translations[$lang]['country_options'] = [
    'en' => [
        'austria' => 'Austria',
        'belgium' => 'Belgium',
        'czech_republic' => 'Czech Republic',
        'denmark' => 'Denmark',
        'finland' => 'Finland',
        'france' => 'France',
        'germany' => 'Germany',
        'greece' => 'Greece',
        'hungary' => 'Hungary',
        'ireland' => 'Ireland',
        'italy' => 'Italy',
        'netherlands' => 'Netherlands',
        'portugal' => 'Portugal',
        'spain' => 'Spain',
        'sweden' => 'Sweden',
        'united_kingdom' => 'United Kingdom',
        'united_states' => 'United States',

    ],
    'de' => [
        'austria' => 'Österreich',
        'belgium' => 'Belgien',
        'czech_republic' => 'Tschechische Republik',
        'denmark' => 'Dänemark',
        'finland' => 'Finnland',
        'france' => 'Frankreich',
        'germany' => 'Deutschland',
        'greece' => 'Griechenland',
        'hungary' => 'Ungarn',
        'ireland' => 'Irland',
        'italy' => 'Italien',
        'netherlands' => 'Niederlande',
        'portugal' => 'Portugal',
        'spain' => 'Spanien',
        'sweden' => 'Schweden',
        'united_kingdom' => 'Vereinigtes Königreich',
        'united_states' => 'Vereinigte Staaten',

    ],
    'it' => [
        'austria' => 'Austria',
        'belgium' => 'Belgio',
        'czech_republic' => 'Repubblica Ceca',
        'denmark' => 'Danimarca',
        'finland' => 'Finlandia',
        'france' => 'Francia',
        'germany' => 'Germania',
        'greece' => 'Grecia',
        'hungary' => 'Ungheria',
        'ireland' => 'Irlanda',
        'italy' => 'Italia',
        'netherlands' => 'Paesi Bassi',
        'portugal' => 'Portogallo',
        'spain' => 'Spagna',
        'sweden' => 'Svezia',
        'united_kingdom' => 'Regno Unito',
        'united_states' => 'Stati Uniti',

    ]
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
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

        .cart-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .cart-item img {
            width: 80px;
            height: auto;
            margin-right: 15px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .error {
            border: 2px solid red !important;
        }

        .agreement {
            display: flex;
            align-items: center;
        }

        .breadcrumb {
            background: none;
            padding: 10px 0;
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
                <?php echo htmlspecialchars($button_hover ?? '#C72B42'); ?> !important;

        }

        .this_container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;

        }

        .this_left {
            color:
                <?php echo htmlspecialchars($text_color ?? '#70706E'); ?>;
            font-size: 24px;
            font-weight: bold;
        }

        .text-color {
            color:
                <?php echo htmlspecialchars($text_color ?? '#70706E'); ?>;
        }

        .link-c {
            color:
                <?php echo htmlspecialchars($link_color ?? '#C72B42'); ?> !important;
        }
    </style>

    <style>
        #voucherDiv {

            margin: auto;


            border: none;

            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .bg-gray-color {
            background-color: rgb(244, 240, 240);
        }

        .t-gray-color {
            color:
                <?php echo htmlspecialchars($text_color ?? '#70706E'); ?>;
        }

        .t-black-color {
            color: black;
        }

        .padding-left-right {
            padding-left: 15px;
            /* Adjust this value as needed */
            padding-right: 15px;
            /* Adjust this value as needed */
        }

        .breadcrumb-item+.breadcrumb-item::before {
            color:
                <?php echo htmlspecialchars($link_color ?? '#C72B42'); ?> !important;
        }

        .gray-color {}
    </style>
    <?php echo $analytics_google; ?>
</head>

<body>

    <div class="container">

        <div class="modal fade" id="voucherModal" tabindex="-1" aria-labelledby="voucherModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="voucherModalLabel"><?php echo $voucher_preview; ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="voucherDiv" class="card shadow-lg border-0 rounded">
                            <div class="text-center pt-3 pb-2">
                                <img id="modalHotelImage" class="img-fluid" style="max-width: 200px;">
                            </div>
                            <div class="bg-gray-color">
                                <div class="text-center">
                                    <img id="modalVoucherImage" class="img-fluid shadow-sm mb-3"
                                        style="max-width: 100%;">
                                </div>
                                <div class="padding-left-right">
                                    <h3 id="modalTitle" class="text-center t-gray-color"></h3>

                                    <h3 id="modalAmount" class="text-center t-black-color"></h3>

                                    <p id="modalOurDescription" class="text-muted text-left"></p>
                                    <p id="modalDescription" class="text-muted text-left"></p>

                                    <div style="text-align: right; width: 100%;">
                                        <div id="qrcode" style="display: inline-block;"></div>
                                        <br>
                                        <p id="modalValidDate" style="font-size: 11px;">
                                            <?php echo $valid_until; ?>: <b></b>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-2 mb-2">
                                <p style="font-size: 14px; padding: 0; margin: 0;">
                                    WOLFSGRUBEN/COSTALOVARA 22 39054 OBERBOZEN/SOPRABOLZANO
                                </p>
                                <p style="font-size: 14px; padding: 0; margin: 0;">
                                    FOR RESERVATIONS TEL.: 0471 345102 INFO@WEIHRERHOF.COM
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a class="link-c"
                        href="voucher_widget.php?define=<?php echo $user_id; ?>&type=<?php echo $type; ?>"><?php echo $all_vouchers; ?></a>
                </li>
                <li class="breadcrumb-item"><a class="link-c"
                        href="voucher_details.php?voucher_id=<?php echo $voucher_id; ?>&define=<?php echo $user_id; ?>&lang=<?php echo $lang; ?>"><?php echo $vouchers_detail; ?></a>
                </li>
                <li class="breadcrumb-item link-c" aria-current="page"><?php echo $checkout; ?></li>
            </ol>
        </nav>

        <div class="this_container">
            <h2 class="this_left"><?php echo $checkout; ?></h2>
            <button id="previewButton" class="btn btn-danger"><?php echo $voucher_preview; ?></button>
        </div>

        <!-- <h4 class="t-gray-color">< ?php echo $cart_summary; ?></h4> -->
        <div class="t-gray-color" id="cartSummary"></div>
        <h5 class="mt-3  t-gray-color"><strong><?php echo $total_amount; ?>: €<span
                    id="totalAmount">0.00</span></strong></h5>


        <div class="form-group mt-3">
            <label class="t-gray-color"><?= $translations[$lang]['promo_code'] ?>:</label>
            <div class="input-group">
                <input type="text" id="promoCode" class="form-control" placeholder="<?= $translations[$lang]['placeholder'] ?>">
                <button type="button" id="applyPromoButton"
                    class="btn btn-danger"><?= $translations[$lang]['apply'] ?></button>
            </div>
            <div class="text-danger" id="errorPromoCode"></div>
            <div class="text-success" id="savingsMessage" style="display: none;"></div>
        </div>

        <h4 class="mt-4 t-gray-color "><?php echo $billing_information; ?></h4>
        <form id="checkoutForm">
            <div class="form-group">
                <label class="text-color"><?php echo $gender; ?>:</label>
                <select id="gender" class="form-control text-color">
                    <option class="text-color" value=""><?php echo $select_gender; ?></option>
                    <option class="text-color" value="Male"><?php echo $male; ?></option>
                    <option class="text-color" value="Female"><?php echo $female; ?></option>
                </select>
            </div>
            <div class="form-group t-gray-color">
                <label><?php echo $first_name; ?>:</label>
                <input type="text" id="firstName" class="form-control">
            </div>
            <div class="form-group t-gray-color">
                <label><?php echo $last_name; ?>:</label>
                <input type="text" id="lastName" class="form-control">
            </div>
            <div class="form-group t-gray-color">
                <label><?php echo $email; ?>:</label>
                <input type="email" id="email" class="form-control">
            </div>
            <div class="form-group t-gray-color">
                <label><?php echo $phone; ?>:</label>
                <input type="text" id="phone" class="form-control">
            </div>
            <div class="form-group t-gray-color">
                <label><?php echo $country; ?>:</label>
                <select id="country" class="form-control text-color">
                    <option value=""><?php echo $translations[$lang]['select_country']; ?></option>
                    <?php foreach ($translations[$lang]['country_options'][$lang] as $key => $country_name): ?>
                        <option value="<?php echo htmlspecialchars($key); ?>">
                            <?php echo htmlspecialchars($country_name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group t-gray-color">
                <label><?php echo $tax_number; ?>:</label>
                <input type="text" id="tax_number" class="form-control">
            </div>
            <div class="form-group t-gray-color">
                <label><?php echo $address; ?>:</label>
                <textarea id="address" class="form-control"></textarea>
            </div>

            <div class="form-check mt-3 t-gray-color">
                <input type="checkbox" id="agreeTerms" class="form-check-input">
                <label for="agreeTerms"><?php echo $agree_terms; ?></label>
                <p id="termsError" class="text-danger mt-1" style="display: none;"><?php echo $terms_error; ?></p>
            </div>

            <button id="payButton" class="btn btn-danger mt-3 w-100 h-100"><?php echo $pay; ?></button>

        </form>
    </div>

    <script>
        $(document).ready(function() {




            let cartData = JSON.parse(localStorage.getItem("cart")) || [];
            let totalAmount = 0;

            function updateCartSummary(discount = 0) {
                totalAmount = 0;
                $("#cartSummary").empty();
                if (cartData.length === 0) {
                    $("#cartSummary").html("<p>Your cart is empty.</p>");
                } else {
                    cartData.forEach(item => {
                        let itemTotal = item.amount * item.quantity;
                        totalAmount += itemTotal;
                        totalAmount = parseFloat(totalAmount.toFixed(2));


                        const translations = {
                            amount: '<?php echo $amount; ?>',
                            quantity: '<?php echo $quantity; ?>',
                            total: '<?php echo $total; ?>',
                            discount: '<?php echo $discount; ?>'
                        };

                        if (discount == 0) {
                            $("#cartSummary").append(`
                        <div class="cart-item">
                            <img src="${item.image}" alt="Voucher">
                            <div>
                                <p><strong>${item.title}</strong></p>
                                <p>${translations.amount}: €${item.amount} </p>
                                
                             
                            </div>
                        </div>
                    `);
                        } else {
                            $("#cartSummary").append(`
                        <div class="cart-item">
                            <img src="${item.image}" alt="Voucher">
                            <div>
                                <p><strong>${item.title}</strong></p>
                                <p>${translations.amount}: €${item.amount} </p>
                                
                                 <p>${translations.discount}: €${discount} </p>
                            </div>
                        </div>
                    `);
                        }


                    });
                }

                console.log(discount);
                console.log(typeof discount);


                totalAmount = totalAmount - discount;

                $("#totalAmount").text(totalAmount.toFixed(2));

            }
            updateCartSummary();
            var promoCode = '';
            let appliedPromo = null; // Track applied promo code
            $("#applyPromoButton").click(function() {
                promoCode = $("#promoCode").val().trim();
                if (!promoCode) {
                    $("#errorPromoCode").text('<?= $translations[$lang]['invalid_promo'] ?>');
                    $("#savingsMessage").hide();
                    return;
                }
                var user_id = '<?php echo $user_id ?>';
                var voucher_id = '<?php echo $voucher_id ?>';
                console.log(user_id);
                console.log(promoCode);
                $.ajax({
                    url: "apply_promo.php",
                    type: "POST",
                    data: {
                        code: promoCode,
                        user_id: user_id,
                        voucher_id: voucher_id
                    },
                    dataType: "json",
                    success: function(response) {

                        if (response.success) {
                            appliedPromo = {
                                promo_code_id: response.promo_code_id,
                                discount_type: response.discount_type,
                                discount_value: response.discount_value
                            };
                            let discount = 0;
                            if (appliedPromo.discount_type === "PERCENTAGE") {
                                 updateCartSummary(discount)
                                discount = totalAmount * (appliedPromo.discount_value / 100);
                            } else if (appliedPromo.discount_type === "FIXED") {
                                discount = parseFloat(appliedPromo.discount_value);
                            }

                            // load ahain summey but now also show discount amont and then total amount .
                            // do here so user unsertand what is going on 

                            $("#savingsMessage").text('<?= $translations[$lang]['savings'] ?>'.replace('{amount}', discount.toFixed(2))).show();
                            $("#errorPromoCode").text('');

                            updateCartSummary(discount)





                        } else {
                            appliedPromo = null;
                            $("#errorPromoCode").text(response.message);
                            $("#savingsMessage").hide();
                            updateCartSummary();
                            promoCode = '';

                        }
                    },
                    error: function(xhr, status, error) {
                        console.log("AJAX Error:", error, xhr.responseText);
                        appliedPromo = null;
                        promoCode = '';

                    }
                });
            });

            $("#payButton").click(function(e) {
                e.preventDefault();
                var type = '<?php echo $type; ?>';
                console.log(type);

                let isValid = true;


                if (type === "INTERNAL") {
                    // Validate only firstName for INTERNAL type
                    let firstName = $("#firstName");
                    if (firstName.val().trim() === "") {
                        firstName.addClass("error");
                        isValid = false;
                    } else {
                        firstName.removeClass("error");
                    }
                } else {
                    // Validate input fields
                    $("#checkoutForm input, #checkoutForm select, #checkoutForm textarea").each(function() {
                        // Get the country value
                        // Get the country value
                        let countryValue = $("#country").val().trim();
                        let isItaly = countryValue === "italy";

                        // Handle tax_number separately
                        if ($(this).attr("id") === "tax_number") {
                            if (isItaly && $(this).val().trim() === "") {
                                $(this).addClass("error");
                                isValid = false;
                            } else {
                                $(this).removeClass("error");
                            }
                        }
                        // Validate all other fields
                        else if ($(this).val().trim() === "") {
                            $(this).addClass("error");
                            isValid = false;
                        } else {
                            $(this).removeClass("error");
                        }
                    });
                }

                // Check if the user has agreed to terms
                if (!$("#agreeTerms").prop("checked")) {
                    $("#termsError").show(); // Show error message in red
                    isValid = false;
                } else {
                    $("#termsError").hide(); // Hide error message if checkbox is checked
                }

                if (!isValid) return; // Stop if any field is invalid

                // Collect user input
                let userData = {
                    gender: $("#gender").val(),
                    first_name: $("#firstName").val(),
                    last_name: $("#lastName").val(),
                    email: $("#email").val(),
                    phone: $("#phone").val(),
                    country: $("#country").val(),
                    address: $("#address").val(),
                    tax_number: $("#tax_number").val(),
                    promoCode: promoCode,
                    cart: JSON.stringify(cartData) // Send cart as JSON string
                };

                // Send data to go.php using AJAX
                $.ajax({
                    url: "go.php",
                    type: "POST",
                    data: userData,

                    success: function(response) {
                        console.log(response);
                        let parts = response.split("|");
                        let d = parts[0];
                        let voucher_id = parts[1];

                        console.log(voucher_id);
                        var lang = '<?php echo $lang; ?>';


                        window.top.location.href = "create-payment.php?voucher_id=" + voucher_id + "&lang=" + lang;

                    },
                    error: function(xhr, status, error) {
                        console.log("Error:", error);
                        console.log("Response Text:", xhr.responseText);
                    }
                });
            });



            var qrCodeText = "MY-code";
            new QRCode(document.getElementById("qrcode"), {
                text: qrCodeText,
                width: 70, // Adjust width
                height: 70, // Adjust height
            });

            // Get the button element
            const previewButton = document.getElementById('previewButton');

            // Add click event listener to call a function
            previewButton.addEventListener('click', function() {
                previewVoucherFunction();
            });

            // Define the function (you can customize this)
            function previewVoucherFunction() {
                let userData = {
                    cart: JSON.stringify(cartData)
                };

                $.ajax({
                    url: "view.php",
                    type: "POST",
                    data: userData,
                    dataType: "json", // Expect JSON response
                    success: function(response) {
                        if (response.success) {
                            // Declare variables from PHP response
                            const title = response.data.title;
                            const amount = response.data.amount;
                            const description = response.data.description;
                            const ourDescription = response.data.ourDescription;
                            const voucher_image = response.data.image;
                            const quantity = response.data.quantity;
                            const hotel_image = response.data.user_image;
                            const valid = response.data.valid; // Added for validity date
                            var currency = '€';
                            var qr_code = 'MY-code';

                            var is_fixed = <?php echo $is_fixed ?>;

                            console.log('Raw ourDescription:', ourDescription);
                            console.log('Raw description:', description);

                            // Update modal content with the variables
                            document.getElementById('modalTitle').textContent = title;
                            if (is_fixed == 0) {
                                document.getElementById('modalAmount').textContent = `${currency} ${amount * quantity}`;
                            } else {
                                document.getElementById('modalAmount').textContent = '';

                            }

                            document.getElementById('modalOurDescription').innerHTML = ourDescription;
                            document.getElementById('modalDescription').innerHTML = description;
                            document.getElementById('modalHotelImage').src = hotel_image;
                            document.getElementById('modalVoucherImage').src = voucher_image;
                            document.getElementById('modalValidDate').innerHTML = `Valid until: <b>${valid}</b>`;


                            $('#voucherModal').modal('show');



                        } else {
                            console.error('Error:', response.error);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", status, error);
                        console.error("Response Text:", xhr.responseText);
                    }
                });
            }

        });
    </script>

</body>

</html>