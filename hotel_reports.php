<?php
require_once 'util_config.php';
require_once 'util_session.php';
include 'lang/translation.php';
$lang = strtolower($my_language_is);

// Redirect based on user type
if (isset($_SESSION['my_user_type_is'])) {
    if ($my_user_type_is == 'ADMIN') {
        echo '<script type="text/javascript">window.location.href = "super_dashboard.php";</script>';
    } elseif ($my_user_type_is == 'NORMAL') {
        // Stay on this page
    } else {
        echo '<script type="text/javascript">window.location = "index.php";</script>';
    }
} else {
    echo '<script type="text/javascript">window.location.href = "index.php";</script>';
}

$page_text = getTranslation('reports', $lang);

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

// Determine days and months passed
$today = new DateTime(); // Current date (2025-05-31 07:04 AM PKT)
$daysPassed = $today->format('z') + 1; // 151 days
$monthsPassed = (int)$today->format('m'); // 5 months
if ($selectedYear == $currentYear && $selectedMonth != 'all') {
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $selectedMonth, $selectedYear);
    $daysPassed = ($selectedMonth == $today->format('m')) ? $today->format('j') : $daysInMonth;
    $monthsPassed = 1;
} elseif ($selectedYear != $currentYear) {
    $daysPassed = ($selectedMonth == 'all') ? 365 : cal_days_in_month(CAL_GREGORIAN, $selectedMonth, $selectedYear);
    $monthsPassed = ($selectedMonth == 'all') ? 12 : 1;
}

// Query conditions
$monthCondition = ($selectedMonth != 'all') ? "AND MONTH(uv.p_time) = '$selectedMonth'" : '';
$yearCondition = "AND YEAR(uv.p_time) = '$selectedYear'";

// Debug log file
$debugLog = "debug_log.txt";
file_put_contents($debugLog, "Debug Log - " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);

// Sold and Redeemed Vouchers
$voucherSql = "SELECT uv.id, v.title, uv.p_time, uv.redaem_time, uv.total
               FROM `tbl_users_vouchers` uv
               JOIN `tbl_voucher` v ON uv.voucher_id = v.id
               WHERE uv.user_id = $my_user_id_is AND uv.is_delete = 0
               AND v.type = 'NORMAL'
               $yearCondition $monthCondition";
$voucherResult = $conn->query($voucherSql);
$totalSold = 0;
$totalRedeemed = 0;
$voucherDetails = [];
while ($row = $voucherResult->fetch_assoc()) {
    $totalSold += 1; // Each row is one purchase
    $totalRedeemed += ($row['redaem_time'] != '') ? 1 : 0; // Count as redeemed if redaem_time is not empty
    $voucherDetails[] = $row;
    // Debug: Log each voucher
    file_put_contents($debugLog, "Voucher: {$row['title']}, Sold: 1, Redeemed: " . ($row['redaem_time'] != '' ? 1 : 0) . ", Total: {$row['total']}\n", FILE_APPEND);
}
$redemptionRate = $totalSold ? ($totalRedeemed / $totalSold) * 100 : 0;
file_put_contents($debugLog, "Total Sold: $totalSold, Total Redeemed: $totalRedeemed, Redemption Rate: $redemptionRate%\n", FILE_APPEND);

