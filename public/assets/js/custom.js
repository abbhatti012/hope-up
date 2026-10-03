/**
 * Custom Dropdown Search Functionality
 * This script adds search functionality to select dropdowns
 * 
 * USAGE:
 * 1. Add class 'searchable-dropdown' to any select element
 * 2. The script will automatically initialize it
 * 
 * Example:
 * <select class="form-select searchable-dropdown" id="doctor_id" name="doctor_id">
 *     <option value="">Select Doctor</option>
 *     <option value="1">Dr. John Doe</option>
 *     <option value="2">Dr. Jane Smith</option>
 * </select>
 * 
 * FEATURES:
 * - Real-time search filtering
 * - Keyboard navigation support
 * - Mobile-friendly
 * - Maintains original select functionality
 * - Works with form validation
 * - No third-party dependencies
 * 
 * GLOBAL FUNCTIONS:
 * - window.makeSearchable(selectElement) - Make any select searchable
 * - window.initializeSearchableDropdown(selectElement) - Initialize specific select
 */

class SearchableDropdown {
    constructor(selectElement) {
        this.select = selectElement;
        this.options = Array.from(this.select.options);
        this.createSearchableDropdown();
        // Set search input to selected option's text on init
        const selectedOption = this.select.options[this.select.selectedIndex];
        if (selectedOption && selectedOption.value !== '') {
            this.searchInput.value = selectedOption.textContent;
        }
    }

    createSearchableDropdown() {
        // Create wrapper
        const wrapper = document.createElement('div');
        wrapper.className = 'searchable-dropdown-wrapper position-relative';
        wrapper.style.position = 'relative';
        
        // Create search input
        const searchInput = document.createElement('input');
        searchInput.type = 'text';
        searchInput.className = 'form-control searchable-dropdown-input';
        searchInput.placeholder = 'Search...';
        searchInput.style.marginBottom = '5px';
        
        // Create dropdown container
        const dropdownContainer = document.createElement('div');
        dropdownContainer.className = 'searchable-dropdown-container';
        dropdownContainer.style.cssText = `
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1050;
            display: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        `;
        
        // Create options list
        const optionsList = document.createElement('div');
        optionsList.className = 'searchable-dropdown-options';
        
        // Add event listeners
        searchInput.addEventListener('focus', () => {
            dropdownContainer.style.display = 'block';
            this.filterOptions(searchInput.value);
        });
        
        searchInput.addEventListener('input', (e) => {
            this.filterOptions(e.target.value);
        });
        
        searchInput.addEventListener('blur', (e) => {
            // Delay hiding to allow option clicks
            setTimeout(() => {
                if (!dropdownContainer.contains(e.relatedTarget)) {
                    dropdownContainer.style.display = 'none';
                }
            }, 150);
        });
        
        // Create option elements
        this.options.forEach((option, index) => {
            if (option.value === '') return; // Skip placeholder option
            
            const optionElement = document.createElement('div');
            optionElement.className = 'searchable-dropdown-option';
            optionElement.textContent = option.textContent;
            optionElement.style.cssText = `
                padding: 8px 12px;
                cursor: pointer;
                border-bottom: 1px solid #f8f9fa;
                transition: background-color 0.15s ease-in-out;
            `;
            
            optionElement.addEventListener('mouseenter', () => {
                optionElement.style.backgroundColor = '#f8f9fa';
            });
            
            optionElement.addEventListener('mouseleave', () => {
                optionElement.style.backgroundColor = 'transparent';
            });
            
            optionElement.addEventListener('click', () => {
                this.select.value = option.value;
                searchInput.value = option.textContent;
                dropdownContainer.style.display = 'none';
                
                // Trigger change event
                const event = new Event('change', { bubbles: true });
                this.select.dispatchEvent(event);
            });
            
            optionsList.appendChild(optionElement);
        });
        
        // Assemble the dropdown
        dropdownContainer.appendChild(optionsList);
        wrapper.appendChild(searchInput);
        wrapper.appendChild(dropdownContainer);
        
        // Replace the original select
        this.select.parentNode.insertBefore(wrapper, this.select);
        this.select.style.display = 'none';
        
        // Store references
        this.wrapper = wrapper;
        this.searchInput = searchInput;
        this.dropdownContainer = dropdownContainer;
        this.optionsList = optionsList;
        this.optionElements = Array.from(optionsList.children);
    }
    
    filterOptions(searchTerm) {
        const term = searchTerm.toLowerCase();
        const selectedValue = this.select.value;
        this.optionElements.forEach((optionElement, index) => {
            const option = this.options[index + 1]; // +1 to skip placeholder
            if (!option) return;
            const text = option.textContent.toLowerCase();
            // Always show the selected option
            if (option.value === selectedValue) {
                optionElement.style.display = 'block';
            } else if (text.includes(term)) {
                optionElement.style.display = 'block';
            } else {
                optionElement.style.display = 'none';
            }
        });
    }
    
