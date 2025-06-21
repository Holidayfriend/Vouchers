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
                <li> <a class="" href="super_dashboard.php" aria-expanded="false"><i class="icon-home"></i><span
                            class="hide-menu">Dashboard</span></a>
                </li>
                <li> <a id="hotel-link" class="" href="super_hotel_list.php" aria-expanded="false"><i
                            class="mdi mdi-city"></i><span class="hide-menu"><?php echo getTranslation('register_hotel', $lang) ?></span></a>
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
    if (currentURL.indexOf("super_hotel_list") !== -1) {
        // Add the "active" class to the link
        document.getElementById("city-link").classList.add("active");
    }

    var searchString = 'super_add_hotel';
    if (currentURL.indexOf(searchString) !== -1) {

        document.getElementById("hotel-link").classList.add("active");
    } else {

    }
    var searchString = 'super_edit_hotel';
    if (currentURL.indexOf(searchString) !== -1) {

        document.getElementById("hotel-link").classList.add("active");
    } else {


    }
</script>