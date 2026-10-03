const treatmentOptions = {
  chart: {
    height: 300,
    type: "bar", // changed from 'line' to 'bar'
    toolbar: {
      show: false,
    },
  },
  dataLabels: {
    enabled: true, // enable data labels
    style: {
      fontSize: '12px',
      colors: ["#304758"]
    }
  },
  fill: {
    type: 'solid',
    opacity: [.1, 1, .5],
  },
  stroke: {
    curve: "smooth",
    width: [0, 4, 0],
  },
  series: [], // will be updated dynamically
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
    categories: [], // will be updated dynamically
  },
  yaxis: {
    labels: {
      show: false,
    },
  },
  colors: ["#116AEF", "#327FF2", "#5394F5", "#75AAF9", "#96BFFC", "#B7D4FF"],
  markers: {
    size: 0,
    opacity: 0.3,
    colors: ["#116AEF", "#327FF2", "#5394F5", "#75AAF9", "#96BFFC", "#B7D4FF"],
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
  noData: {
    text: 'Loading...',
    align: 'center',
    verticalAlign: 'middle',
    style: {
      color: '#666',
      fontSize: '14px',
      fontFamily: 'Inter, sans-serif'
    }
  }
};

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

let treatmentChart;
document.addEventListener('DOMContentLoaded', function () {
  const chartEl = document.querySelector("#treatment-types");
  if (!chartEl) {
    console.error('Chart container not found.');
    return;
  }

  treatmentChart = new ApexCharts(chartEl, treatmentOptions);
  treatmentChart.render();

  // Initial load with default range
  fetchTreatmentTypeStats('1m');

  // Listen for filter button clicks
  document.querySelectorAll('.treatment-filter-btn[data-range]').forEach(btn => {
    btn.addEventListener('click', function () {
      const group = this.closest('.treatment-filter-group');
      if (group) {
        group.querySelectorAll('.treatment-filter-btn[data-range]').forEach(b => b.classList.remove('btn-primary'));
      }
      this.classList.add('btn-primary');
      fetchTreatmentTypeStats(this.getAttribute('data-range'));
    });
  });
});

// Fetch and update chart
function fetchTreatmentTypeStats(range = '1m') {
  // Show spinner
  const chartContainer = document.querySelector('#treatment-types').closest('.chart-container') || document.querySelector('#treatment-types').parentElement;
  showSpinner(chartContainer);
  
  treatmentChart.updateOptions({
    noData: {
      text: 'Loading...',
    }
  });

  fetch(`/treatment-type-stats/${range}`)
    .then(res => {
      if (!res.ok) throw new Error(`HTTP error: ${res.status}`);
      return res.json();
    })
    .then(data => {
      if (!data.categories || !data.series) {
        throw new Error('Invalid response format');
      }

      treatmentChart.updateOptions({
        xaxis: {
          categories: data.categories
        },
        noData: {
          text: 'No data available',
        }
      });

      treatmentChart.updateSeries(data.series);
      
      // Hide spinner after data is loaded
      hideSpinner(chartContainer);
    })
    .catch(error => {
      console.error('Error loading treatment type stats:', error);
      treatmentChart.updateOptions({
        noData: {
          text: 'Error loading data',
          style: { color: '#ff0000' }
        }
      });
      
      // Hide spinner even if there's an error
      hideSpinner(chartContainer);
    });
}
