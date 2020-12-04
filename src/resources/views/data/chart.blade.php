<head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"
            integrity="sha512-d9xgZrVZpmmQlfonhQUvTR7lMPtO7NkZMkA0ABN3PHCbKA5nqylQ/yWlFAyY6hYgdF1Qh6nYiuADWwKB4C2WSw=="
            crossorigin="anonymous"></script>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <title>Chart for {{ $name }} table</title>
</head>

<div style="display: flex; align-content: center; justify-content: space-between;" class="p-5">
    <h1 class="text-3xl">Chart showing {{ $name }} table</h1>
    <p class="text-sm">{{ $timestamp }}</p>
</div>

<div class="px-3 py-4" style="width: 60%; min-width: 600px;">
    @if (count(json_decode($data)) > 0)
        <canvas id="myChart" height="400"></canvas>
    @else
        <p>This table is empty</p>
    @endif
</div>


<script>
    var ctx = document.getElementById('myChart');

    var scatterChart = new Chart(ctx, {
        type: 'scatter',
        data: {
            datasets: [{
                label: '{!! $name !!}',
                data: {!! $data !!},
                backgroundColor: 'rgba(100,100,255)'
            }]
        },
        options: {
            scales: {
                xAxes: [{
                    type: 'linear',
                    position: 'bottom'
                }]
            },
            responsive: true,
            maintainAspectRatio: false
        }
    });

    scatterChart.update();
</script>
