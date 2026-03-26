import random

def monte_carlo(
    initial_balance,
    risk_percent,
    win_rate,
    break_even_rate,
    trades_per_month,
    total_months,
    tp_sl_ratio,
    simulations=50
):
    num_trades = trades_per_month * total_months
    results = []

    for _ in range(simulations):
        balance = initial_balance
        trajectory = [balance]
        consec_wins = 0
        consec_losses = 0
        max_wins = 0
        max_losses = 0
        peak = balance
        max_drawdown = 0
        win_count = 0

        for _ in range(num_trades):
            risk_amount = balance * (risk_percent / 100)
            roll = random.random()

            if roll < win_rate / 100:
                balance += risk_amount * tp_sl_ratio
                consec_wins += 1
                consec_losses = 0
                win_count += 1
            elif roll < (win_rate + break_even_rate) / 100:
                consec_wins = 0
                consec_losses = 0
            else:
                balance -= risk_amount
                consec_losses += 1
                consec_wins = 0

            if consec_wins > max_wins: max_wins = consec_wins
            if consec_losses > max_losses: max_losses = consec_losses

            # Drawdown
            if balance > peak: peak = balance
            drawdown = peak - balance
            if drawdown > max_drawdown: max_drawdown = drawdown

            trajectory.append(balance)

        results.append({
            "trajectory": trajectory,
            "balance": balance,
            "drawdown": max_drawdown,
            "maxWins": max_wins,
            "maxLosses": max_losses,
            "winPercent": win_count / num_trades
        })

    return results
