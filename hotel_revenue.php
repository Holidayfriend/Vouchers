<?php
require_once 'util_config.php';
require_once 'util_session.php';
include 'lang/translation.php';
$lang = strtolower($my_language_is);

// Redirect based on user type
if (isset($_SESSION['my_user_type_is'])) {
    if ($my_user_type_is == 'ADMIN') {
        echo '<script type="text/javascript">window.location.href = "super_dashboard.php";</script>';
    } else if ($my_user_type_is == 'NORMAL') {
        // Stay on this page
    } else {
        echo '<script type="text/javascript">window.location.href = "index.php";</script>';
    }
} else {
    echo '<script type="text/javascript">window.location.href = "index.php";</script>';
}

$page_text = getTranslation('revenue', $lang);

// Year and Month filters
$currentYear = date('Y'); // 2025
$selectedYear = isset($_GET['year']) ? $_GET['year'] : $currentYear;
$selectedMonth = isset($_GET['month']) ? $_GET['month'] : 'all';
$years = [$currentYear, $currentYear + 1];
$months = [
    '01' => 'January',
    '02' => 'February',
    '03' => 'March',
    '04' => 'April',
    '05' => 'May',
    '06' => 'June',
    '07' => 'July',
    '08' => 'August',
    '09' => 'September',
    '10' => 'October',
    '11' => 'November',
    '12' => 'December'
];

// Determine days, weeks, and months passed
$today = new DateTime(); // Current date (2025-05-29 03:46 AM PKT)
$daysPassed = $today->format('z') + 1; // Days since Jan 1 (150 for May 29, 2025)
$weeksPassed = ceil($daysPassed / 7) ?: 1; // Approx 21.43 weeks -> 22 weeks
$monthsPassed = (int) $today->format('m'); // 5 months (January–May 2025)
if ($selectedYear == $currentYear && $selectedMonth != 'all') {
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $selectedMonth, $selectedYear);
    $daysPassed = ($selectedMonth == $today->format('m')) ? $today->format('j') : $daysInMonth;
    $weeksPassed = ceil($daysPassed / 7) ?: 1;
    $monthsPassed = 1; // Single month selected
} elseif ($selectedYear != $currentYear) {
    $daysPassed = ($selectedMonth == 'all') ? 365 : cal_days_in_month(CAL_GREGORIAN, $selectedMonth, $selectedYear);
    $weeksPassed = ceil($daysPassed / 7) ?: 1;
    $monthsPassed = ($selectedMonth == 'all') ? 12 : 1;
}

// Query for income metrics
$monthCondition = ($selectedMonth != 'all') ? "AND MONTH(purchase_date) = '$selectedMonth'" : '';
$yearCondition = "AND YEAR(purchase_date) = '$selectedYear'";
$incomeSql = "SELECT SUM(amount) as total_income 
              FROM `tbl_transaction` 
              WHERE user_id = $my_user_id_is 
              AND is_delete = 0 
              $yearCondition $monthCondition";
$incomeResult = $conn->query($incomeSql);
$incomeData = $incomeResult->fetch_assoc();
$totalIncome = $incomeData['total_income'] ?? 0;
$avgDailyIncome = $daysPassed ? $totalIncome / $daysPassed : 0;
$avgWeeklyIncome = $weeksPassed ? $totalIncome / $weeksPassed : 0;
$perMonthIncome = $monthsPassed ? $totalIncome / $monthsPassed : 0;

// Prepare chart data
$chartLabels = [];
$chartData = [];
if ($selectedMonth == 'all') {
    // Monthly chart data
    foreach ($months as $m => $name) {
        $sqlMonthly = "SELECT SUM(amount) as total 
                       FROM `tbl_transaction` 
                       WHERE user_id = $my_user_id_is 
                       AND is_delete = 0 
                       AND YEAR(purchase_date) = '$selectedYear' 
                       AND MONTH(purchase_date) = '$m'";
        $resMonthly = $conn->query($sqlMonthly);
        $chartLabels[] = $name;
        $chartData[] = $resMonthly->fetch_assoc()['total'] ?? 0;
    }
} else {
    // Daily chart data for selected month
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $selectedMonth, $selectedYear);
    $maxDays = ($selectedYear == $currentYear && $selectedMonth == $today->format('m')) ? $today->format('j') : $daysInMonth;
    for ($day = 1; $day <= $maxDays; $day++) {
        $dayStr = sprintf('%02d', $day);
        $sqlDaily = "SELECT SUM(amount) as total 
                     FROM `tbl_transaction` 
                     WHERE user_id = $my_user_id_is 
                     AND is_delete = 0 
                     AND YEAR(purchase_date) = '$selectedYear' 
                     AND MONTH(purchase_date) = '$selectedMonth' 
                     AND DAY(purchase_date) = '$dayStr'";
        $resDaily = $conn->query($sqlDaily);
        $chartLabels[] = $day;
        $chartData[] = $resDaily->fetch_assoc()['total'] ?? 0;
    }
}
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="./assets/images/favicon.png">
    <title><?= getTranslation('revenue', $lang) ?></title>
    <link href="dist/css/style.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        #revenueChart {
            height: 500px !important;
        }
    </style>