// Voucher Chart Data
$voucherChartLabels = [];
$voucherSoldData = [];
$voucherRedeemedData = [];
if ($selectedMonth == 'all') {
    foreach ($months as $m => $name) {
        $sql = "SELECT COUNT(uv.id) as sold,
                       SUM(CASE WHEN uv.redaem_time != '' THEN 1 ELSE 0 END) as redeemed
                FROM `tbl_users_vouchers` uv
                JOIN `tbl_voucher` v ON uv.voucher_id = v.id
                WHERE uv.user_id = $my_user_id_is AND uv.is_delete = 0
                AND v.type = 'NORMAL'
                AND YEAR(uv.p_time) = '$selectedYear' AND MONTH(uv.p_time) = '$m'";
        $res = $conn->query($sql);
        $data = $res->fetch_assoc();
        $voucherChartLabels[] = $name;
        $voucherSoldData[] = $data['sold'] ?? 0;
        $voucherRedeemedData[] = $data['redeemed'] ?? 0;
        // Debug: Log chart data
        file_put_contents($debugLog, "Month $name: Sold: {$data['sold']}, Redeemed: {$data['redeemed']}\n", FILE_APPEND);
    }
} else {
    $maxDays = ($selectedYear == $currentYear && $selectedMonth == $today->format('m')) ? $today->format('j') : cal_days_in_month(CAL_GREGORIAN, $selectedMonth, $selectedYear);
    for ($day = 1; $day <= $maxDays; $day++) {
        $dayStr = sprintf('%02d', $day);
        $sql = "SELECT COUNT(uv.id) as sold,
                       SUM(CASE WHEN uv.redaem_time != '' THEN 1 ELSE 0 END) as redeemed
                FROM `tbl_users_vouchers` uv
                JOIN `tbl_voucher` v ON uv.voucher_id = v.id
                WHERE uv.user_id = $my_user_id_is AND uv.is_delete = 0
                AND v.type = 'NORMAL'
                AND YEAR(uv.p_time) = '$selectedYear' AND MONTH(uv.p_time) = '$selectedMonth'
                AND DAY(uv.p_time) = '$dayStr'";
        $res = $conn->query($sql);
        $data = $res->fetch_assoc();
        $voucherChartLabels[] = $day;
        $voucherSoldData[] = $data['sold'] ?? 0;
        $voucherRedeemedData[] = $data['redeemed'] ?? 0;
        // Debug: Log chart data
        file_put_contents($debugLog, "Day $day: Sold: {$data['sold']}, Redeemed: {$data['redeemed']}\n", FILE_APPEND);
    }
}

// Promo Code Usage
$promoSql = "SELECT p.code, p.discount_type, p.discount_value, COUNT(pu.usage_id) as uses,
                    SUM(pu.applied_discount) as total_discount, MAX(pu.usage_date) as last_used
             FROM `tbl_promocodeusage` pu
             JOIN `tbl_promocodes` p ON pu.promo_code_id = p.promo_code_id
             JOIN `tbl_users_vouchers` uv ON pu.purchase_id = uv.id
             JOIN `tbl_voucher` v ON uv.voucher_id = v.id
             WHERE p.user_id = $my_user_id_is AND p.is_delete = 0 AND uv.is_delete = 0
             AND v.type = 'NORMAL'
             $yearCondition $monthCondition
             GROUP BY p.promo_code_id";
$promoResult = $conn->query($promoSql);
$totalPromoUses = 0;
$totalPromoDiscount = 0;
$promoDetails = [];
while ($row = $promoResult->fetch_assoc()) {
    $totalPromoUses += $row['uses'];
    $totalPromoDiscount += $row['total_discount'] ?? 0;
    $promoDetails[] = $row;
    // Debug: Log promo data
    file_put_contents($debugLog, "Promo Code: {$row['code']}, Uses: {$row['uses']}, Discount: {$row['total_discount']}\n", FILE_APPEND);
}

// Promo Chart Data
$promoChartLabels = [];
$promoUsesData = [];
$topPromoLimit = 5; // Top 5 promo codes
$promoTopSql = "SELECT p.code, COUNT(pu.usage_id) as uses
                FROM `tbl_promocodeusage` pu
                JOIN `tbl_promocodes` p ON pu.promo_code_id = p.promo_code_id
                JOIN `tbl_users_vouchers` uv ON pu.purchase_id = uv.id
                JOIN `tbl_voucher` v ON uv.voucher_id = v.id
                WHERE p.user_id = $my_user_id_is AND p.is_delete = 0 AND uv.is_delete = 0
                AND v.type = 'NORMAL'
                $yearCondition $monthCondition
                GROUP BY p.promo_code_id
                ORDER BY uses DESC
                LIMIT $topPromoLimit";
