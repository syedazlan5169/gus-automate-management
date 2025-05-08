import Chart from "chart.js/auto";

let chartInstance = null;

function initializeChart() {
    const ctx = document.getElementById("monthlyBookingsChart");
    if (!ctx) return;

    // Destroy existing chart if it exists
    if (chartInstance) {
        chartInstance.destroy();
    }

    const data = {
        labels: [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December",
        ],
        datasets: [
            {
                label: "Monthly Bookings",
                data: window.monthlyData || [],
                borderColor: "rgb(59, 130, 246)",
                backgroundColor: "rgba(59, 130, 246, 0.1)",
                borderWidth: 2,
                fill: true,
                pointBackgroundColor: "rgb(59, 130, 246)",
                pointBorderColor: "#fff",
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
            },
        ],
    };

    const config = {
        type: "line",
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: "top",
                },
                title: {
                    display: true,
                    text:
                        "Monthly Bookings Overview - " +
                        new Date().getFullYear(),
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                    },
                    grid: {
                        color: "rgba(0, 0, 0, 0.1)",
                    },
                },
                x: {
                    grid: {
                        display: false,
                    },
                },
            },
        },
    };

    try {
        chartInstance = new Chart(ctx, config);
    } catch (error) {
        console.error("Chart initialization error:", error);
    }
}

// Initialize chart when DOM is loaded
document.addEventListener("DOMContentLoaded", initializeChart);

// Reinitialize chart when page becomes visible again
document.addEventListener("visibilitychange", function () {
    if (document.visibilityState === "visible") {
        initializeChart();
    }
});

// Handle Livewire/Turbo navigation
document.addEventListener("turbo:load", initializeChart);
document.addEventListener("livewire:load", initializeChart);
document.addEventListener("livewire:navigated", initializeChart);
