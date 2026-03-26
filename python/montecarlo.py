import numpy as np
import matplotlib.pyplot as plt
import matplotlib.ticker as mticker

# ----------------------------
# Simulation parameters
# ----------------------------
mean_final_balance = 14664.20   # Average final balance from your simulation
std_dev = 2500                  # Standard deviation for variability
num_simulations = 1000          # Number of simulation runs

# Generate mock final capital data
final_balances = np.random.normal(mean_final_balance, std_dev, num_simulations)
final_balances = np.maximum(final_balances, 0)  # Ensure no negative balances

# ----------------------------
# Create histogram
# ----------------------------
plt.figure(figsize=(10,6))  # Wider and taller for clarity

plt.hist(final_balances, bins=30, color='skyblue', edgecolor='black')
plt.title("Figure 1. Distribution of Final Capital Across Simulations", fontsize=12)
plt.xlabel("Final Capital ($)", fontsize=11)
plt.ylabel("Frequency", fontsize=11)

# Format X-axis as normal numbers
plt.gca().xaxis.set_major_formatter(mticker.StrMethodFormatter('${x:,.0f}'))

# Highlight mean, min, max
plt.axvline(mean_final_balance, color='red', linestyle='dashed', linewidth=1.5, label=f'Mean: ${mean_final_balance:,.2f}')
plt.axvline(final_balances.min(), color='green', linestyle='dotted', linewidth=1, label=f'Min: ${final_balances.min():,.2f}')
plt.axvline(final_balances.max(), color='blue', linestyle='dotted', linewidth=1, label=f'Max: ${final_balances.max():,.2f}')

# Legend
plt.legend(loc='upper right', fontsize=10)

# Grid
plt.grid(axis='y', alpha=0.75)

# Layout adjustment
plt.tight_layout()

# Show plot
plt.show()

# Optional: Save figure
plt.savefig("final_capital_distribution.png", dpi=300)