$promoTopResult = $conn->query($promoTopSql);
while ($row = $promoTopResult->fetch_assoc()) {
    $promoChartLabels[] = $row['code'];
    $promoUsesData[] = $row['uses'];
}

// Top-Selling Vouchers
$topVoucherSql = "SELECT v.title, v.cat_id, COUNT(uv.id) as sales_count,
                         SUM(uv.total) as total_revenue, SUM(CASE WHEN uv.redaem_time != '' THEN 1 ELSE 0 END) as total_redeemed
                  FROM `tbl_users_vouchers` uv
                  JOIN `tbl_voucher` v ON uv.voucher_id = v.id
                  WHERE uv.user_id = $my_user_id_is AND uv.is_delete = 0
                  AND v.type = 'NORMAL'
                  $yearCondition $monthCondition
                  GROUP BY v.id
                  ORDER BY sales_count DESC
                  LIMIT 5";
$topVoucherResult = $conn->query($topVoucherSql);
$totalVoucherSales = 0;
$totalVoucherRevenue = 0;
$topVoucherDetails = [];
while ($row = $topVoucherResult->fetch_assoc()) {
    $totalVoucherSales += $row['sales_count'];
    $totalVoucherRevenue += $row['total_revenue'] ?? 0;
    $topVoucherDetails[] = $row;
    // Debug: Log top voucher data
    file_put_contents($debugLog, "Top Voucher: {$row['title']}, Sales: {$row['sales_count']}, Revenue: {$row['total_revenue']}\n", FILE_APPEND);
}
file_put_contents($debugLog, "Total Voucher Sales: $totalVoucherSales, Total Voucher Revenue: $totalVoucherRevenue\n", FILE_APPEND);

// Top Voucher Chart Data
$topVoucherChartLabels = [];
$topVoucherSalesData = [];
foreach ($topVoucherDetails as $row) {
    $topVoucherChartLabels[] = $row['title'];
    $topVoucherSalesData[] = $row['sales_count'];
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
    <title><?= getTranslation('reports', $lang) ?></title>
    <link href="dist/css/style.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        #voucherChart,
        #promoChart,
        #topVoucherChart {
            height: 500px !important;
        }

        .table-responsive {
            margin-bottom: 20px;
        }
    </style>
</head>

