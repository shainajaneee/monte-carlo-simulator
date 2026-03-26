let mcChart;

function renderChart(data, activeScenario = 'mostProbable') {
    const ctx = document.getElementById("mcChart").getContext("2d");
    
    // Create Gradient for the main line
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(59, 130, 246, 0.2)');   // Blue top
    gradient.addColorStop(1, 'rgba(59, 130, 246, 0.0)');   // Transparent bottom

    const numPoints = data.mostProbableTrajectory.length;
    const labels = Array.from({ length: numPoints }, (_, i) => i + 1);

    const datasets = [
        {
            label: "Most Probable",
            data: data.mostProbableTrajectory,
            borderColor: "#3b82f6",
            backgroundColor: gradient,
            fill: true,             // Fill area under the curve
            pointRadius: 0,
            borderWidth: 3,
            tension: 0.3,           // Smooth lines
        },
        {
            label: "Best Case",
            data: data.bestTrajectory,
            borderColor: "#10b981", // Emerald Green
            fill: false,
            pointRadius: 0,
            borderWidth: 2,
            borderDash: [5, 5],    // Dashed line for "potential"
            tension: 0.3,
        },
        {
            label: "Worst Case",
            data: data.worstTrajectory,
            borderColor: "#ef4444", // Rose Red
            fill: false,
            pointRadius: 0,
            borderWidth: 2,
            borderDash: [5, 5],
            tension: 0.3,
        }
    ];

    if (mcChart) mcChart.destroy();

    mcChart = new Chart(ctx, {
        type: "line",
        data: { labels, datasets },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: { 
                legend: { 
                    position: 'top',
                    labels: { color: '#94a3b8', font: { weight: 'bold' }, usePointStyle: true }
                }
            },
            scales: {
                y: { 
                    grid: { color: 'rgba(255, 255, 255, 0.05)' },
                    ticks: { color: '#64748b', callback: value => '$' + value.toLocaleString() }
                },
                x: { 
                    grid: { display: false },
                    ticks: { color: '#64748b', maxRotation: 0 }
                }
            }
        }
    });
}