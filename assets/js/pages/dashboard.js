let currentHouseholdPhase = 'all';
let currentTab = 'reports';
let currentStatus = 'all';
let currentPriority = 'all';
let currentSort = 'newest';
let currentServicesSubTab = 'offerings'; // Track which sub-tab in services

function loadTab(tab, subTab = null) {
    currentTab = tab;
    
    const buttons = ['reports', 'households', 'services', 'complaints', 'announcements'];
    buttons.forEach(btn => {
        const element = document.getElementById(`nav-${btn}`);
        if (element) {
            if (btn === tab) {
                element.classList.add('btn-active');
                element.classList.remove('btn-inactive');
            } else {
                element.classList.add('btn-inactive');
                element.classList.remove('btn-active');
            }
        }
    });
    
    const contentContainer = document.getElementById('tabContent');
    contentContainer.innerHTML = '<div class="loading-spinner">Loading...</div>';
    
    let url = `admin/${tab}/index.php`;
    
    // Handle services sub-tabs
    if (tab === 'services') {
        if (subTab === 'requests') {
            url = `admin/services/requests.php`;
            currentServicesSubTab = 'requests';
        } else if (subTab === 'offerings') {
            url = `admin/services/index.php`;
            currentServicesSubTab = 'offerings';
        } else {
            // Default to offerings, but check if we have a saved state
            url = currentServicesSubTab === 'requests' ? `admin/services/requests.php` : `admin/services/index.php`;
        }
    }
    
    fetch(url)
        .then(response => response.text())
        .then(html => {
            contentContainer.innerHTML = html;
            attachDeleteHandlers();
            attachFilterHandlers();
            attachRespondPageDropdown();
            attachServicesSubNavHandlers();
            attachRequestFilterHandlers();
        })
        .catch(() => {
            contentContainer.innerHTML = '<div class="error-alert">Failed to load content. Please refresh.</div>';
        });
}

function attachServicesSubNavHandlers() {
    // Handle clicks on service sub-navigation links
    const subNavLinks = document.querySelectorAll('.sub-nav-link');
    if (subNavLinks.length > 0) {
        subNavLinks.forEach(link => {
            link.removeEventListener('click', handleSubNavClick);
            link.addEventListener('click', handleSubNavClick);
        });
    }
}

function handleSubNavClick(e) {
    e.preventDefault();
    const href = this.getAttribute('href');
    
    // Update active class
    document.querySelectorAll('.sub-nav-link').forEach(link => {
        link.classList.remove('active');
    });
    this.classList.add('active');
    
    // Load the appropriate content
    const contentContainer = document.getElementById('tabContent');
    contentContainer.innerHTML = '<div class="loading-spinner">Loading...</div>';
    
    fetch(href)
        .then(response => response.text())
        .then(html => {
            contentContainer.innerHTML = html;
            attachDeleteHandlers();
            attachFilterHandlers();
            attachServicesSubNavHandlers();
            attachRequestFilterHandlers();
        })
        .catch(() => {
            contentContainer.innerHTML = '<div class="error-alert">Failed to load content. Please refresh.</div>';
        });
}

function attachRequestFilterHandlers() {
    // Status filter buttons for service requests
    const filterBtns = document.querySelectorAll('.status-filter-btn');
    const searchInput = document.getElementById('searchInput');
    
    if (filterBtns.length > 0) {
        filterBtns.forEach(btn => {
            btn.removeEventListener('click', handleRequestFilterClick);
            btn.addEventListener('click', handleRequestFilterClick);
        });
    }
    
    if (searchInput) {
        searchInput.removeEventListener('input', handleRequestSearch);
        searchInput.addEventListener('input', handleRequestSearch);
    }
}

let searchTimeout;
function handleRequestSearch(e) {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        const searchValue = e.target.value;
        const activeStatus = document.querySelector('.status-filter-btn.active')?.getAttribute('data-status') || 'all';
        loadFilteredRequests(activeStatus, searchValue);
    }, 500);
}

