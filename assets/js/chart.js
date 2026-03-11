/**
 * Chart.js Integration
 * 
 * Fetches BP data from API and renders a line chart.
 */

document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('bpChart');
    if (!canvas) return; // not on this page

    fetch('/medtrack/api/charts_data.php')
        .then(response => {
            if (!response.ok) throw new Error('Failed to fetch chart data');
            return response.json();
        })
        .then(data => {
            if (!data.success) throw new Error(data.error || 'Unknown error');

            const ctx = canvas.getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.data.labels,
                    datasets: [{
                        label: 'Systolic BP',
                        data: data.data.systolic,
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: true },
                        tooltip: { enabled: true }
                    },
                    scales: {
                        y: { beginAtZero: false }
                    }
                }
            });
        })
        .catch(err => {
            console.error('Chart error:', err);
            canvas.parentElement.innerHTML += '<p class="text-muted">Unable to load chart.</p>';
        });
});