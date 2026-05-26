let currentHouseholdPhase = 'all';
let currentTab = 'reports';
let currentStatus = 'all';
let currentPriority = 'all';
let currentSort = 'newest';

function loadTab(tab) {
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
    
    fetch(`admin/${tab}/index.php`)
        .then(response => response.text())
        .then(html => {
            contentContainer.innerHTML = html;
            attachDeleteHandlers();
            attachFilterHandlers();
            attachRespondPageDropdown();
        })
        .catch(() => {
            contentContainer.innerHTML = '<div class="error-alert">Failed to load content. Please refresh.</div>';
        });
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