function handleRequestFilterClick(e) {
    const status = this.getAttribute('data-status');
    const searchValue = document.getElementById('searchInput')?.value || '';
    
    // Update active class
    document.querySelectorAll('.status-filter-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    this.classList.add('active');
    
    loadFilteredRequests(status, searchValue);
}

function loadFilteredRequests(status, search) {
    const url = `admin/services/ajax-requests.php?status=${status}&search=${encodeURIComponent(search)}`;
    const tbody = document.getElementById('requestsTableBody');
    
    if (tbody) {
        tbody.innerHTML = '<tr><td colspan="8" class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</td></tr>';
        
        fetch(url)
            .then(response => response.text())
            .then(html => {
                tbody.innerHTML = html;
            })
            .catch(() => {
                tbody.innerHTML = '<tr><td colspan="8" class="no-data text-center"><p>Failed to load data.</p></td></tr>';
            });
    }
}

function attachDeleteHandlers() {
    document.querySelectorAll('.delete-item-btn').forEach(btn => {
        btn.removeEventListener('click', handleDeleteClick);
        btn.addEventListener('click', handleDeleteClick);
    });
}

function handleDeleteClick(e) {
    e.preventDefault();
    const type = this.getAttribute('data-type');
    const id = this.getAttribute('data-id');
    const name = this.getAttribute('data-name');
    openDeleteModal(type, id, name);
}

function attachFilterHandlers() {
    attachDropdownHandler('complaintStatusBtn', 'complaintStatusMenu', handleStatusFilter);
    attachDropdownHandler('complaintPriorityBtn', 'complaintPriorityMenu', handlePriorityFilter);
    attachDropdownHandler('complaintSortBtn', 'complaintSortMenu', handleSortFilter);
    attachDropdownHandler('householdPhaseBtn', 'householdPhaseMenu', handleHouseholdPhaseClick);
    
    const complaintSearch = document.getElementById('complaintSearchInput');
    if (complaintSearch) {
        complaintSearch.removeEventListener('keyup', filterAndSortComplaints);
        complaintSearch.addEventListener('keyup', filterAndSortComplaints);
    }
    
    const householdSearch = document.getElementById('householdSearchInput');
    if (householdSearch) {
        householdSearch.removeEventListener('keyup', filterHouseholds);
        householdSearch.addEventListener('keyup', filterHouseholds);
    }
}

function attachRespondPageDropdown() {
    const statusBtn = document.getElementById('statusDropdownBtn');
    const statusMenu = document.getElementById('statusDropdownMenu');
    const statusLabel = document.getElementById('statusDropdownLabel');
    const statusInput = document.getElementById('selectedStatus');
    
    if (statusBtn && statusMenu) {
        const newBtn = statusBtn.cloneNode(true);
        statusBtn.parentNode.replaceChild(newBtn, statusBtn);
        const newMenu = statusMenu.cloneNode(true);
        statusMenu.parentNode.replaceChild(newMenu, statusMenu);
        
        newBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            newMenu.classList.toggle('show');
        });
        
        newMenu.querySelectorAll('.dropdown-item').forEach(item => {
            item.addEventListener('click', function() {
                const value = this.getAttribute('data-value');
                statusLabel.textContent = this.textContent;
                if (statusInput) statusInput.value = value;
                newMenu.classList.remove('show');
            });
        });
    }
}

function attachDropdownHandler(btnId, menuId, handler) {
    const btn = document.getElementById(btnId);
    const menu = document.getElementById(menuId);
    
    if (btn && menu) {
        const newBtn = btn.cloneNode(true);
        btn.parentNode.replaceChild(newBtn, btn);
        const newMenu = menu.cloneNode(true);
        menu.parentNode.replaceChild(newMenu, menu);
        
        newBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            newMenu.classList.toggle('show');
        });
        
        newMenu.querySelectorAll('.dropdown-item').forEach(item => {
            item.removeEventListener('click', handler);
            item.addEventListener('click', handler);
        });
    }
}

function handleStatusFilter(e) {
    currentStatus = this.getAttribute('data-status');
    const label = document.getElementById('complaintStatusLabel');
    if (label) label.textContent = this.textContent;
    closeAllDropdowns();
    filterAndSortComplaints();
}

function handlePriorityFilter(e) {
    currentPriority = this.getAttribute('data-priority');
    const label = document.getElementById('complaintPriorityLabel');
    if (label) label.textContent = this.textContent;
    closeAllDropdowns();
    filterAndSortComplaints();
}

function handleSortFilter(e) {
    currentSort = this.getAttribute('data-sort');
    const label = document.getElementById('complaintSortLabel');
    if (label) label.textContent = this.textContent;
    closeAllDropdowns();
    filterAndSortComplaints();
}

function handleHouseholdPhaseClick(e) {
    const phase = this.getAttribute('data-phase');
    if (phase) {
        currentHouseholdPhase = phase;
        const label = document.getElementById('householdPhaseLabel');
        if (label) label.textContent = phase === 'all' ? 'All Phases' : phase;
        closeAllDropdowns();
        filterHouseholds();
    }
}

function closeAllDropdowns() {
    document.querySelectorAll('.dropdown-menu').forEach(menu => {
        menu.classList.remove('show');
    });
}

