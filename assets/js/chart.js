// Load BP chart
fetch('/medtrack/api/charts_data.php')
    .then(res => res.json())
    .then(data => {
        const ctx = document.getElementById('bpChart')?.getContext('2d');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Systolic BP',
                        data: data.systolic,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                    }]
                }
            });
        }
    });