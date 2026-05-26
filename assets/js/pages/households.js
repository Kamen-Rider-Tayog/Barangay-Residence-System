var members = [];
var deletedMembers = [];

document.addEventListener('DOMContentLoaded', function() {
    // Load existing members for edit page
    if (typeof existingMembersData !== 'undefined' && existingMembersData.length > 0) {
        for (var i = 0; i < existingMembersData.length; i++) {
            var m = existingMembersData[i];
            members.push({
                id: m.resident_id,
                first_name: m.first_name,
                last_name: m.last_name,
                suffix: m.suffix || '',
                age: m.age || '',
                contact: m.contact_no || '',
                is_voter: m.is_voter == 1,
                relationship: m.relationship_to_head || 'Other'
            });
        }
        renderTable();
    }
    
    // Setup event listeners
    var showBtn = document.getElementById('showMemberFormBtn');
    var saveBtn = document.getElementById('saveMemberBtn');
    var cancelBtn = document.getElementById('cancelMemberBtn');
    var form = document.getElementById('householdForm');
    
    if (showBtn) {
        showBtn.onclick = function() {
            document.getElementById('memberFormContainer').style.display = 'block';
            this.style.display = 'none';
        };
    }
    
    if (saveBtn) {
        saveBtn.onclick = addMember;
    }
    
    if (cancelBtn) {
        cancelBtn.onclick = cancelMemberForm;
    }
    
    if (form) {
        form.onsubmit = function() {
            document.getElementById('membersData').value = JSON.stringify(members);
            document.getElementById('deletedMembersData').value = JSON.stringify(deletedMembers);
        };
    }
    
    // Handle edit existing member buttons
    document.querySelectorAll('.edit-existing').forEach(function(btn) {
        btn.onclick = function() {
            var id = parseInt(this.getAttribute('data-id'));
            var firstName = this.getAttribute('data-first');
            var lastName = this.getAttribute('data-last');
            var suffix = this.getAttribute('data-suffix');
            var age = this.getAttribute('data-age');
            var contact = this.getAttribute('data-contact');
            var isVoter = this.getAttribute('data-voter') == '1';
            var relationship = this.getAttribute('data-relationship');
            
            // Remove from members array
            for (var i = 0; i < members.length; i++) {
                if (members[i].id === id) {
                    members.splice(i, 1);
                    break;
                }
            }
            
            // Fill form for editing
            document.getElementById('memberFirstName').value = firstName;
            document.getElementById('memberLastName').value = lastName;
            document.getElementById('memberSuffix').value = suffix;
            document.getElementById('memberAge').value = age;
            document.getElementById('memberContact').value = contact;
            document.getElementById('memberIsVoter').checked = isVoter;
            document.getElementById('memberRelationship').value = relationship;
            
            // Show form
            document.getElementById('memberFormContainer').style.display = 'block';
            document.getElementById('showMemberFormBtn').style.display = 'none';
            
            // Remove row from table
            this.closest('tr').remove();
            renderTable();
        };
    });
});

function renderTable() {
    var tbody = document.getElementById('membersTableBody');
    if (!tbody) return;
    
    if (members.length === 0) {
        tbody.innerHTML = '<tr id="noMembersRow"><td colspan="8" class="text-center">No household members added yet. Use "Add Member" to add.'
        return;
    }
    
    var html = '';
    for (var i = 0; i < members.length; i++) {
        var m = members[i];
        var voterText = m.is_voter ? 'Yes' : 'No';
        
        html += '<tr>';
        html += '<td>' + escapeHtml(m.first_name) + '</td>';
        html += '<td>' + escapeHtml(m.last_name) + '</td>';
        html += '<td>' + escapeHtml(m.suffix) + '</td>';
        html += '<td>' + m.age + '</td>';
        html += '<td>' + escapeHtml(m.contact) + '</td>';
        html += '<td>' + voterText + '</td>';
        html += '<td>' + escapeHtml(m.relationship) + '</td>';
        html += '<td><button type="button" class="action-icon" onclick="removeMember(' + i + ')"><i class="fas fa-trash"></i></button></td>';
        html += '</tr>';
    }
    tbody.innerHTML = html;
}

function addMember() {
    var firstName = document.getElementById('memberFirstName').value.trim();
    var lastName = document.getElementById('memberLastName').value.trim();
    
    if (!firstName || !lastName) {
        alert('Please enter first name and last name');
        return;
    }
    
    members.push({
        id: 0,
        first_name: firstName,
        last_name: lastName,
        suffix: document.getElementById('memberSuffix').value.trim(),
        age: document.getElementById('memberAge').value,
        contact: document.getElementById('memberContact').value.trim(),
        is_voter: document.getElementById('memberIsVoter').checked,
        relationship: document.getElementById('memberRelationship').value
    });
    
    // Clear form but keep it open
    document.getElementById('memberFirstName').value = '';
    document.getElementById('memberLastName').value = '';
    document.getElementById('memberSuffix').value = '';
    document.getElementById('memberAge').value = '';
    document.getElementById('memberContact').value = '';
    document.getElementById('memberIsVoter').checked = false;
    document.getElementById('memberRelationship').value = 'Child';
    
    renderTable();
}

function removeMember(index) {
    if (confirm('Delete this member?')) {
        var member = members[index];
        if (member.id > 0) {
            deletedMembers.push(member.id);
        }
        members.splice(index, 1);
        renderTable();
    }
}

function cancelMemberForm() {
    document.getElementById('memberFormContainer').style.display = 'none';
    document.getElementById('showMemberFormBtn').style.display = 'inline-block';
    
    document.getElementById('memberFirstName').value = '';
    document.getElementById('memberLastName').value = '';
    document.getElementById('memberSuffix').value = '';
    document.getElementById('memberAge').value = '';
    document.getElementById('memberContact').value = '';
    document.getElementById('memberIsVoter').checked = false;
    document.getElementById('memberRelationship').value = 'Child';
}

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}