<?php
require_once '../config/session.php';
checkLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgriTrack Pro - Progress Reports</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .bg-nature {
            background: linear-gradient(135deg, #F3F4F6 0%, #E8F5E9 100%);
        }
        .dark .bg-nature {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        }
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        .loading {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }
        .loading-spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #4CAF50;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-nature min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white dark:bg-gray-800 shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="../dashboard.php" class="text-xl font-bold text-nature">AgriTrack Pro</a>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="../dashboard.php" class="border-transparent text-gray-500 dark:text-gray-300 hover:border-gray-300 hover:text-gray-700 dark:hover:text-gray-200 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Dashboard
                        </a>
                        <a href="crop-progression.php" class="border-transparent text-gray-500 dark:text-gray-300 hover:border-gray-300 hover:text-gray-700 dark:hover:text-gray-200 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Crop Progression
                        </a>
                        <a href="gis-integration.php" class="border-transparent text-gray-500 dark:text-gray-300 hover:border-gray-300 hover:text-gray-700 dark:hover:text-gray-200 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            GIS Integration
                        </a>
                        <a href="progress-reports.php" class="border-nature text-nature inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Progress Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Progress Reports</h1>
                    <button id="exportBtn" class="btn-primary flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Export Report
                    </button>
                </div>
                
                <!-- Report Filters -->
                <div class="mb-8">
                    <div class="flex flex-wrap gap-4">
                        <select id="cropFilter" class="form-input rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                            <option value="all">All Crops</option>
                            <option value="wheat">Wheat</option>
                            <option value="rice">Rice</option>
                            <option value="corn">Corn</option>
                        </select>
                        <select id="timeFilter" class="form-input rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                            <option value="7">Last 7 Days</option>
                            <option value="30">Last 30 Days</option>
                            <option value="90">Last 3 Months</option>
                            <option value="180">Last 6 Months</option>
                        </select>
                        <button id="generateBtn" class="btn-primary">Generate Report</button>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Growth Progress</h3>
                        <div class="chart-container">
                            <div class="loading hidden">
                                <div class="loading-spinner"></div>
                            </div>
                            <canvas id="growthChart"></canvas>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Yield Prediction</h3>
                        <div class="chart-container">
                            <div class="loading hidden">
                                <div class="loading-spinner"></div>
                            </div>
                            <canvas id="yieldChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Detailed Report -->
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Detailed Analysis</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Crop</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Growth Stage</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Health Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="reportTableBody" class="divide-y divide-gray-200 dark:divide-gray-600">
                                <!-- Table rows will be loaded dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let growthChart, yieldChart;
        let reportData = [];

        $(document).ready(function() {
            // Initialize charts
            initializeCharts();
            
            // Load initial data
            loadReportData();

            // Event listeners
            $('#generateBtn').click(loadReportData);
            $('#cropFilter, #timeFilter').change(loadReportData);
            $('#exportBtn').click(exportReport);

            // Auto-refresh data every 5 minutes
            setInterval(loadReportData, 300000);
        });

        function initializeCharts() {
            const growthCtx = document.getElementById('growthChart').getContext('2d');
            growthChart = new Chart(growthCtx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Growth Progress',
                        data: [],
                        borderColor: '#4CAF50',
                        tension: 0.4,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        }
                    }
                }
            });

            const yieldCtx = document.getElementById('yieldChart').getContext('2d');
            yieldChart = new Chart(yieldCtx, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Expected Yield (tons)',
                        data: [],
                        backgroundColor: '#4CAF50'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        function loadReportData() {
            showLoading();
            
            const cropFilter = $('#cropFilter').val();
            const timeFilter = $('#timeFilter').val();

            $.ajax({
                url: '../api/progress-reports.php',
                method: 'GET',
                data: {
                    crop: cropFilter,
                    time: timeFilter
                },
                success: function(response) {
                    updateCharts(response);
                    updateTable(response.tableData);
                    hideLoading();
                },
                error: function(xhr, status, error) {
                    console.error('Error loading report data:', error);
                    hideLoading();
                }
            });
        }

        function updateCharts(data) {
            // Update growth chart
            growthChart.data.labels = data.growthData.labels;
            growthChart.data.datasets[0].data = data.growthData.values;
            growthChart.update();

            // Update yield chart
            yieldChart.data.labels = data.yieldData.labels;
            yieldChart.data.datasets[0].data = data.yieldData.values;
            yieldChart.update();
        }

        function updateTable(data) {
            let html = '';
            data.forEach(row => {
                html += `
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">${row.date}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">${row.crop}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">${row.stage}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm ${row.statusColor}">${row.status}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-nature">
                            <button class="hover:underline">View Details</button>
                        </td>
                    </tr>
                `;
            });
            $('#reportTableBody').html(html);
        }

        function showLoading() {
            $('.loading').removeClass('hidden');
        }

        function hideLoading() {
            $('.loading').addClass('hidden');
        }

        function exportReport() {
            const wb = XLSX.utils.book_new();
            
            // Create growth data worksheet
            const growthData = [
                ['Week', 'Growth Progress (%)'],
                ...growthChart.data.labels.map((label, index) => [
                    label,
                    growthChart.data.datasets[0].data[index]
                ])
            ];
            const growthWs = XLSX.utils.aoa_to_sheet(growthData);
            XLSX.utils.book_append_sheet(wb, growthWs, "Growth Progress");

            // Create yield data worksheet
            const yieldData = [
                ['Crop', 'Expected Yield (tons)'],
                ...yieldChart.data.labels.map((label, index) => [
                    label,
                    yieldChart.data.datasets[0].data[index]
                ])
            ];
            const yieldWs = XLSX.utils.aoa_to_sheet(yieldData);
            XLSX.utils.book_append_sheet(wb, yieldWs, "Yield Prediction");

            // Create detailed analysis worksheet
            const tableData = [
                ['Date', 'Crop', 'Growth Stage', 'Health Status'],
                ...$('#reportTableBody tr').map(function() {
                    return [
                        $(this).find('td:eq(0)').text(),
                        $(this).find('td:eq(1)').text(),
                        $(this).find('td:eq(2)').text(),
                        $(this).find('td:eq(3)').text()
                    ];
                }).get()
            ];
            const tableWs = XLSX.utils.aoa_to_sheet(tableData);
            XLSX.utils.book_append_sheet(wb, tableWs, "Detailed Analysis");

            // Save the file
            XLSX.writeFile(wb, "progress_report.xlsx");
        }
    </script>
</body>
</html> 