// Global chart variable
var appointmentsChart = null;
let currentRange = '1m'; // Default range

// Function to show spinner
function showSpinner(chartContainer) {
  const spinner = chartContainer.querySelector('.chart-spinner');
  if (spinner) {
    spinner.style.display = 'flex';
  }
}

// Function to hide spinner
function hideSpinner(chartContainer) {
  const spinner = chartContainer.querySelector('.chart-spinner');
  if (spinner) {
    spinner.style.display = 'none';
  }
}

// Function to fetch appointment data
async function fetchAppointmentData(range = '1m') {
    try {
        // Show spinner
        const chartContainer = document.querySelector('#appointments').closest('.chart-container') || document.querySelector('#appointments').parentElement;
        showSpinner(chartContainer);
        
        // Show loading state
        if (appointmentsChart) {
            appointmentsChart.updateOptions({
                noData: {
                    text: 'Loading data...',
                    style: { color: '#666' }
                }
            }, false, false);
        }

        const response = await fetch(`/appointment-stats/${range}`);
        if (!response.ok) throw new Error('Network response was not ok');
        
        const data = await response.json();
        
        if (!data || !data.series || !Array.isArray(data.series)) {
            throw new Error('Invalid data format received');
        }

        // Update chart with new data
        if (appointmentsChart) {
            appointmentsChart.updateOptions({
                xaxis: { categories: data.categories || [] },
                noData: { text: 'No data available' }
            }, false, false);
            
            appointmentsChart.updateSeries(data.series);
        }
        
        // Hide spinner after data is loaded
        hideSpinner(chartContainer);
    } catch (error) {
        console.error('Error fetching appointment data:', error);
        if (appointmentsChart) {
            appointmentsChart.updateOptions({
                noData: {
                    text: 'Error loading data',
                    style: { color: '#FF4560' }
                }
            }, false, false);
        }
        
        // Hide spinner even if there's an error
        const chartContainer = document.querySelector('#appointments').closest('.chart-container') || document.querySelector('#appointments').parentElement;
        hideSpinner(chartContainer);
    }
}

// Initialize the chart
function initChart() {
    console.log('Initializing chart...');
    
    // Check if the chart container exists
    const chartElement = document.querySelector("#appointments");
    if (!chartElement) {
        console.error('Chart container #appointments not found');
        return;
    }
    
    // Chart options
    const options = {
        chart: {
            height: 400,
            type: "bar",
            toolbar: { show: false },
            animations: { enabled: true },
        },
        series: [],
        dataLabels: { enabled: false },
        stroke: {
            curve: "smooth",
            width: 3,
        },
        grid: {
            borderColor: "#d8dee6",
            strokeDashArray: 5,
            xaxis: { lines: { show: true } },
            yaxis: { lines: { show: false } },
            padding: { top: 0, right: 0, bottom: 10, left: 0 }
        },
        xaxis: {
            categories: [],
            labels: {
                style: {
                    colors: '#8c8c8c',
                    fontSize: '12px',
                    fontFamily: 'Poppins, sans-serif'
                }
            }
        },
        yaxis: {
            labels: { show: false }
        },
        colors: ["#2E93fA", "#FEB019", "#FF4560", "#00E396"],
        markers: {
            size: 0,
            opacity: 0.3,
            colors: ["#2E93fA", "#FEB019", "#FF4560", "#00E396"],
            strokeColor: "#ffffff",
            strokeWidth: 1,
            hover: { size: 7 }
        },
        legend: {
            position: 'bottom',
            horizontalAlign: 'center',
            itemMargin: { horizontal: 10, vertical: 5 },
            fontSize: '12px',
            fontFamily: 'Poppins, sans-serif',
            labels: { colors: '#8c8c8c' }
        },
        noData: {
            text: 'Loading...',
            style: {
                color: '#666',
                fontSize: '14px',
                fontFamily: 'Poppins, sans-serif'
            }
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + ' appointments';
                }
            }
        }
    };

    // Initialize chart
    appointmentsChart = new ApexCharts(chartElement, options);
    appointmentsChart.render();
    
    // Initial data load
    fetchAppointmentData(currentRange);
    
    // Set up event listeners for range buttons
    document.querySelectorAll('.appointments-filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            // Update active button
            const group = this.closest('.appointments-filter-group');
            if (group) {
                group.querySelectorAll('.appointments-filter-btn[data-range]').forEach(b => b.classList.remove('btn-primary'));
            }
            this.classList.add('btn-primary');
            
            // Update chart with new range
            currentRange = this.dataset.range;
            fetchAppointmentData(currentRange);
        });
    });
}

// Initialize the chart when DOM is loaded
document.addEventListener('DOMContentLoaded', initChart);
