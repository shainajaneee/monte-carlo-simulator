document.addEventListener("DOMContentLoaded", () => {

    const form = document.getElementById("simulationForm");
    const ctx = document.getElementById("mcChart").getContext("2d");
    const resultsSummary = document.getElementById("resultsSummary");
    const resultsTableBody = document.querySelector("#resultsTable tbody");
    const monthsTableBody = document.querySelector("#monthsTable tbody");
    const monthsTableHead = document.querySelector("#monthsTableHead");
    const scenarioButtons = document.querySelectorAll(".scenario-btns .btn");
    const monthsPagination = document.getElementById("monthsPagination");
    const paginationList = monthsPagination.querySelector(".pagination");

    let chart;
    let simulationData = null;
    let currentPage = 1;
    const monthsPerPage = 12;

    const formatDollar = v => `$${Number(v).toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2})}`;
    const formatPercent = v => `${Number(v).toFixed(1)}%`;

    // Render chart
    const renderChart = (data) => {
        if (!data.mostProbableTrajectory) return;

        const numPoints = data.mostProbableTrajectory.length;
        const labels = Array.from({length: numPoints}, (_, i) => i + 1);

        const datasets = [
            { label: "Most Probable", data: data.mostProbableTrajectory, borderColor: "blue", fill: false, pointRadius: 0 },
            { label: "Best Case", data: data.bestTrajectory, borderColor: "green", fill: false, pointRadius: 0 },
            { label: "Worst Case", data: data.worstTrajectory, borderColor: "red", fill: false, pointRadius: 0 }
        ];

        if(chart) chart.destroy();
        chart = new Chart(ctx, {
            type: "line",
            data: { labels, datasets },
            options: {
                responsive: true,
                plugins: { legend: { display: true } },
                scales: {
                    y: { title: { display: true, text: "Balance ($)" } },
                    x: { title: { display: true, text: "Trades" } }
                }
            }
        });
    };

    // Render monthly table with pagination
    const renderMonthlyTable = (monthly, page = 1) => {
        monthsTableBody.innerHTML = "";
        monthsTableHead.innerHTML = "";

        if(!monthly || monthly.length === 0) return;

        const totalMonths = monthly.length;
        const totalPages = Math.ceil(totalMonths / monthsPerPage);
        currentPage = Math.min(Math.max(page, 1), totalPages);

        const startIdx = (currentPage - 1) * monthsPerPage;
        const endIdx = Math.min(startIdx + monthsPerPage, totalMonths);

        // Table header shows year of first month in page
        const thRow = document.createElement("tr");
        const th = document.createElement("th");
        th.colSpan = 3;
        th.style.textAlign = "center";
        th.textContent = `Year ${monthly[startIdx].year}`;
        thRow.appendChild(th);
        monthsTableHead.appendChild(thRow);

        for (let i = startIdx; i < endIdx; i++) {
            const m = monthly[i];
            const tr = document.createElement("tr");
            tr.innerHTML = `<td><b>${m.monthName}</b></td><td style="color:${m.percent>=0?'green':'red'}">${formatPercent(m.percent)}</td><td style="color:${m.dollar>=0?'green':'red'}">${formatDollar(m.dollar)}</td>`;
            monthsTableBody.appendChild(tr);
        }

        // Pagination
        if(totalPages > 1){
            monthsPagination.style.display="flex";
            paginationList.innerHTML="";

            const createPageItem = (text, disabled=false, active=false, onClick=null) => {
                const li = document.createElement("li");
                li.className = `page-item${disabled?" disabled":""}${active?" active":""}`;
                li.innerHTML = `<a class="page-link" href="#">${text}</a>`;
                if(onClick) li.addEventListener("click", e=>{ e.preventDefault(); onClick(); });
                return li;
            };

            paginationList.appendChild(createPageItem("Prev", currentPage===1, false, ()=>renderMonthlyTable(monthly,currentPage-1)));
            for(let i=1; i<=totalPages; i++){
                paginationList.appendChild(createPageItem(i, false, i===currentPage, ()=>renderMonthlyTable(monthly,i)));
            }
            paginationList.appendChild(createPageItem("Next", currentPage===totalPages, false, ()=>renderMonthlyTable(monthly,currentPage+1)));
        } else monthsPagination.style.display="none";
    };

    // Update scenario view
    const updateScenario = (key) => {
        if(!simulationData) return;
        const s = simulationData[key];

        resultsSummary.innerHTML = `<div class="row text-center">
            <div class="col-md-3 mb-2"><b>Initial Balance</b><br>${formatDollar(simulationData.initialBalance)}</div>
            <div class="col-md-3 mb-2"><b>Result Balance</b><br>${formatDollar(s.balance)}</div>
            <div class="col-md-3 mb-2"><b>Return %</b><br style="color:${s.return>=0?'green':'red'}">${formatPercent(s.return)}</div>
            <div class="col-md-3 mb-2"><b>Max Drawdown</b><br>${formatPercent(s.drawdown)}</div>
        </div>`;

        resultsTableBody.innerHTML = `<tr>
            <td>${formatDollar(simulationData.initialBalance)}</td>
            <td>${formatDollar(s.balance)}</td>
            <td style="color:${s.return>=0?'green':'red'}">${formatPercent(s.return)}</td>
            <td>${formatPercent(s.drawdown)}</td>
            <td>${s.maxLosses}</td>
            <td>${s.maxWins}</td>
            <td>${formatPercent(s.winPercent*100)}</td>
        </tr>`;

        renderMonthlyTable(s.monthly);
        renderChart(simulationData);

        // Save to DB
        saveSimulationToDB(key);
    };

    // Scenario buttons
    scenarioButtons.forEach(btn => btn.addEventListener("click", ()=>updateScenario(btn.dataset.scenario)));

    // Save simulation to DB
    const saveSimulationToDB = async (key) => {
        if(!simulationData) return;
        const s = simulationData[key];

        try {
            const res = await fetch("insert_user_simulation.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    mostProbable: s.balance,
                    bestCase: simulationData.bestCase.balance,
                    worstCase: simulationData.worstCase.balance
                })
            });
            const result = await res.json();
            console.log("Simulation saved:", result);
        } catch(err){
            console.error("Failed to save simulation:", err);
        }
    };

    // Form submission -> run simulation
    form.addEventListener("submit", e => {
        e.preventDefault();
        const formData = new FormData(form);
        formData.append("num_simulations", 50); // example default

        fetch("simulation.php", { method: "POST", body: formData })
        .then(res => res.json())
        .then(data => {
            if(data.error){
                resultsSummary.innerHTML = `<p class="text-danger">${data.error}</p>`;
                return;
            }

            // Prepare simulationData object
            simulationData = {
                initialBalance: data.initialBalance,
                mostProbable: {
                    balance: data.mostProbable,
                    return: data.mostProbableReturn,
                    drawdown: data.mostProbableDrawdown,
                    maxWins: data.mostProbableMaxWins,
                    maxLosses: data.mostProbableMaxLosses,
                    winPercent: data.mostProbableWinPercent,
                    monthly: data.monthlyMostProbable
                },
                bestCase: {
                    balance: data.bestCase,
                    return: data.bestReturn,
                    drawdown: data.bestDrawdown,
                    maxWins: data.bestMaxWins,
                    maxLosses: data.bestMaxLosses,
                    winPercent: data.bestWinPercent,
                    monthly: data.monthlyBest
                },
                worstCase: {
                    balance: data.worstCase,
                    return: data.worstReturn,
                    drawdown: data.worstDrawdown,
                    maxWins: data.worstMaxWins,
                    maxLosses: data.worstMaxLosses,
                    winPercent: data.worstWinPercent,
                    monthly: data.monthlyWorst
                },
                mostProbableTrajectory: data.mostProbableTrajectory,
                bestTrajectory: data.bestTrajectory,
                worstTrajectory: data.worstTrajectory
            };

            updateScenario("mostProbable");
        })
        .catch(err => {
            console.error(err);
            resultsSummary.innerHTML = `<p class="text-danger">Simulation failed. Check console.</p>`;
        });
    });

});
