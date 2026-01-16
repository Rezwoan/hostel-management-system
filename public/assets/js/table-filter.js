/**
 * Client-Side Table Filtering
 * Fast filtering without database calls
 */

(function() {
    'use strict';

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        initTableSearch();
        initDropdownFilters();
    });

    /**
     * Live text search - filters table rows as you type
     */
    function initTableSearch() {
        const searchInputs = document.querySelectorAll('[data-table-search]');
        
        searchInputs.forEach(function(input) {
            const tableId = input.dataset.tableSearch;
            const table = document.getElementById(tableId);
            
            if (!table) return;
            
            let debounceTimer;
            
            input.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(function() {
                    filterTable(table, input.value);
                }, 150); // Small delay for performance
            });
        });
    }

    /**
     * Filter table rows by search text
     */
    function filterTable(table, query) {
        const tbody = table.querySelector('tbody');
        if (!tbody) return;
        
        const rows = tbody.querySelectorAll('tr');
        const searchText = query.toLowerCase().trim();
        let visibleCount = 0;
        
        rows.forEach(function(row) {
            // Skip empty state rows
            if (row.querySelector('.empty-state')) {
                row.style.display = searchText ? 'none' : '';
                return;
            }
            
            const text = row.textContent.toLowerCase();
            const isMatch = !searchText || text.includes(searchText);
            
            row.style.display = isMatch ? '' : 'none';
            if (isMatch) visibleCount++;
        });
        
        // Show "no results" message if needed
        updateEmptyState(tbody, visibleCount, searchText);
    }

    /**
     * Initialize dropdown filters for instant filtering
     */
    function initDropdownFilters() {
        const filterSelects = document.querySelectorAll('[data-filter-column]');
        
        filterSelects.forEach(function(select) {
            const tableId = select.dataset.filterTable;
            const column = parseInt(select.dataset.filterColumn);
            const table = document.getElementById(tableId);
            
            if (!table || isNaN(column)) return;
            
            select.addEventListener('change', function() {
                applyAllFilters(table);
            });
        });
    }

    /**
     * Apply all active filters to a table
     */
    function applyAllFilters(table) {
        const tableId = table.id;
        const filters = document.querySelectorAll('[data-filter-table="' + tableId + '"]');
        const searchInput = document.querySelector('[data-table-search="' + tableId + '"]');
        const tbody = table.querySelector('tbody');
        
        if (!tbody) return;
        
        const rows = tbody.querySelectorAll('tr');
        let visibleCount = 0;
        
        rows.forEach(function(row) {
            // Skip empty state rows
            if (row.querySelector('.empty-state')) {
                return;
            }
            
            let isVisible = true;
            
            // Check dropdown filters
            filters.forEach(function(select) {
                const column = parseInt(select.dataset.filterColumn);
                const filterValue = select.value.toLowerCase();
                
                if (filterValue && !isNaN(column)) {
                    const cell = row.cells[column];
                    if (cell) {
                        const cellText = cell.textContent.toLowerCase().trim();
                        // Check if cell contains the filter value or has matching badge
                        if (!cellText.includes(filterValue)) {
                            isVisible = false;
                        }
                    }
                }
            });
            
            // Check text search
            if (isVisible && searchInput && searchInput.value.trim()) {
                const searchText = searchInput.value.toLowerCase().trim();
                const rowText = row.textContent.toLowerCase();
                if (!rowText.includes(searchText)) {
                    isVisible = false;
                }
            }
            
            row.style.display = isVisible ? '' : 'none';
            if (isVisible) visibleCount++;
        });
        
        // Update empty state
        const searchText = searchInput ? searchInput.value.trim() : '';
        updateEmptyState(tbody, visibleCount, searchText || 'filter');
    }

    /**
     * Show/hide empty state message
     */
    function updateEmptyState(tbody, visibleCount, searchText) {
        let emptyRow = tbody.querySelector('.filter-empty-state');
        
        if (visibleCount === 0 && searchText) {
            if (!emptyRow) {
                const colCount = tbody.closest('table').querySelector('thead tr').cells.length;
                emptyRow = document.createElement('tr');
                emptyRow.className = 'filter-empty-state';
                emptyRow.innerHTML = '<td colspan="' + colCount + '" class="empty-state" style="text-align:center;padding:20px;color:#666;">No matching records found</td>';
                tbody.appendChild(emptyRow);
            }
            emptyRow.style.display = '';
        } else if (emptyRow) {
            emptyRow.style.display = 'none';
        }
    }

    // Export for manual use
    window.TableFilter = {
        filter: filterTable,
        applyAll: applyAllFilters
    };
})();
