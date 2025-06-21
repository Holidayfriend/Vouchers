<aside class="left-sidebar">
    <div class="d-flex no-block nav-text-box align-items-center">
        <span><img src="./assets/images/favicon.png" width="30px" height="30px" alt="elegant admin template"></span>

        <a class="nav-toggler waves-effect waves-dark ml-auto hidden-sm-up" href="javascript:void(0)"><i
                class="ti-menu ti-close"></i></a>

    </div>
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li> <a class="" href="hotel_dashboard.php" aria-expanded="false">
                        <i class="icon-home"></i><span
                            class="hide-menu"><?php echo getTranslation('dashboard', $lang) ?></span></a>
                </li>

                <li> <a id="vouchers-link" class="" href="hotel_vouchers.php" aria-expanded="false">
                        <i class="mdi mdi-wallet-giftcard"></i><span
                            class="hide-menu"><?php echo getTranslation('vouchers', $lang) ?></span>
                    </a>
                </li>
                <li> <a id="categories-link" class="" href="hotel_categories.php" aria-expanded="false">
                        <i class="fab fa-hubspot"></i><span
                            class="hide-menu"><?php echo getTranslation('categories', $lang) ?></span></a>
                </li>
                <li> <a id="purchased-link" class="" href="hotel_purchased_voucher.php" aria-expanded="false">
                        <i class="fas fas fa-gift"></i><span
                            class="hide-menu"><?php echo getTranslation('all_purchased_voucher', $lang) ?></span></a>
                </li>
                <li> <a id="redeem-link" class="" href="hotel_redeemed_voucher.php" aria-expanded="false">
                        <i class="mdi mdi-scanner"></i><span
                            class="hide-menu"><?php echo getTranslation('redeemed_voucher', $lang) ?></span></a>
                </li>
                <li> <a id="unused-link" class="" href="hotel_unused_voucher.php" aria-expanded="false">
                        <i class="mdi mdi-wallet-giftcard"></i><span
                            class="hide-menu"><?php echo getTranslation('unused_voucher', $lang) ?></span></a>
                </li>
                <li> <a id="transaction-link" class="" href="hotel_transaction.php" aria-expanded="false">
                        <i class="far fa-money-bill-alt"></i><span
                            class="hide-menu"><?php echo getTranslation('transaction', $lang) ?></span></a>
                </li>
                <li> <a id="internal-link" class="" href="hotel_internal_vouchers.php" aria-expanded="false">
                        <i class="mdi mdi-heart-box-outline"></i><span
                            class="hide-menu"><?php echo getTranslation('Internal Vouchers', $lang) ?></span></a>
                </li>
                <li> <a id="promo-link" class="" href="hotel_promo_code.php" aria-expanded="false">
                        <i class="fas fa-credit-card"></i><span
                            class="hide-menu"><?php echo getTranslation('promo_code', $lang) ?></span></a>
                </li>
                <hr>

                <li> <a id="promo-link" class="" href="hotel_revenue.php" aria-expanded="false">
                        <i class="fas fa-dollar-sign"></i><span
                            class="hide-menu"><?php echo getTranslation('revenue', $lang) ?></span></a>
                </li>

                <li> <a id="promo-link" class="" href="hotel_reports.php" aria-expanded="false">
                        <i class="far fa-chart-bar"></i><span
                            class="hide-menu"><?php echo getTranslation('reports', $lang) ?></span></a>
                </li>














            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>

<script>
    // Get the current URL
    var currentURL = window.location.href;

    // Check if the URL contains "city_benefit_holder" or "super_add_benefit_holder"
    // 1st
    if (currentURL.indexOf("hotel_vouchers") !== -1) {
        // Add the "active" class to the link
        document.getElementById("vouchers-link").classList.add("active");
    }

    var searchString = 'hotel_add_vochers';
    if (currentURL.indexOf(searchString) !== -1) {

        document.getElementById("vouchers-link").classList.add("active");
    } else {


    }
    var searchString = 'hotel_edit_vochers';
    if (currentURL.indexOf(searchString) !== -1) {

        document.getElementById("vouchers-link").classList.add("active");
    } else {


    }
    // 2nd
    if (currentURL.indexOf("hotel_categories") !== -1) {
        // Add the "active" class to the link
        document.getElementById("categories-link").classList.add("active");
    }
    if (currentURL.indexOf("hotel_add_category") !== -1) {
        // Add the "active" class to the link
        document.getElementById("categories-link").classList.add("active");
    }
    if (currentURL.indexOf("hotel_edit_category") !== -1) {
        // Add the "active" class to the link
        document.getElementById("categories-link").classList.add("active");
    }
    // 3rd
    if (currentURL.indexOf("hotel_purchased_voucher") !== -1) {
        // Add the "active" class to the link
        document.getElementById("purchased-link").classList.add("active");
    }
    if (currentURL.indexOf("hotel_purchased_voucher_detail") !== -1) {
        // Add the "active" class to the link
        document.getElementById("purchased-link").classList.add("active");
    }

    // 4
    if (currentURL.indexOf("hotel_transaction") !== -1) {
        // Add the "active" class to the link
        document.getElementById("transaction-link").classList.add("active");
    }

    // 5
    if (currentURL.indexOf("hotel_redeemed_voucher") !== -1) {
        // Add the "active" class to the link
        document.getElementById("redeem-link").classList.add("active");
    }


    // 7
    if (currentURL.indexOf("hotel_unused_voucher") !== -1) {
        // Add the "active" class to the link
        document.getElementById("unused-link").classList.add("active");
    }

    //8
    if (currentURL.indexOf("hotel_categories") !== -1) {
        // Add the "active" class to the link
        document.getElementById("hotel_promo_code").classList.add("active");
    }
    if (currentURL.indexOf("promo-link") !== -1) {
        // Add the "active" class to the link
        document.getElementById("hotel_add_promo_code").classList.add("active");
    }
    if (currentURL.indexOf("hotel_edit_promo_code") !== -1) {
        // Add the "active" class to the link
        document.getElementById("promo-link").classList.add("active");
    }
</script>