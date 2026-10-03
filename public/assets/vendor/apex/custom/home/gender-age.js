// Gender donut chart options
var genderOptions = {
  chart: {
    width: 240,
    type: "donut",
  },
  labels: ["Male", "Female", "Kids"],
  series: [0, 0, 0], // will be updated dynamically
  legend: {
    position: "bottom",
  },
  dataLabels: {
    enabled: false,
  },
  stroke: {
    width: 0,
  },
  colors: ["#116AEF", "#0ebb13", "#ff5a39", "#3e3e42", "#75C2F6"],
};
var genderChart = new ApexCharts(document.querySelector("#genderAge"), genderOptions);
genderChart.render();

// Ex-Militant donut chart options
var militantOptions = {
  chart: {
    width: 240,
    type: "donut",
  },
  labels: ["Ex-Militant", "Not Ex-Militant"],
  series: [0, 0], // will be updated dynamically
  legend: {
    position: "bottom",
  },
  dataLabels: {
    enabled: false,
  },
  stroke: {
    width: 0,
  },
  colors: ["#116AEF", "#ff5a39"],
};
var militantChart = new ApexCharts(document.querySelector("#isMilitantPatients"), militantOptions);
militantChart.render();

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

function fetchGenderAndMilitantStats(range = '1m') {
  // Show spinners for both charts
  const genderContainer = document.querySelector('#genderAge').closest('.chart-container') || document.querySelector('#genderAge').parentElement;
  const militantContainer = document.querySelector('#isMilitantPatients').closest('.chart-container') || document.querySelector('#isMilitantPatients').parentElement;
  
  showSpinner(genderContainer);
  showSpinner(militantContainer);
  
  fetch(`/patient-gender-militant-stats/${range}`)
    .then(res => res.json())
    .then(data => {
      // Update gender chart
      genderChart.updateOptions({ labels: data.gender.labels });
      genderChart.updateSeries(data.gender.series);
      // Update militant chart
      militantChart.updateOptions({ labels: data.militant.labels });
      militantChart.updateSeries(data.militant.series);
      
      // Hide spinners after data is loaded
      hideSpinner(genderContainer);
      hideSpinner(militantContainer);
    })
    .catch(error => {
      console.error('Error fetching stats:', error);
      // Hide spinners even if there's an error
      hideSpinner(genderContainer);
      hideSpinner(militantContainer);
    });
}

document.addEventListener('DOMContentLoaded', function() {
  fetchGenderAndMilitantStats('1m');
  document.querySelectorAll('.gender-age-filter-btn[data-range]').forEach(btn => {
    btn.addEventListener('click', function () {
      const group = this.closest('.gender-age-filter-group');
      if (group) {
        group.querySelectorAll('.gender-age-filter-btn[data-range]').forEach(b => b.classList.remove('btn-primary'));
      }
      this.classList.add('btn-primary');
      fetchGenderAndMilitantStats(this.getAttribute('data-range'));
    });
  });
});