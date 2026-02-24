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
    <title>AgriTrack Pro - GIS Integration</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .bg-nature {
            background: linear-gradient(135deg, #F3F4F6 0%, #E8F5E9 100%);
        }
        .dark .bg-nature {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        }
        #map {
            height: 500px;
            width: 100%;
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
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
                        <a href="gis-integration.php" class="border-nature text-nature inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
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
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">GIS Integration</h1>
                
                <!-- Map Section -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Field Mapping</h2>
                    <div id="map" class="rounded-lg overflow-hidden"></div>
                </div>

                <!-- Data Layers -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Soil Analysis</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">pH Level</span>
                                <span class="text-gray-900 dark:text-white">6.8</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Nitrogen Content</span>
                                <span class="text-gray-900 dark:text-white">Medium</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Moisture Level</span>
                                <span class="text-gray-900 dark:text-white">Optimal</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Weather Patterns</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Temperature</span>
                                <span class="text-gray-900 dark:text-white">24°C</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Humidity</span>
                                <span class="text-gray-900 dark:text-white">65%</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Rainfall</span>
                                <span class="text-gray-900 dark:text-white">12mm</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Initialize map
            const map = L.map('map').setView([20.5937, 78.9629], 5); // Center on India

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Add sample field markers
            const fields = [
                { lat: 20.5937, lng: 78.9629, name: 'Field 1', crop: 'Wheat', area: '5 hectares' },
                { lat: 21.1458, lng: 79.0882, name: 'Field 2', crop: 'Rice', area: '3 hectares' }
            ];

            fields.forEach(field => {
                const marker = L.marker([field.lat, field.lng]).addTo(map);
                marker.bindPopup(`
                    <strong>${field.name}</strong><br>
                    Crop: ${field.crop}<br>
                    Area: ${field.area}
                `);
            });
        });
    </script>
</body>
</html> 