function filterAndSortComplaints() {
    const tbody = document.getElementById('complaintTableBody');
    
    if (!tbody) return;
    
    let rows = Array.from(tbody.querySelectorAll('tr.complaint-row'));
    let noDataRow = tbody.querySelector('.no-data-row');
    
    if (rows.length === 0) {
        if (noDataRow) noDataRow.style.display = '';
        return;
    }
    
    const search = document.getElementById('complaintSearchInput')?.value.toLowerCase() || '';
    
    rows.forEach(row => {
        row.style.display = '';
    });
    
    const filteredRows = rows.filter(row => {
        const rowStatus = row.getAttribute('data-status');
        const rowPriority = row.getAttribute('data-priority');
        const rowSubject = row.getAttribute('data-subject') || '';
        const rowName = row.getAttribute('data-name') || '';
        
        let show = true;
        
        if (currentStatus !== 'all' && rowStatus !== currentStatus) show = false;
        if (currentPriority !== 'all' && rowPriority !== currentPriority) show = false;
        if (search && !rowSubject.includes(search) && !rowName.includes(search)) show = false;
        
        return show;
    });
    
    rows.forEach(row => {
        if (!filteredRows.includes(row)) {
            row.style.display = 'none';
        }
    });
    
    filteredRows.sort((a, b) => {
        const dateA = new Date(a.getAttribute('data-date'));
        const dateB = new Date(b.getAttribute('data-date'));
        return currentSort === 'newest' ? dateB - dateA : dateA - dateB;
    });
    
    filteredRows.forEach(row => {
        tbody.appendChild(row);
    });
    
    if (filteredRows.length === 0) {
        if (noDataRow) {
            noDataRow.style.display = '';
        } else {
            const emptyRow = document.createElement('tr');
            emptyRow.className = 'no-data-row';
            emptyRow.innerHTML = '<td colspan="7" class="no-data"><p>No complaints found.</p></td>';
            tbody.appendChild(emptyRow);
        }
    } else if (noDataRow) {
        noDataRow.style.display = 'none';
    }
}

function filterHouseholds() {
    const searchInput = document.getElementById('householdSearchInput');
    const searchValue = searchInput ? searchInput.value.toLowerCase() : '';
    const rows = document.querySelectorAll('#householdTable tbody tr');
    
    rows.forEach(row => {
        const rowPhase = row.getAttribute('data-phase') || '';
        let textMatch = false;
        
        if (searchValue) {
            const cells = row.querySelectorAll('td');
            for (let i = 0; i < cells.length - 1; i++) {
                if (cells[i] && cells[i].textContent.toLowerCase().includes(searchValue)) {
                    textMatch = true;
                    break;
                }
            }
        } else {
            textMatch = true;
        }
        
        const phaseMatch = (currentHouseholdPhase === 'all' || rowPhase === currentHouseholdPhase);
        row.style.display = (textMatch && phaseMatch) ? '' : 'none';
    });
}

document.addEventListener('click', function() {
    closeAllDropdowns();
});

function openDeleteModal(type, id, name) {
    const modalTitle = document.getElementById('deleteModalTitle');
    const itemName = document.getElementById('deleteItemName');
    const warningText = document.getElementById('deleteWarningText');
    const confirmLink = document.getElementById('deleteConfirmLink');
    
    if (type === 'household') {
        modalTitle.textContent = 'Delete Household';
        itemName.textContent = name;
        warningText.textContent = 'This will also delete all residents in this household.';
        confirmLink.href = `admin/households/destroy.php?id=${id}`;
    } else if (type === 'service') {
        modalTitle.textContent = 'Delete Service';
        itemName.textContent = name;
        warningText.textContent = 'This action cannot be undone.';
        confirmLink.href = `admin/services/destroy.php?id=${id}`;
    } else if (type === 'announcement') {
        modalTitle.textContent = 'Delete Announcement';
        itemName.textContent = name;
        warningText.textContent = 'This action cannot be undone.';
        confirmLink.href = `admin/announcements/destroy.php?id=${id}`;
    }
    
    const modal = document.getElementById('deleteModal');
    modal.classList.remove('hidden');
    setTimeout(() => modal.classList.add('active'), 10);
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.remove('active');
    setTimeout(() => modal.classList.add('hidden'), 300);
}

// Restore tab from URL hash
var savedHash = window.location.hash.substring(1);
var validTabs = ['reports', 'households', 'services', 'complaints', 'announcements'];
var initialTab = (savedHash && validTabs.includes(savedHash)) ? savedHash : 'reports';
loadTab(initialTab);