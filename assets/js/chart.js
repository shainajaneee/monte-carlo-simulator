let mcChart;

function renderChart(data) {
    const ctx = document.getElementById("mcChart").getContext("2d");

    // Create x-axis labels based on number of points in mostProbableTrajectory
    const numPoints = data.mostProbableTrajectory.length;
    const labels = Array.from({ length: numPoints }, (_, i) => i + 1); // 1,2,3,... trades

    const datasets = [
        {
            label: "Most Probable",
            data: data.mostProbableTrajectory,
            borderColor: "blue",
            fill: false,
            pointRadius: 0,
            borderWidth: 1.5
        },
        {
            label: "Best Case",
            data: data.bestTrajectory,
            borderColor: "green",
            fill: false,
            pointRadius: 0,
            borderWidth: 1.5
        },
        {
            label: "Worst Case",
            data: data.worstTrajectory,
            borderColor: "red",
            fill: false,
            pointRadius: 0,
            borderWidth: 1.5
        }
    ];

    if (mcChart) mcChart.destroy();

    mcChart = new Chart(ctx, {
        type: "line",
        data: {
            labels,   // <-- important
            datasets
        },
        options: {
            responsive: true,
            plugins: { 
                legend: { display: true } 
            },
            scales: {
                y: { 
                    title: { display: true, text: "Balance ($)" } 
                },
                x: { 
                    title: { display: true, text: "Trades" } 
                }
            }
        }
    });
}

new Chart(pieCtx, {
    type: 'pie',
    data: {
        labels,
        datasets: [{
            label: 'Most Probable Balance Distribution (%)',
            data: dataPercent,
            backgroundColor: labels.map((_,i)=>`hsl(${i*40 % 360}, 70%, 50%)`)
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,  // <-- ensures chart stays circular
        aspectRatio: 1,             // <-- forces width = height
        plugins: {
            legend: { position: 'right' },
            title: { display: true, text: 'Most Probable Balance Distribution (%)' }
        }
    }
});

