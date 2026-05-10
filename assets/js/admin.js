let allCakes = [];

document.addEventListener('DOMContentLoaded', () => {
    // Check if we are on login page or dashboard
    if (document.getElementById('loginForm')) {
        document.getElementById('loginForm').addEventListener('submit', handleLogin);
    } else if (document.getElementById('dashboard')) {
        checkAuth();
        fetchReservations();
        fetchCakes();

        document.getElementById('logoutBtn').addEventListener('click', handleLogout);
        document.getElementById('addCakeForm').addEventListener('submit', handleAddCake);
        
        // Tab switching
        document.getElementById('tabReservations').addEventListener('click', (e) => switchTab('reservations', e.target));
        document.getElementById('tabCakes').addEventListener('click', (e) => switchTab('cakes', e.target));
        
        // Edit Form
        document.getElementById('editCakeForm').addEventListener('submit', handleEditCake);
    }
});

function checkAuth() {
    fetch('../api/admin_auth.php?action=check')
        .then(response => response.json())
        .then(data => {
            if (data.status !== 'success') {
                window.location.href = 'index.php';
            }
        });
}

function handleLogin(e) {
    e.preventDefault();
    const submitBtn = e.target.querySelector('button[type="submit"]');
    submitBtn.innerHTML = 'Logging in...';
    submitBtn.disabled = true;

    const formData = new FormData(e.target);
    fetch('../api/admin_auth.php?action=login', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            window.location.href = 'dashboard.php';
        } else {
            alert(data.message);
            submitBtn.innerHTML = 'Sign In';
            submitBtn.disabled = false;
        }
    })
    .catch(() => {
        alert('An error occurred');
        submitBtn.innerHTML = 'Sign In';
        submitBtn.disabled = false;
    });
}

function handleLogout() {
    fetch('../api/admin_auth.php?action=logout')
        .then(() => window.location.href = 'index.php');
}

function switchTab(tab, element) {
    // Update active tab styling
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('border-[#d4a373]', 'text-[#283618]');
        btn.classList.add('border-transparent', 'text-gray-500');
    });
    element.classList.remove('border-transparent', 'text-gray-500');
    element.classList.add('border-[#d4a373]', 'text-[#283618]');

    // Show/hide sections
    document.getElementById('reservationsSection').classList.add('hidden');
    document.getElementById('cakesSection').classList.add('hidden');
    document.getElementById(`${tab}Section`).classList.remove('hidden');
}

function fetchReservations() {
    fetch('../api/admin_reservations.php')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('reservationsTableBody');
            tbody.innerHTML = '';
            
            if (data.status === 'success' && data.data.length > 0) {
                data.data.forEach(res => {
                    const statusColors = {
                        'pending': 'bg-yellow-100 text-yellow-800',
                        'confirmed': 'bg-blue-100 text-blue-800',
                        'completed': 'bg-green-100 text-green-800',
                        'cancelled': 'bg-red-100 text-red-800'
                    };
                    const colorClass = statusColors[res.status];

                    const tr = `
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#${res.id}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="font-medium text-gray-900">${res.customer_name}</div>
                                <div>${res.customer_phone}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">${res.cake_name}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div>${res.reservation_date}</div>
                                <div>${res.reservation_time}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="truncate max-w-[150px]" title="${res.customer_address}">${res.customer_address}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${colorClass}">
                                    ${res.status.charAt(0).toUpperCase() + res.status.slice(1)}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <select onchange="updateStatus(${res.id}, this.value)" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-[#d4a373] focus:border-[#d4a373] sm:text-sm">
                                    <option value="pending" ${res.status === 'pending' ? 'selected' : ''}>Pending</option>
                                    <option value="confirmed" ${res.status === 'confirmed' ? 'selected' : ''}>Confirmed</option>
                                    <option value="completed" ${res.status === 'completed' ? 'selected' : ''}>Completed</option>
                                    <option value="cancelled" ${res.status === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                                </select>
                            </td>
                        </tr>
                    `;
                    tbody.insertAdjacentHTML('beforeend', tr);
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500 font-serif italic text-lg">No reservations found.</td></tr>';
            }
        });
}

function updateStatus(id, status) {
    const formData = new FormData();
    formData.append('id', id);
    formData.append('status', status);

    fetch('../api/admin_reservations.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            fetchReservations(); // Reload to get updated colors
        } else {
            alert(data.message);
        }
    });
}

function fetchCakes() {
    fetch('../api/admin_cakes.php')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('cakesList');
            container.innerHTML = '';
            
            if (data.status === 'success' && data.data.length > 0) {
                allCakes = data.data; // Store globally for editing
                data.data.forEach(cake => {
                    const el = `
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                            <div class="flex items-center space-x-4">
                                <img src="../${cake.image_url}" alt="${cake.name}" class="w-20 h-20 rounded-lg object-cover shadow-sm">
                                <div>
                                    <h4 class="text-lg font-medium text-gray-900 font-serif">${cake.name}</h4>
                                    <p class="text-sm text-gray-500">₱${parseFloat(cake.price).toFixed(2)}</p>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <button onclick="openEditModal(${cake.id})" class="px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">Edit</button>
                                <button onclick="deleteCake(${cake.id})" class="px-4 py-2 text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">Delete</button>
                            </div>
                        </div>
                    `;
                    container.insertAdjacentHTML('beforeend', el);
                });
            } else {
                allCakes = [];
                container.innerHTML = '<div class="text-sm text-gray-500 py-4 italic">No cakes added yet.</div>';
            }
        });
}

function handleAddCake(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    formData.append('action', 'add');

    const btn = document.getElementById('addCakeBtn');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = 'Adding...';

    fetch('../api/admin_cakes.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            e.target.reset();
            fetchCakes();
        } else {
            alert(data.message);
        }
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
}

function deleteCake(id) {
    if (confirm('Are you sure you want to delete this cake? Any existing reservations for it might be affected.')) {
        const formData = new FormData();
        formData.append('action', 'delete');
        formData.append('id', id);

        fetch('../api/admin_cakes.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                fetchCakes();
            } else {
                alert(data.message);
            }
        });
    }
}

function openEditModal(id) {
    const cake = allCakes.find(c => c.id == id);
    if (!cake) return;
    
    document.getElementById('edit_cake_id').value = cake.id;
    document.getElementById('edit_cake_name').value = cake.name;
    document.getElementById('edit_cake_desc').value = cake.description;
    document.getElementById('edit_cake_price').value = cake.price;
    
    document.getElementById('editCakeModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeEditModal() {
    document.getElementById('editCakeModal').classList.add('hidden');
    document.body.style.overflow = '';
    document.getElementById('editCakeForm').reset();
}

function handleEditCake(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    
    const btn = e.target.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = 'Saving...';

    fetch('../api/admin_cakes.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            closeEditModal();
            fetchCakes();
        } else {
            alert(data.message);
        }
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
}
