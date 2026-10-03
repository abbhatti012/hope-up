/**
 * Admin Global Search Functionality
 * Provides real-time search across all admin entities
 */

class AdminSearch {
    constructor() {
        this.searchInput = document.getElementById('searchId');
        this.searchResults = null;
        this.suggestionsContainer = null;
        this.searchTimeout = null;
        this.isSearching = false;
        
        this.init();
    }

    init() {
        if (!this.searchInput) return;

        // Create search results container
        this.createSearchResultsContainer();
        
        // Create suggestions container
        this.createSuggestionsContainer();
        
        // Bind events
        this.bindEvents();
        
        // Initialize search
        this.initializeSearch();
    }

    createSearchResultsContainer() {
        // Remove existing container if any
        const existing = document.getElementById('searchResultsContainer');
        if (existing) existing.remove();

        this.searchResults = document.createElement('div');
        this.searchResults.id = 'searchResultsContainer';
        this.searchResults.className = 'search-results-container position-absolute bg-white border rounded shadow-lg';
        this.searchResults.style.cssText = `
            top: 100%;
            left: 0;
            right: 0;
            z-index: 1050;
            max-height: 400px;
            overflow-y: auto;
            display: none;
        `;

        // Insert after search input
        this.searchInput.parentNode.appendChild(this.searchResults);
    }

    createSuggestionsContainer() {
        // Remove existing container if any
        const existing = document.getElementById('searchSuggestionsContainer');
        if (existing) existing.remove();

        this.suggestionsContainer = document.createElement('div');
        this.suggestionsContainer.id = 'searchSuggestionsContainer';
        this.suggestionsContainer.className = 'search-suggestions-container position-absolute bg-white border rounded shadow-lg';
        this.suggestionsContainer.style.cssText = `
            top: 100%;
            left: 0;
            right: 0;
            z-index: 1050;
            max-height: 300px;
            overflow-y: auto;
            display: none;
        `;

        // Insert after search input
        this.searchInput.parentNode.appendChild(this.suggestionsContainer);
    }

    bindEvents() {
        // Search input events
        this.searchInput.addEventListener('input', (e) => {
            this.handleSearchInput(e.target.value);
        });

        this.searchInput.addEventListener('focus', () => {
            this.showSuggestions();
        });

        this.searchInput.addEventListener('blur', () => {
            // Delay hiding to allow clicking on results
            setTimeout(() => {
                this.hideSuggestions();
            }, 200);
        });

        // Keyboard navigation
        this.searchInput.addEventListener('keydown', (e) => {
            this.handleKeyboardNavigation(e);
        });

        // Click outside to close
        document.addEventListener('click', (e) => {
            if (!this.searchInput.contains(e.target) && 
                !this.searchResults.contains(e.target) && 
                !this.suggestionsContainer.contains(e.target)) {
                this.hideAll();
            }
        });
    }

    handleSearchInput(query) {
        clearTimeout(this.searchTimeout);
        
        if (query.length < 2) {
            this.hideResults();
            this.showSuggestions();
            return;
        }

        this.searchTimeout = setTimeout(() => {
            this.performSearch(query);
        }, 300);
    }

