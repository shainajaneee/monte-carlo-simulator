



<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");

$monthNames = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
$startYear = 2026;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $initial = floatval($_POST["initial_capital"] ?? 0);
    $risk = floatval($_POST["risk_per_trade"] ?? 2) / 100;
    $winRate = floatval($_POST["win_rate"] ?? 60) / 100;
    $breakevenRate = floatval($_POST["breakeven_rate"] ?? 10) / 100;
    $ratio = floatval($_POST["avg_win_loss_ratio"] ?? 1.5);
    $tradesPerMonth = intval($_POST["avg_trades_per_month"] ?? 20);
    $months = intval($_POST["total_months"] ?? 12);
    $numSimulations = intval($_POST["num_simulations"] ?? 50);

    $numTrades = $tradesPerMonth * $months;
    $allTrajectories = [];
    $finalBalances = [];
    $monthlyDataList = []; // store monthly data for each simulation

    for ($s = 0; $s < $numSimulations; $s++) {
        $balance = $initial;
        $trajectory = [$balance];
        $consecWins = 0;
        $consecLosses = 0;
        $maxConsecWins = 0;
        $maxConsecLosses = 0;
        $peak = $balance;
        $maxDrawdown = 0;
        $winCount = 0;

        $monthlyStartBalance = $balance;
        $tradesDone = 0;
        $monthlyPercentArr = array_fill(0, $months, 0);
        $monthlyDollarArr = array_fill(0, $months, $initial);

        for ($t = 0; $t < $numTrades; $t++) {
            $random = mt_rand() / mt_getrandmax();

            if ($random < $winRate) {
                $balance += $balance * $risk * $ratio;
                $consecWins++;
                $consecLosses = 0;
                $winCount++;
            } elseif ($random < $winRate + $breakevenRate) {
                $consecWins = 0;
                $consecLosses = 0;
            } else {
                $balance -= $balance * $risk;
                $consecLosses++;
                $consecWins = 0;
            }

            $trajectory[] = $balance;

            if ($balance > $peak) $peak = $balance;
            $drawdown = $peak - $balance;
            if ($drawdown > $maxDrawdown) $maxDrawdown = $drawdown;

            if ($consecWins > $maxConsecWins) $maxConsecWins = $consecWins;
            if ($consecLosses > $maxConsecLosses) $maxConsecLosses = $consecLosses;

            $tradesDone++;
            if ($tradesDone % $tradesPerMonth === 0) {
                $monthIndex = intval($tradesDone / $tradesPerMonth) - 1;
                if ($monthIndex < $months) {
                    $monthlyPercentArr[$monthIndex] = (($balance - $monthlyStartBalance)/max($monthlyStartBalance,1))*100;
                    $monthlyDollarArr[$monthIndex] = $balance;
                    $monthlyStartBalance = $balance;
                }
            }
        }

        $allTrajectories[] = $trajectory;

        $finalBalances[] = [
            "balance" => $balance,
            "drawdown" => $maxDrawdown,
            "maxWins" => $maxConsecWins,
            "maxLosses" => $maxConsecLosses,
            "winPercent" => $winCount / $numTrades,
            "trajectory" => $trajectory,
            "monthlyPercent" => $monthlyPercentArr,
            "monthlyDollar" => $monthlyDollarArr
        ];
    }

    // Sort by final balance
    usort($finalBalances, fn($a,$b) => $a['balance'] <=> $b['balance']);

    $worst = $finalBalances[0];
    $best = $finalBalances[$numSimulations-1];
    $mostProbable = $finalBalances[intval($numSimulations/2)];

    $buildMonthlyObjects = function($percentArr, $dollarArr) use ($monthNames, $startYear) {
        $obj = [];
        for ($i = 0; $i < count($percentArr); $i++) {
            $year = $startYear + floor($i / 12);
            $monthIndex = $i % 12;
            $obj[] = [
                "percent" => $percentArr[$i],
                "dollar" => $dollarArr[$i],
                "year" => $year,
                "monthName" => $monthNames[$monthIndex]
            ];
        }
        return $obj;
    };

    echo json_encode([
        "initialBalance" => $initial,
        "numTrades" => $numTrades,

        "mostProbable" => $mostProbable['balance'],
        "mostProbableReturn" => ($mostProbable['balance']-$initial)/$initial*100,
        "mostProbableDrawdown" => $mostProbable['drawdown'],
        "mostProbableMaxWins" => $mostProbable['maxWins'],
        "mostProbableMaxLosses" => $mostProbable['maxLosses'],
        "mostProbableWinPercent" => $mostProbable['winPercent'],
        "mostProbableTrajectory" => $mostProbable['trajectory'],
        "monthlyMostProbable" => $buildMonthlyObjects($mostProbable['monthlyPercent'], $mostProbable['monthlyDollar']),

        "bestCase" => $best['balance'],
        "bestReturn" => ($best['balance']-$initial)/$initial*100,
        "bestDrawdown" => $best['drawdown'],
        "bestMaxWins" => $best['maxWins'],
        "bestMaxLosses" => $best['maxLosses'],
        "bestWinPercent" => $best['winPercent'],
        "bestTrajectory" => $best['trajectory'],
        "monthlyBest" => $buildMonthlyObjects($best['monthlyPercent'], $best['monthlyDollar']),

        "worstCase" => $worst['balance'],
        "worstReturn" => ($worst['balance']-$initial)/$initial*100,
        "worstDrawdown" => $worst['drawdown'],
        "worstMaxWins" => $worst['maxWins'],
        "worstMaxLosses" => $worst['maxLosses'],
        "worstWinPercent" => $worst['winPercent'],
        "worstTrajectory" => $worst['trajectory'],
        "monthlyWorst" => $buildMonthlyObjects($worst['monthlyPercent'], $worst['monthlyDollar']),
    ]);
    exit;
}

echo json_encode(["error"=>"Invalid request"]);
exit;