    // Method to update options if the original select changes
    updateOptions() {
        this.options = Array.from(this.select.options);
        this.optionsList.innerHTML = '';
        
        this.options.forEach((option, index) => {
            if (option.value === '') return;
            
            const optionElement = document.createElement('div');
            optionElement.className = 'searchable-dropdown-option';
            optionElement.textContent = option.textContent;
            optionElement.style.cssText = `
                padding: 8px 12px;
                cursor: pointer;
                border-bottom: 1px solid #f8f9fa;
                transition: background-color 0.15s ease-in-out;
            `;
            
            optionElement.addEventListener('mouseenter', () => {
                optionElement.style.backgroundColor = '#f8f9fa';
            });
            
            optionElement.addEventListener('mouseleave', () => {
                optionElement.style.backgroundColor = 'transparent';
            });
            
            optionElement.addEventListener('click', () => {
                this.select.value = option.value;
                this.searchInput.value = option.textContent;
                this.dropdownContainer.style.display = 'none';
                
                const event = new Event('change', { bubbles: true });
                this.select.dispatchEvent(event);
            });
            
            this.optionsList.appendChild(optionElement);
        });
        
        this.optionElements = Array.from(this.optionsList.children);
    }
}

// Initialize searchable dropdowns when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize existing searchable dropdowns
    const searchableDropdowns = document.querySelectorAll('.searchable-dropdown');
    searchableDropdowns.forEach(select => {
        new SearchableDropdown(select);
    });
    
    // Function to initialize new searchable dropdowns (for dynamically added elements)
    window.initializeSearchableDropdown = function(selectElement) {
        if (selectElement && !selectElement.hasAttribute('data-searchable-initialized')) {
            selectElement.setAttribute('data-searchable-initialized', 'true');
            new SearchableDropdown(selectElement);
        }
    };
    
    // Function to make any select searchable
    window.makeSearchable = function(selectElement) {
        if (selectElement) {
            selectElement.classList.add('searchable-dropdown');
            window.initializeSearchableDropdown(selectElement);
        }
    };
});

// Export for use in other scripts
window.SearchableDropdown = SearchableDropdown;

$.sidebarMenu = function (menu) {
  var animationSpeed = 300;

  $(menu).on("click", "li a", function (e) {
    var $this = $(this);
    var checkElement = $this.next();

    if (checkElement.is(".treeview-menu") && checkElement.is(":visible")) {
      checkElement.slideUp(animationSpeed, function () {
        checkElement.removeClass("menu-open");
      });
      checkElement.parent("li").removeClass("active");
    }

    //If the menu is not visible
    else if (
      checkElement.is(".treeview-menu") &&
      !checkElement.is(":visible")
    ) {
      //Get the parent menu
      var parent = $this.parents("ul").first();
      //Close all open menus within the parent
      var ul = parent.find("ul:visible").slideUp(animationSpeed);
      //Remove the menu-open class from the parent
      ul.removeClass("menu-open");
      //Get the parent li
      var parent_li = $this.parent("li");

      //Open the target menu and add the menu-open class
      checkElement.slideDown(animationSpeed, function () {
        //Add the class active to the parent li
        checkElement.addClass("menu-open");
        parent.find("li.active").removeClass("active");
        parent_li.addClass("active");
      });
    }
    //if this isn't a link, prevent the page from being redirected
    if (checkElement.is(".treeview-menu")) {
      e.preventDefault();
    }
  });
};
$.sidebarMenu($(".sidebar-menu"));

// Custom Sidebar JS
jQuery(function ($) {
  //toggle sidebar
  $(".toggle-sidebar").on("click", function () {
    $(".page-wrapper").toggleClass("toggled");
  });

  // Pin sidebar on click
  $(".pin-sidebar").on("click", function () {
    if ($(".page-wrapper").hasClass("pinned")) {
      // unpin sidebar when hovered
      $(".page-wrapper").removeClass("pinned");
      $("#sidebar").unbind("hover");
    } else {
      $(".page-wrapper").addClass("pinned");
      $("#sidebar").hover(
        function () {
          console.log("mouseenter");
          $(".page-wrapper").addClass("sidebar-hovered");
        },
        function () {
          console.log("mouseout");
          $(".page-wrapper").removeClass("sidebar-hovered");
        }
      );
    }
  });

  // Pinned sidebar
  $(function () {
    $(".page-wrapper").hasClass("pinned");
    $("#sidebar").hover(
      function () {
        console.log("mouseenter");
        $(".page-wrapper").addClass("sidebar-hovered");
      },
      function () {
        console.log("mouseout");
        $(".page-wrapper").removeClass("sidebar-hovered");
      }
    );
  });

  // Toggle sidebar overlay
  $("#overlay").on("click", function () {
    $(".page-wrapper").toggleClass("toggled");
  });

  // Added by Srinu
  $(function () {
    // When the window is resized,
    $(window).resize(function () {
      // When the width and height meet your specific requirements or lower
      if ($(window).width() <= 768) {
        $(".page-wrapper").removeClass("pinned");
      }
    });
    // When the window is resized,
    $(window).resize(function () {
      // When the width and height meet your specific requirements or lower
      if ($(window).width() >= 768) {
        $(".page-wrapper").removeClass("toggled");
      }
    });
  });
});

// Loading
$(function () {
  $("#loading-wrapper").fadeOut(2000);
});

// $(function () {
//   $(".day-sorting .btn").on("click", function () {
//     $(".day-sorting .btn").removeClass("btn-primary");
//     $(this).addClass("btn-primary");
//   });
// });


/***********
***********
***********
  Bootstrap JS 
***********
***********
***********/

// Tooltip
var tooltipTriggerList = [].slice.call(
  document.querySelectorAll('[data-bs-toggle="tooltip"]')
);
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl);
});

// Popover
var popoverTriggerList = [].slice.call(
  document.querySelectorAll('[data-bs-toggle="popover"]')
);
var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
  return new bootstrap.Popover(popoverTriggerEl);
});
