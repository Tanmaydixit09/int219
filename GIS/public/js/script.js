// Crop data management
const cropData = {
    // Dummy crop growth periods (in days)
    growthPeriods: {
        wheat: 120,
        rice: 150,
        maize: 90,
        sugarcane: 365
    },
    
    // Dummy expected yields (tons per hectare)
    expectedYields: {
        wheat: 3.5,
        rice: 4.0,
        maize: 5.0,
        sugarcane: 80.0
    }
};

// Form submission handler
document.getElementById('cropForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get form values
    const villageName = document.getElementById('villageName').value;
    const cropType = document.getElementById('cropType').value;
    const plantingDate = new Date(document.getElementById('plantingDate').value);
    const area = parseFloat(document.getElementById('area').value);
    
    // Validate inputs
    if (!villageName || !cropType || !plantingDate || !area) {
        alert('Please fill in all fields');
        return;
    }
    
    // Calculate crop progression
    const today = new Date();
    const daysSincePlanting = Math.floor((today - plantingDate) / (1000 * 60 * 60 * 24));
    const totalGrowthPeriod = cropData.growthPeriods[cropType];
    const progressPercentage = Math.min(Math.max((daysSincePlanting / totalGrowthPeriod) * 100, 0), 100);
    
    // Update progress bar
    updateProgressBar(progressPercentage, daysSincePlanting, totalGrowthPeriod);
    
    // Calculate expected yield
    const expectedYield = area * cropData.expectedYields[cropType];
    
    // Update progress text
    const progressText = document.getElementById('progressText');
    progressText.innerHTML = `
        <div class="space-y-2">
            <p>Village: ${villageName}</p>
            <p>Crop Type: ${cropType}</p>
            <p>Days since planting: ${daysSincePlanting}</p>
            <p>Total growth period: ${totalGrowthPeriod} days</p>
            <p>Expected yield: ${expectedYield.toFixed(2)} tons</p>
        </div>
    `;
});

// Update progress bar
function updateProgressBar(percentage, daysSincePlanting, totalGrowthPeriod) {
    const progressBar = document.getElementById('progressBar');
    progressBar.style.width = `${percentage}%`;
    
    // Change color based on progress
    if (percentage < 33) {
        progressBar.className = 'h-4 bg-yellow-500 rounded-full';
    } else if (percentage < 66) {
        progressBar.className = 'h-4 bg-blue-500 rounded-full';
    } else {
        progressBar.className = 'h-4 bg-green-500 rounded-full';
    }
}

// Initialize form with today's date
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('plantingDate').value = today;
}); 