</head>

<body class="skin-default-dark fixed-layout mini-sidebar lock-nav">
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label"><?= getTranslation('revenue', $lang) ?></p>
        </div>
    </div>
    <div id="main-wrapper">
        <?php include 'util_header.php'; ?>
        <?php include 'hotel_utill_side_nav.php'; ?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="mobile-container-padding pt-3">
                    <form method="get">
                        <div class="row page-titles mb-3   add_background">

                            <div class="col-lg-6 col-xlg-6 col-md-6">
                                <label for="year"><strong><?= getTranslation('year', $lang) ?>:</strong></label>
                                <select name="year" id="year" class="form-control"
                                    onchange="this.form.submit()">
                                    <?php foreach ($years as $y): ?>
                                        <option value="<?= $y ?>" <?= $y == $selectedYear ? 'selected' : '' ?>>
                                            <?= $y ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-lg-6 col-xlg-6 col-md-6">
                                <label for="month"><strong><?= getTranslation('month', $lang) ?>:</strong></label>
                                <select name="month" id="month" class="form-control"
                                    onchange="this.form.submit()">
                                    <option value="all" <?= $selectedMonth == 'all' ? 'selected' : '' ?>>
                                        <?= getTranslation('all_months', $lang) ?></option>
                                    <?php foreach ($months as $m => $name): ?>
                                        <option value="<?= $m ?>" <?= $m == $selectedMonth ? 'selected' : '' ?>
                                            <?= ($selectedYear == $currentYear && (int) $m > (int) $today->format('m')) ? 'disabled' : '' ?>>
                                            <?= $name ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                        </div>
                    </form>


                    <!-- Metrics Display -->

                    <div class="row page-titles mb-3   add_background">
                        <div class="col-lg-3 col-xlg-3 col-md-3">
                            <h6><strong><?= getTranslation('avg_daily_income', $lang) ?></strong></h6>
                            <p>$<?= number_format($avgDailyIncome, 2) ?></p>
                        </div>
                        <div class="col-md-3">
                            <h6><strong><?= getTranslation('avg_weekly_income', $lang) ?></strong></h6>
                            <p>$<?= number_format($avgWeeklyIncome, 2) ?></p>
                        </div>
                        <div class="col-md-3">
                            <h6><strong><?= getTranslation('per_month_income', $lang) ?></strong></h6>
                            <p>$<?= number_format($perMonthIncome, 2) ?></p>
                        </div>
                        <div class="col-md-3">
                            <h6><strong><?= getTranslation('total_income', $lang) ?></strong></h6>
                            <p>$<?= number_format($totalIncome, 2) ?></p>
                        </div>
                    </div>
                    <!-- Chart Display -->
                    <div class="row page-titles mb-3   add_background">
                        <div class="col-lg-12 col-xlg-12 col-md-12">
                            <?php if (array_sum($chartData) > 0): // Show chart only if there’s data 
                            ?>
                                <div class="row chart-container">
                                    <div class="col-md-12">
                                        <canvas id="revenueChart"></canvas>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <?php include 'util_footer.php'; ?>
    </div>

    <script src="./assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
    <script src="./assets/node_modules/popper/popper.min.js"></script>
    <script src="./assets/node_modules/bootstrap/js/bootstrap.min.js"></script>
    <script src="dist/js/perfect-scrollbar.jquery.min.js"></script>
    <script src="dist/js/waves.js"></script>
    <script src="dist/js/sidebarmenu.js"></script>
    <script src="dist/js/custom.min.js"></script>

    <script>
        function reset() {
            window.location.href = 'revenue.php';
        }

        // Chart.js configuration
        <?php if (array_sum($chartData) > 0): ?>
            const ctx = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($chartLabels) ?>,
                    datasets: [{
                        label: '<?= getTranslation('revenue', $lang) ?> ($)',
                        data: <?= json_encode($chartData) ?>,
                        backgroundColor: '#4e73df',
                        borderColor: '#4e73df',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: '<?= getTranslation('revenue', $lang) ?> ($)'
                            },
                            grid: {
                                color: '#444'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: '<?= $selectedMonth == 'all' ? getTranslation('month', $lang) : getTranslation('day', $lang) ?>'
                            },
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            labels: {
                                color: '#fff'
                            }
                        }
                    }
                }
            });
        <?php endif; ?>
    </script>
</body>

</html>