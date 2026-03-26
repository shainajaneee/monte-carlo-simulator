# 📈 TradeSim: Monte Carlo Trading Simulator

**Developer:** Tanguan Shaina Jane  
**Algorithm:** Monte Carlo Simulation (Stochastic Modeling)

TradeSim is a professional-grade web application designed to help traders understand risk and strategy expectancy through probabilistic simulation. By running thousands of trade iterations, users can visualize potential drawdowns and final capital distributions before risking real money.

---

## 🚀 Key Features
- **Deterministic & Stochastic Modeling:** Input win rates and risk parameters to forecast outcomes.
- **Dynamic Visualizations:** Interactive charts showing equity curves and capital distribution.
- **Secure Authentication:** Complete User Management system (Sign up/Login/Logout).
- **Risk Metrics:** Calculates Maximum Drawdown, Win/Loss Streaks, and Profit Factor.

## 🛠️ Tech Stack
- **Frontend:** HTML5, Tailwind CSS, JavaScript (Chart.js)
- **Backend:** PHP 7.4/8.x
- **Database:** MySQL
- **Optional:** Python (Advanced Analytics)

## 📂 Project Structure
```text
├── assets/
│   ├── js/          # Chart logic and UI interactions
│   └── css/         # Tailwind configurations & custom styles
├── config.php       # Database connection strings
├── simulation.php   # Core Simulation Engine
├── index.php        # User Dashboard
└── tradesim_db.sql  # Database Schema