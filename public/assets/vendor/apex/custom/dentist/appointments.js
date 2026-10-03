// Function to fetch appointment statistics
function fetchAppointmentStats(range = '1m') {
    fetch(`/appointment-stats/${range}`)
        .then(response => response.json())
        .then(data => {
            console.log('Appointment data received:', data);
            // Update chart with new data
            chart.updateOptions({
                xaxis: {
                    categories: data.categories
                },
                series: data.series
            });
        })
        .catch(error => {
            console.error('Error fetching appointment stats:', error);
        });
}

// Initialize chart with default data
var options = {
  chart: {
    height: 300,
    type: "bar",
    toolbar: {
      show: false,
    },
  },
  dataLabels: {
    enabled: false,
  },
  plotOptions: {
    bar: {
      horizontal: false,
      columnWidth: '30%',
    },
  },
  stroke: {
    show: true,
    width: 6,
    colors: ['transparent']
  },
  series: [
    {
      name: "Appointments",
      data: [],
    }
  ],
  grid: {
    borderColor: "#d8dee6",
    strokeDashArray: 5,
    xaxis: {
      lines: {
        show: true,
      },
    },
    yaxis: {
      lines: {
        show: false,
      },
    },
    padding: {
      top: 0,
      right: 0,
      bottom: 0,
      left: 0,
    },
  },
  xaxis: {
    categories: [],
  },
  yaxis: {
    labels: {
      show: false,
    },
  },
  colors: ["#116aef", "#ff3939", "#436ccf", "#dcad10", "#828382"],
  markers: {
    size: 0,
    opacity: 0.3,
    colors: ["#116aef", "#ff3939", "#436ccf", "#dcad10", "#828382"],
    strokeColor: "#ffffff",
    strokeWidth: 1,
    hover: {
      size: 7,
    },
  },
  tooltip: {
    y: {
      formatter: function (val) {
        return val;
      },
    },
  },
};

// Wait for DOM to be ready before initializing chart
document.addEventListener('DOMContentLoaded', function() {
    const appointmentsElement = document.querySelector("#appointments");
    
    if (appointmentsElement) {
        var chart = new ApexCharts(appointmentsElement, options);
        chart.render();
        
        // Fetch initial data after chart is rendered
        fetchAppointmentStats();
        
        // Add event listeners for range buttons
        const rangeButtons = document.querySelectorAll('[data-range]');
        rangeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const range = this.getAttribute('data-range');
                fetchAppointmentStats(range);
            });
        });
    } else {
        console.error('Appointments chart container not found');
    }
});