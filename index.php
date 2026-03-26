<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require 'config.php';

// Fetch user name from DB
$user_id = $_SESSION['user_id'];
$name = 'User';
$stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($nameFromDB);
if ($stmt->fetch()) {
    $name = $nameFromDB;
    $_SESSION['name'] = $name; // Save to session for consistency
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Trading Monte Carlo Simulator</title>

<!-- Bootstrap -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Custom CSS -->
<link rel="stylesheet" href="assets/css/style.css">

<!-- Sidebar Toggle JS -->
<script src="assets/js/sidebar.js" defer></script>
</head>
<body class="dark-theme">

<!-- ================= LEFT SIDEBAR ================= -->
<aside id="sidebar">
    <div class="sidebar-logo text-center mb-4">
        <img src="https://cdn-icons-png.flaticon.com/512/784/784596.png" width="40" alt="Logo">
        <span>TradingSim</span>
    </div>

    <ul class="sidebar-menu list-unstyled">
        <li class="<?= basename($_SERVER['PHP_SELF'])=='index.php'?'active':'' ?>">
            <a href="simulation.php"><i class="bi bi-bar-chart"></i> <span>Simulator</span></a>
        </li>
    </ul>
</aside>

<!-- ================= MAIN AREA ================= -->
<div id="main">

    <!-- TOP NAVBAR -->
    <nav class="topbar d-flex justify-content-between align-items-center px-3">
        <div class="topbar-left d-flex align-items-center">
            <button id="sidebarToggle" class="theme-btn me-3">
                <i class="bi bi-list"></i>
            </button>
            <span class="text-light">Welcome, <?php echo htmlspecialchars($name); ?></span>
        </div>

        <div class="topbar-right d-flex align-items-center">
            <button id="themeToggle" class="theme-btn me-3">
                <i class="bi bi-moon-stars" id="themeIcon"></i>
            </button>
            <a href="logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <div class="container-fluid p-4">

        <!-- SIMULATION FORM -->
        <div class="card-panel">
            <h2>Monte Carlo Simulation</h2>
            <form id="simulationForm">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Initial Balance</label>
                        <input type="number" name="initial_capital" class="form-control" placeholder="5000" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Risk % per Trade</label>
                        <input type="number" step="0.01" name="risk_per_trade" class="form-control" placeholder="2" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Winning Trades %</label>
                        <input type="number" step="0.01" name="win_rate" class="form-control" placeholder="60" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Break Even Trades %</label>
                        <input type="number" step="0.01" name="breakeven_rate" class="form-control" placeholder="10" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Avg. Trades / Month</label>
                        <input type="number" name="avg_trades_per_month" class="form-control" placeholder="20" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Total Months</label>
                        <input type="number" name="total_months" class="form-control" placeholder="12" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">TP / SL Ratio</label>
                        <input type="number" step="0.1" name="avg_win_loss_ratio" class="form-control" placeholder="1.5" required>
                    </div>
                </div>
                <button class="btn btn-primary mt-4">Run Simulation</button>
            </form>
        </div>

        <!-- SCENARIO + CHART -->
        <div class="card-panel mt-4">
            <h3>Scenario Selection</h3>
            <div class="scenario-btns mb-3">
                <button class="btn btn-outline-primary" data-scenario="mostProbable">Most Probable</button>
                <button class="btn btn-outline-success" data-scenario="bestCase">Best Case</button>
                <button class="btn btn-outline-danger" data-scenario="worstCase">Worst Case</button>
            </div>

            <h3>Monte Carlo Chart</h3>
            <div class="chart-box">
                <canvas id="mcChart"></canvas>
            </div>

            <h3>Simulation Summary</h3>
            <div id="resultsSummary" class="summary-box"></div>

            <h3 class="mt-4">Summary Table</h3>
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="resultsTable">
                    <thead>
                        <tr>
                            <th>Initial</th>
                            <th>Final</th>
                            <th>Return %</th>
                            <th>Max DD</th>
                            <th>Max Loss Streak</th>
                            <th>Max Win Streak</th>
                            <th>Win %</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <h3 class="mt-4">Monthly Table</h3>
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="monthsTable">
                    <thead id="monthsTableHead"></thead>
                    <tbody></tbody>
                </table>
            </div>

            <nav id="monthsPagination" class="mt-2 d-flex justify-content-center" style="display:none;">
                <ul class="pagination"></ul>
            </nav>
        </div>

    </div> <!-- container -->
</div> <!-- main -->

<!-- Main JS -->
<script src="assets/js/main.js" defer></script>

<!-- ================= THEME TOGGLE SCRIPT ================= -->
<script>
document.addEventListener("DOMContentLoaded", () => {
    // Load saved theme
    if (localStorage.getItem("theme") === "light") {
        document.body.classList.remove("dark-theme");
        document.body.classList.add("light-theme");
        document.getElementById("themeIcon").className = "bi bi-sun";
    }

    document.getElementById("themeToggle").addEventListener("click", () => {
        const body = document.body;
        const icon = document.getElementById("themeIcon");

        if (body.classList.contains("dark-theme")) {
            body.classList.remove("dark-theme");
            body.classList.add("light-theme");
            icon.className = "bi bi-sun";
            localStorage.setItem("theme", "light");
        } else {
            body.classList.remove("light-theme");
            body.classList.add("dark-theme");
            icon.className = "bi bi-moon-stars";
            localStorage.setItem("theme", "dark");
        }
    });
});
</script>

</body>
</html>
