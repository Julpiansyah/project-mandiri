@extends('templates.app')

@section('content')
<style>
    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 12px;
        margin-bottom: 20px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
        transition: transform 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-card.cyan {
        background: linear-gradient(135deg, #0097a7 0%, #00bcd4 100%);
    }

    .stat-card.green {
        background: linear-gradient(135deg, #00b894 0%, #00cec9 100%);
    }

    .stat-card.orange {
        background: linear-gradient(135deg, #ff6348 0%, #ff8c42 100%);
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 900;
        margin: 10px 0;
    }

    .stat-label {
        font-size: 0.95rem;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .chart-container {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }

    .chart-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 3px solid #667eea;
    }

    .analytics-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    .analytics-table th {
        background: #f5f5f5;
        padding: 12px;
        text-align: left;
        font-weight: 700;
        color: #333;
        border-bottom: 2px solid #ddd;
    }

    .analytics-table td {
        padding: 12px;
        border-bottom: 1px solid #eee;
        color: #666;
    }

    .analytics-table tr:hover {
        background: #f9f9f9;
    }

    .badge-count {
        background: #667eea;
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .badge-revenue {
        background: #00b894;
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
    }
</style>

<div class="container-fluid py-4">
    <h1 class="mb-4" style="font-size: 2rem; font-weight: 900; color: #333;">Admin Dashboard</h1>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-label">Total Events</div>
                <div class="stat-number">{{ $totalEvents }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card cyan">
                <div class="stat-label">Total Payments</div>
                <div class="stat-number">{{ $totalPayments }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card green">
                <div class="stat-label">Total Users</div>
                <div class="stat-number">{{ $totalUsers }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card orange">
                <div class="stat-label">Total Revenue</div>
                <div class="stat-number">Rp {{ number_format($totalRevenue / 1000000, 1) }}M</div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row">
        <!-- Pie Chart -->
        <div class="col-md-6">
            <div class="chart-container">
                <div class="chart-title">🎭 Event Paling Diminati</div>
                <canvas id="eventChart" height="300"></canvas>
            </div>
        </div>

        <!-- Analytics Table -->
        <div class="col-md-6">
            <div class="chart-container">
                <div class="chart-title">📊 Analisis Event Detail</div>
                <table class="analytics-table">
                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>Terjual</th>
                            <th>Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($eventAnalytics as $event)
                            <tr>
                                <td><strong>{{ \Illuminate\Support\Str::limit($event['title'], 20) }}</strong></td>
                                <td><span class="badge-count">{{ $event['count'] }} tiket</span></td>
                                <td><span class="badge-revenue">Rp {{ number_format($event['revenue'], 0, ',', '.') }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center" style="padding: 20px; color: #999;">Belum ada data event</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<script>
    // Prepare data
    const chartLabels = {!! json_encode($chartLabels) !!};
    const chartData = {!! json_encode($chartData) !!};
    const chartColors = {!! json_encode($chartColors) !!};

    // Create Pie Chart
    const ctx = document.getElementById('eventChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: chartLabels,
            datasets: [{
                data: chartData,
                backgroundColor: chartColors,
                borderColor: '#fff',
                borderWidth: 2,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: { size: 12, weight: 'bold' },
                        padding: 15,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.parsed + ' tiket';
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
