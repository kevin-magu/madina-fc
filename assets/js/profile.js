document.addEventListener('DOMContentLoaded', function() {
    // Goals Chart
    const goalsCtx = document.getElementById('goalsChart').getContext('2d');
    const goalsChart = new Chart(goalsCtx, {
        type: 'bar',
        data: {
            labels: ['Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar'],
            datasets: [{
                label: 'Goals per Month',
                data: [3, 2, 4, 1, 5, 2, 3, 4],
                backgroundColor: '#e94560',
                borderColor: '#e94560',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + ' goal' + (context.parsed.y !== 1 ? 's' : '');
                        }
                    }
                }
            }
        }
    });

    // Minutes Played Chart
    const minutesCtx = document.getElementById('minutesChart').getContext('2d');
    const minutesChart = new Chart(minutesCtx, {
        type: 'line',
        data: {
            labels: ['Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar'],
            datasets: [{
                label: 'Minutes Played',
                data: [85, 90, 75, 60, 90, 45, 80, 90],
                fill: true,
                backgroundColor: 'rgba(15, 52, 96, 0.1)',
                borderColor: '#0f3460',
                tension: 0.3,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 90
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + ' minutes';
                        }
                    }
                }
            }
        }
    });
});