<body class="skin-default-dark fixed-layout mini-sidebar lock-nav">
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label"><?= getTranslation('reports', $lang) ?></p>
        </div>
    </div>
    <div id="main-wrapper">
        <?php include 'util_header.php'; ?>
        <?php include 'hotel_utill_side_nav.php'; ?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="mobile-container-padding pt-3">
                    <!-- Filters -->
                    <form method="get">
                        <div class="row page-titles mb-3 add_background">
                            <div class="col-lg-6 col-xlg-6 col-md-6">
                                <label for="year"><strong><?= getTranslation('year', $lang) ?>:</strong></label>
                                <select name="year" id="year" class="form-control" onchange="this.form.submit()">
                                    <?php foreach ($years as $y): ?>
                                        <option value="<?= $y ?>" <?= $y == $selectedYear ? 'selected' : '' ?>>
                                            <?= $y ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-lg-6 col-xlg-6 col-md-6">
                                <label for="month"><strong><?= getTranslation('month', $lang) ?>:</strong></label>
                                <select name="month" id="month" class="form-control" onchange="this.form.submit()">
                                    <option value="all" <?= $selectedMonth == 'all' ? 'selected' : '' ?>>
                                        <?= getTranslation('all_months', $lang) ?></option>
                                    <?php foreach ($months as $m => $name): ?>
                                        <option value="<?= $m ?>" <?= $m == $selectedMonth ? 'selected' : '' ?>
                                            <?= ($selectedYear == $currentYear && (int)$m > (int)$today->format('m')) ? 'disabled' : '' ?>>
                                            <?= $name ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </form>

             

                    <!-- Sold and Redeemed Vouchers -->
                    <div class="row page-titles mb-3 add_background">
                        <h4><?= getTranslation('sold_redeemed_vouchers', $lang) ?></h4>
                        <!-- Metrics -->
                        <div class="col-lg-4 col-xlg-4 col-md-4">
                            <h6><strong><?= getTranslation('total_sold', $lang) ?></strong></h6>
                            <p><?= $totalSold ?></p>
                        </div>
                        <div class="col-lg-4 col-xlg-4 col-md-4">
                            <h6><strong><?= getTranslation('total_redeemed', $lang) ?></strong></h6>
                            <p><?= $totalRedeemed ?></p>
                        </div>
                        <div class="col-lg-4 col-xlg-4 col-md-4">
                            <h6><strong><?= getTranslation('redemption_rate', $lang) ?></strong></h6>
                            <p><?= number_format($redemptionRate, 2) ?>%</p>
                        </div>
                        <!-- Table -->
                        <div class="col-lg-12 col-xlg-12 col-md-12 table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th><?= getTranslation('voucher_title', $lang) ?></th>
                                        <th><?= getTranslation('sold_date', $lang) ?></th>
                                        <th><?= getTranslation('redeemed', $lang) ?></th>
                                        <th><?= getTranslation('last_redeemed', $lang) ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($voucherDetails as $voucher): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($voucher['title']) ?></td>
                                            <td><?= $voucher['p_time'] ?></td>
                                            <td><?= $voucher['redaem_time'] != '' ? 1 : 0 ?></td>
                                            <td><?= $voucher['redaem_time'] ?: '-' ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- Chart -->
                        <?php if (array_sum($voucherSoldData) > 0 || array_sum($voucherRedeemedData) > 0): ?>
                            <div class="col-lg-12 col-xlg-12 col-md-12">
                                <canvas id="voucherChart"></canvas>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Promo Code Usage -->
                    <div class="row page-titles mb-3 add_background">
                        <h4><?= getTranslation('promo_code_usage', $lang) ?></h4>
                        <!-- Metrics -->
                        <div class="col-lg-6 col-xlg-6 col-md-6">
                            <h6><strong><?= getTranslation('total_uses', $lang) ?></strong></h6>
                            <p><?= $totalPromoUses ?></p>
                        </div>
                        <div class="col-lg-6 col-xlg-6 col-md-6">
                            <h6><strong><?= getTranslation('total_discount', $lang) ?></strong></h6>
                            <p>$<?= number_format($totalPromoDiscount, 2) ?></p>
                        </div>
                        <!-- Table -->
                        <div class="col-lg-12 col-xlg-12 col-md-12 table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th><?= getTranslation('promo_code', $lang) ?></th>
                                        <th><?= getTranslation('discount_type', $lang) ?></th>
                                        <th><?= getTranslation('discount_value', $lang) ?></th>
                                        <th><?= getTranslation('uses', $lang) ?></th>
                                        <th><?= getTranslation('total_discount', $lang) ?></th>
                                        <th><?= getTranslation('last_used', $lang) ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($promoDetails as $promo): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($promo['code']) ?></td>
                                            <td><?= ucfirst($promo['discount_type']) ?></td>
                                            <td><?= $promo['discount_type'] == 'percentage' ? $promo['discount_value'] . '%' : '$' . $promo['discount_value'] ?></td>
                                            <td><?= $promo['uses'] ?></td>
                                            <td>$<?= number_format($promo['total_discount'] ?? 0, 2) ?></td>
                                            <td><?= $promo['last_used'] ?? '-' ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- Chart -->
                        <?php if (count($promoUsesData) > 0): ?>
                            <div class="col-lg-12 col-xlg-12 col-md-12">
                                <canvas id="promoChart"></canvas>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Top-Selling Vouchers -->
                    <div class="row page-titles mb-3 add_background">
                        <h4><?= getTranslation('top_selling_vouchers', $lang) ?></h4>
                        <!-- Metrics -->
                        <div class="col-lg-6 col-xlg-6 col-md-6">
                            <h6><strong><?= getTranslation('total_sales', $lang) ?></strong></h6>
                            <p><?= $totalVoucherSales ?></p>
                        </div>
                        <div class="col-lg-6 col-xlg-6 col-md-6">
                            <h6><strong><?= getTranslation('total_revenue', $lang) ?></strong></h6>
                            <p>$<?= number_format($totalVoucherRevenue, 2) ?></p>
                        </div>
                        <!-- Table -->
                        <div class="col-lg-12 col-xlg-12 col-md-12 table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th><?= getTranslation('voucher_title', $lang) ?></th>
                                        <th><?= getTranslation('sales_count', $lang) ?></th>
                                        <th><?= getTranslation('total_revenue', $lang) ?></th>
                                        <th><?= getTranslation('redeemed', $lang) ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($topVoucherDetails as $voucher): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($voucher['title']) ?></td>
                                            <td><?= $voucher['sales_count'] ?></td>
                                            <td>$<?= number_format($voucher['total_revenue'] ?? 0, 2) ?></td>
                                            <td><?= $voucher['total_redeemed'] ?? 0 ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- Chart -->
                        <?php if (count($topVoucherSalesData) > 0): ?>
                            <div class="col-lg-12 col-xlg-12 col-md-12">
                                <canvas id="topVoucherChart"></canvas>
                            </div>
                        <?php endif; ?>
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
            window.location.href = 'reports.php';
        }

        // Voucher Chart
        <?php if (array_sum($voucherSoldData) > 0 || array_sum($voucherRedeemedData) > 0): ?>
            const voucherCtx = document.getElementById('voucherChart').getContext('2d');
            new Chart(voucherCtx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($voucherChartLabels) ?>,
                    datasets: [{
                            label: '<?= getTranslation('sold_vouchers', $lang) ?>',
                            data: <?= json_encode($voucherSoldData) ?>,
                            backgroundColor: '#4e73df',
                            borderColor: '#4e73df',
                            borderWidth: 1
                        },
                        {
                            label: '<?= getTranslation('redeemed_vouchers', $lang) ?>',
                            data: <?= json_encode($voucherRedeemedData) ?>,
                            backgroundColor: '#1cc88a',
                            borderColor: '#1cc88a',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: '<?= getTranslation('count', $lang) ?>'
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

        // Promo Chart
        <?php if (count($promoUsesData) > 0): ?>
            const promoCtx = document.getElementById('promoChart').getContext('2d');
            new Chart(promoCtx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($promoChartLabels) ?>,
                    datasets: [{
                        label: '<?= getTranslation('uses', $lang) ?>',
                        data: <?= json_encode($promoUsesData) ?>,
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
                                text: '<?= getTranslation('uses', $lang) ?>'
                            },
                            grid: {
                                color: '#444'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: '<?= getTranslation('promo_code', $lang) ?>'
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

        // Top Voucher Chart
        <?php if (count($topVoucherSalesData) > 0): ?>
            const topVoucherCtx = document.getElementById('topVoucherChart').getContext('2d');
            new Chart(topVoucherCtx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($topVoucherChartLabels) ?>,
                    datasets: [{
                        label: '<?= getTranslation('sales_count', $lang) ?>',
                        data: <?= json_encode($topVoucherSalesData) ?>,
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
                                text: '<?= getTranslation('sales_count', $lang) ?>'
                            },
                            grid: {
                                color: '#444'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: '<?= getTranslation('voucher_title', $lang) ?>'
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