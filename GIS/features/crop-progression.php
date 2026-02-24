<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgriTrack Pro - Crop Progression</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .bg-nature {
            background: linear-gradient(135deg, #F3F4F6 0%, #E8F5E9 100%);
        }
        .dark .bg-nature {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
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
                        <a href="../dashboard.php" class="border-nature text-gray-900 dark:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Dashboard
                        </a>
                        <a href="crop-progression.php" class="border-nature text-nature inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Crop Progression
                        </a>
                        <a href="gis-integration.php" class="border-transparent text-gray-500 dark:text-gray-300 hover:border-gray-300 hover:text-gray-700 dark:hover:text-gray-200 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            GIS Integration
                        </a>
                        <a href="progress-reports.php" class="border-transparent text-gray-500 dark:text-gray-300 hover:border-gray-300 hover:text-gray-700 dark:hover:text-gray-200 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
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
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Crop Progression Tracking</h1>
                
                <!-- Current Crops -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Your Active Crops</h2>
                    <div id="activeCrops" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Crops will be loaded here dynamically -->
                    </div>
                </div>

                <!-- Growth Stages -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Growth Stages</h2>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-nature rounded-full mr-2"></div>
                                <span class="text-sm text-gray-700 dark:text-gray-300">Planting</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-nature rounded-full mr-2"></div>
                                <span class="text-sm text-gray-700 dark:text-gray-300">Germination</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-nature rounded-full mr-2"></div>
                                <span class="text-sm text-gray-700 dark:text-gray-300">Vegetative Growth</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-nature rounded-full mr-2"></div>
                                <span class="text-sm text-gray-700 dark:text-gray-300">Flowering</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-nature rounded-full mr-2"></div>
                                <span class="text-sm text-gray-700 dark:text-gray-300">Harvesting</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Progress Chart -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Growth Progress</h2>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <canvas id="growthChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Load active crops
            $.ajax({
                url: '../api/dashboard.php',
                method: 'GET',
                data: { user_id: <?php echo $_SESSION['user_id']; ?> },
                success: function(response) {
                    const crops = response.crops;
                    let html = '';
                    
                    crops.forEach(crop => {
                        html += `
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">${crop.crop_type}</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Planted on: ${crop.planting_date}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Area: ${crop.area} hectares</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Current Stage: ${crop.current_stage}</p>
                                </div>
                                <div class="mt-4">
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-gray-600 dark:text-gray-400">Progress</span>
                                        <span class="text-gray-900 dark:text-white">${crop.progress}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-600">
                                        <div class="bg-nature h-2.5 rounded-full" style="width: ${crop.progress}%"></div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    
                    $('#activeCrops').html(html);
                }
            });

            // Initialize chart
            const ctx = document.getElementById('growthChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5', 'Week 6', 'Week 7', 'Week 8'],
                    datasets: [{
                        label: 'Growth Progress',
                        data: [10, 25, 35, 45, 55, 65, 70, 65],
                        borderColor: '#2E7D32',
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
        });
    </script>
</body>
</html> 