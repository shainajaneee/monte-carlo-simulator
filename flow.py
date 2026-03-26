import numpy as np
import matplotlib.pyplot as plt
import matplotlib.ticker as mticker

initial_capital = 10000
risk_per_trade = 0.01     
win_rate = 0.45            
breakeven_rate = 0.05      
reward_to_risk = 2.0       
trades_per_month = 20
total_months = 12
num_simulations = 1000     

total_trades = trades_per_month * total_months

final_balances = []

for _ in range(num_simulations):
    balance = initial_capital
    for _ in range(total_trades):
        rand = np.random.random()
        
        if rand < win_rate:
            balance += (balance * risk_per_trade * reward_to_risk)
        elif rand < (win_rate + breakeven_rate):
            pass
        else:
            balance -= (balance * risk_per_trade)
            
        if balance <= 0:
            balance = 0
            break
            
    final_balances.append(balance)

final_balances = np.array(final_balances)
mean_val = np.mean(final_balances)
median_val = np.median(final_balances)
min_val = np.min(final_balances)
max_val = np.max(final_balances)

plt.figure(figsize=(10, 6), facecolor='#f8fafc')
n, bins, patches = plt.hist(final_balances, bins=40, color='#3b82f6', edgecolor='#1e3a8a', alpha=0.8)

plt.title("Monte Carlo Simulation: Final Capital Distribution", fontsize=16, fontweight='bold', pad=20)
plt.xlabel("Ending Balance ($)", fontsize=12)
plt.ylabel("Number of Scenarios", fontsize=12)

plt.gca().xaxis.set_major_formatter(mticker.StrMethodFormatter('${x:,.0f}'))

plt.axvline(mean_val, color='#ef4444', linestyle='--', linewidth=2, label=f'Average: ${mean_val:,.2f}')
plt.axvline(median_val, color='#f59e0b', linestyle='-', linewidth=2, label=f'Median: ${median_val:,.2f}')

plt.axvspan(initial_capital, max_val, color='green', alpha=0.05, label='Profitable Zone')

plt.legend(frameon=True, shadow=True)
plt.grid(axis='y', linestyle=':', alpha=0.6)

plt.tight_layout()
plt.savefig("final_balance_histogram.png", dpi=300)
print(f"Success! Histogram saved. \nMedian Outcome: ${median_val:,.2f}")
plt.show()