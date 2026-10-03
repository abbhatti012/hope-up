const patientsOptions = {
  chart: {
    height: 300,
    type: "line",
    toolbar: { show: false },
  },
  dataLabels: {
    enabled: true,
    style: {
      fontSize: '12px',
      colors: ["#304758"]
    }
  },
  series: [], // will be updated dynamically
  xaxis: { categories: [] },
  yaxis: { labels: { show: false } },
  colors: ["#116aef", "#b9c3ca"],
  grid: {
    borderColor: "#d8dee6",
    strokeDashArray: 5,
    xaxis: { lines: { show: true } },
    yaxis: { lines: { show: false } },
    padding: { top: 0, right: 0, bottom: 0, left: 0 },
  },
  markers: {
    size: 0,
    opacity: 0.3,
    colors: ["#116aef", "#b9c3ca"],
    strokeColor: "#ffffff",
    strokeWidth: 1,
    hover: { size: 7 },
  },
  tooltip: {
    y: {
      formatter: function (val) { return val; },
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

let patientsChart;
document.addEventListener('DOMContentLoaded', function () {
  const el = document.querySelector("#patients");
  if (!el) return;
  patientsChart = new ApexCharts(el, patientsOptions);
  patientsChart.render();
  fetchPatientsStats('1m');

  document.querySelectorAll('.customers-filter-btn[data-range]').forEach(btn => {
    btn.addEventListener('click', function () {
      const group = this.closest('.customers-filter-group');
      if (group) {
        group.querySelectorAll('.customers-filter-btn[data-range]').forEach(b => b.classList.remove('btn-primary'));
      }
      this.classList.add('btn-primary');
      fetchPatientsStats(this.getAttribute('data-range'));
    });
  });
});

function fetchPatientsStats(range = '1m') {
  // Show spinner
  const chartContainer = document.querySelector('#patients').closest('.chart-container') || document.querySelector('#patients').parentElement;
  showSpinner(chartContainer);
  
  patientsChart.updateOptions({
    noData: { text: 'Loading...' }
  });
  fetch(`/user-role-stats/${range}`)
    .then(res => res.json())
    .then(data => {
      if (!data.categories || !data.series) throw new Error('Invalid response format');
      patientsChart.updateOptions({ xaxis: { categories: data.categories }, noData: { text: 'No data available' } });
      patientsChart.updateSeries(data.series);
      
      // Hide spinner after data is loaded
      hideSpinner(chartContainer);
    })
    .catch(error => {
      console.error('Error loading patient stats:', error);
      patientsChart.updateOptions({
        noData: {
          text: 'Error loading data',
          style: { color: '#ff0000' }
        }
      });
      
      // Hide spinner even if there's an error
      hideSpinner(chartContainer);
    });
}