    async performSearch(query) {
        if (this.isSearching) return;
        
        this.isSearching = true;
        this.showLoading();

        try {
            const response = await fetch(`/admin/search/global?q=${encodeURIComponent(query)}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            });

            const data = await response.json();

            if (data.success) {
                this.displayResults(data.data, query);
            } else {
                this.showError(data.message || 'Search failed');
                this.hideLoading();
            }
        } catch (error) {
            console.error('Search error:', error);
            this.showError('Search failed. Please try again.');
            this.hideLoading();
        } finally {
            this.isSearching = false;
        }
    }

    async loadSuggestions() {
        try {
            const response = await fetch(`/admin/search/suggestions?q=${encodeURIComponent(this.searchInput.value)}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            });

            const data = await response.json();
            this.displaySuggestions(data.suggestions);
        } catch (error) {
            console.error('Suggestions error:', error);
        }
    }

    displayResults(results, query) {
        this.hideSuggestions();
        
        if (!results || (typeof results === 'object' && Object.keys(results).length === 0)) {
            this.showNoResults(query);
            this.hideLoading();
            return;
        }

        let html = `<div class="search-section-header d-flex align-items-center justify-content-between">
            <span>Search Results</span>
            <span class="search-loading" style="display: none;">
                <div class="spinner-border spinner-border-sm text-primary"></div>
            </span>
        </div>`;

        // Handle different result structures
        if (Array.isArray(results)) {
            html += this.renderResultsList(results);
        } else {
            // Handle categorized results
            html += this.renderCategorizedResults(results);
        }

        this.searchResults.innerHTML = html;
        this.showResults();
        this.hideLoading();
    }

    renderResultsList(results) {
        if (results.length === 0) {
            return '<div class="p-3 text-muted">No results found</div>';
        }

        let html = '<div class="search-results-list">';
        
        results.forEach(item => {
            html += this.renderResultItem(item);
        });

        html += '</div>';
        return html;
    }

    renderCategorizedResults(results) {
        let html = '';
        
        Object.keys(results).forEach(category => {
            const items = results[category];
            if (items && items.length > 0) {
                html += `<div class="search-category p-2 border-bottom">
                    <h6 class="mb-2 text-primary">${this.capitalizeFirst(category)}</h6>
                    <div class="search-results-list">`;
                
                items.forEach(item => {
                    html += this.renderResultItem(item);
                });
                
                html += '</div></div>';
            }
        });

        return html;
    }

    renderResultItem(item) {
        const icon = this.getTypeIcon(item.type);
        const statusClass = this.getStatusClass(item.status);
        
        return `
            <div class="search-result-item p-2 border-bottom" data-url="${item.url}">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="${icon} text-muted"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold">${item.title}</div>
                        <div class="text-muted small">${item.subtitle}</div>
                        ${item.status ? `<span class="badge ${statusClass}">${item.status}</span>` : ''}
                    </div>
                    <div class="text-muted small">${item.created_at}</div>
                </div>
            </div>
        `;
    }

    displaySuggestions(suggestions) {
        if (!suggestions || suggestions.length === 0) {
            this.suggestionsContainer.innerHTML = '<div class="p-3 text-muted">No suggestions available</div>';
            return;
        }

        let html = '<div class="p-2 border-bottom"><h6 class="mb-0">Quick Search</h6></div>';
        html += '<div class="suggestions-list">';

        suggestions.forEach(suggestion => {
            html += `
                <div class="suggestion-item p-2 border-bottom" data-text="${suggestion.text}">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="ri-search-line text-muted"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">${suggestion.text}</div>
                            <div class="text-muted small">${suggestion.category}</div>
                        </div>
                    </div>
                </div>
            `;
        });

        html += '</div>';
        this.suggestionsContainer.innerHTML = html;
    }

    getTypeIcon(type) {
        const icons = {
            'user': 'ri-user-line',
            'appointment': 'ri-calendar-line',
            'transaction': 'ri-money-dollar-circle-line',
            'review': 'ri-star-line',
            'content': 'ri-file-text-line',
            'speciality': 'ri-heart-pulse-line'
        };
        return icons[type] || 'ri-file-line';
    }

    getStatusClass(status) {
        const classes = {
            'pending': 'bg-warning',
            'completed': 'bg-success',
            'canceled': 'bg-danger',
            'approved': 'bg-success',
            'rejected': 'bg-danger',
            'active': 'bg-success',
            'inactive': 'bg-secondary'
        };
        return classes[status?.toLowerCase()] || 'bg-secondary';
    }

    capitalizeFirst(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    showLoading() {
        // Show spinner in the header if present
        const spinner = this.searchResults.querySelector('.search-loading');
        if (spinner) spinner.style.display = 'flex';
        this.showResults();
    }

    hideLoading() {
        const spinner = this.searchResults.querySelector('.search-loading');
        if (spinner) spinner.style.display = 'none';
    }

    showError(message) {
        this.searchResults.innerHTML = `
            <div class="p-3 text-danger">
                <i class="ri-error-warning-line me-2"></i>
                ${message}
            </div>
        `;
        this.showResults();
    }

    showNoResults(query) {
        this.searchResults.innerHTML = `
            <div class="p-3 text-center">
                <div class="text-muted mb-2">
                    <i class="ri-search-line fs-4"></i>
                </div>
                <div class="text-muted">No results found for "${query}"</div>
                <div class="small text-muted mt-1">Try different keywords</div>
            </div>
        `;
        this.showResults();
    }

    showResults() {
        this.searchResults.style.display = 'block';
        this.hideSuggestions();
    }

    hideResults() {
        this.searchResults.style.display = 'none';
    }

    showSuggestions() {
        if (this.searchInput.value.length < 2) {
            this.loadSuggestions();
        }
        this.suggestionsContainer.style.display = 'block';
    }

    hideSuggestions() {
        this.suggestionsContainer.style.display = 'none';
    }

    hideAll() {
        this.hideResults();
        this.hideSuggestions();
    }

    handleKeyboardNavigation(e) {
        const results = this.searchResults.querySelectorAll('.search-result-item');
        const suggestions = this.suggestionsContainer.querySelectorAll('.suggestion-item');
        
        let currentIndex = -1;
        let items = [];

        if (this.searchResults.style.display !== 'none') {
            items = results;
        } else if (this.suggestionsContainer.style.display !== 'none') {
            items = suggestions;
        }

        if (items.length === 0) return;

        // Find currently selected item
        items.forEach((item, index) => {
            if (item.classList.contains('selected')) {
                currentIndex = index;
            }
        });

        switch (e.key) {
            case 'ArrowDown':
                e.preventDefault();
                this.selectNextItem(items, currentIndex);
                break;
            case 'ArrowUp':
                e.preventDefault();
                this.selectPreviousItem(items, currentIndex);
                break;
            case 'Enter':
                e.preventDefault();
                this.selectCurrentItem(items, currentIndex);
                break;
            case 'Escape':
                this.hideAll();
                this.searchInput.blur();
                break;
        }
    }

    selectNextItem(items, currentIndex) {
        const nextIndex = currentIndex < items.length - 1 ? currentIndex + 1 : 0;
        this.selectItem(items, nextIndex);
    }

    selectPreviousItem(items, currentIndex) {
        const prevIndex = currentIndex > 0 ? currentIndex - 1 : items.length - 1;
        this.selectItem(items, prevIndex);
    }

    selectItem(items, index) {
        items.forEach(item => item.classList.remove('selected'));
        if (items[index]) {
            items[index].classList.add('selected');
            items[index].scrollIntoView({ block: 'nearest' });
        }
    }

    selectCurrentItem(items, currentIndex) {
        if (currentIndex >= 0 && items[currentIndex]) {
            const item = items[currentIndex];
            const url = item.dataset.url;
            const text = item.dataset.text;
            
            if (url && url !== '#') {
                window.location.href = url;
            } else if (text) {
                this.searchInput.value = text;
                this.searchInput.focus();
                this.performSearch(text);
            }
        }
    }

    initializeSearch() {
        // Add click handlers for search results
        this.searchResults.addEventListener('click', (e) => {
            const resultItem = e.target.closest('.search-result-item');
            if (resultItem) {
                const url = resultItem.dataset.url;
                if (url && url !== '#') {
                    window.location.href = url;
                }
            }
        });

        // Add click handlers for suggestions
        this.suggestionsContainer.addEventListener('click', (e) => {
            const suggestionItem = e.target.closest('.suggestion-item');
            if (suggestionItem) {
                const text = suggestionItem.dataset.text;
                if (text) {
                    this.searchInput.value = text;
                    this.performSearch(text);
                }
            }
        });
    }
}

// Initialize search when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    new AdminSearch();
}); 