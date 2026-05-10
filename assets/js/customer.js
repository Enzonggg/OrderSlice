document.addEventListener('DOMContentLoaded', () => {
    // Handle Login
    const loginForm = document.getElementById('customerLoginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const btn = e.target.querySelector('button[type="submit"]');
            btn.innerHTML = 'Signing In...';
            btn.disabled = true;

            const formData = new FormData(e.target);
            fetch('api/customer_auth.php?action=login', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    window.location.href = 'dashboard.php';
                } else {
                    alert(data.message);
                    btn.innerHTML = 'Sign In';
                    btn.disabled = false;
                }
            })
            .catch(() => {
                alert('Connection error');
                btn.innerHTML = 'Sign In';
                btn.disabled = false;
            });
        });
    }

    // Handle Registration
    const registerForm = document.getElementById('customerRegisterForm');
    if (registerForm) {
        registerForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const btn = e.target.querySelector('button[type="submit"]');
            btn.innerHTML = 'Creating Account...';
            btn.disabled = true;

            const formData = new FormData(e.target);
            fetch('api/customer_auth.php?action=register', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    window.location.href = 'dashboard.php';
                } else {
                    alert(data.message);
                    btn.innerHTML = 'Create Account';
                    btn.disabled = false;
                }
            })
            .catch(() => {
                alert('Connection error');
                btn.innerHTML = 'Create Account';
                btn.disabled = false;
            });
        });
    }

    // Handle Dashboard
    const dashboard = document.getElementById('customerDashboard');
    if (dashboard) {
        const logoutBtn = document.getElementById('logoutBtn');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', () => {
                fetch('api/customer_auth.php?action=logout')
                    .then(() => window.location.href = 'login.php');
            });
        }

        // Tab switching
        const tabReservations = document.getElementById('tabMyReservations');
        const tabBrowse = document.getElementById('tabBrowseCakes');
        if (tabReservations && tabBrowse) {
            tabReservations.addEventListener('click', () => switchCustomerTab('reservations'));
            tabBrowse.addEventListener('click', () => switchCustomerTab('browse'));
        }

        // Reservation Form
        const reservationForm = document.getElementById('reservationForm');
        if (reservationForm) {
            reservationForm.addEventListener('submit', submitReservation);
        }

        const modalCloseBtn = document.getElementById('closeModalBtn');
        if (modalCloseBtn) {
            modalCloseBtn.addEventListener('click', closeModal);
        }
        
        const modal = document.getElementById('reservationModal');
        if(modal) {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) closeModal();
            });
        }

        fetchReservations();
        fetchCakes();
    }
});

function switchCustomerTab(tab) {
    const tabRes = document.getElementById('tabMyReservations');
    const tabBrowse = document.getElementById('tabBrowseCakes');
    const secRes = document.getElementById('sectionReservations');
    const secBrowse = document.getElementById('sectionBrowse');

    if (tab === 'reservations') {
        tabRes.classList.add('border-[#d4a373]', 'text-[#283618]');
        tabRes.classList.remove('border-transparent', 'text-gray-500');
        tabBrowse.classList.remove('border-[#d4a373]', 'text-[#283618]');
        tabBrowse.classList.add('border-transparent', 'text-gray-500');

        secRes.classList.remove('hidden');
        secBrowse.classList.add('hidden');
        fetchReservations();
    } else {
        tabBrowse.classList.add('border-[#d4a373]', 'text-[#283618]');
        tabBrowse.classList.remove('border-transparent', 'text-gray-500');
        tabRes.classList.remove('border-[#d4a373]', 'text-[#283618]');
        tabRes.classList.add('border-transparent', 'text-gray-500');

        secBrowse.classList.remove('hidden');
        secRes.classList.add('hidden');
        fetchCakes();
    }
}

function fetchReservations() {
    fetch('api/customer_dashboard.php')
        .then(response => response.json())
        .then(data => {
            const list = document.getElementById('reservationsList');
            if (!list) return;

            list.innerHTML = '';

            if (data.status === 'success' && data.data.length > 0) {
                data.data.forEach(res => {
                    const statusColors = {
                        'pending': 'bg-yellow-100 text-yellow-800 border-yellow-200',
                        'confirmed': 'bg-blue-100 text-blue-800 border-blue-200',
                        'completed': 'bg-green-100 text-green-800 border-green-200',
                        'cancelled': 'bg-red-100 text-red-800 border-red-200'
                    };
                    const colorClass = statusColors[res.status] || 'bg-gray-100 text-gray-800';
                    const statusName = res.status.charAt(0).toUpperCase() + res.status.slice(1);

                    const item = `
                        <div class="p-6 md:p-8 hover:bg-gray-50 transition-colors">
                            <div class="flex flex-col md:grid md:grid-cols-12 gap-6 items-center">
                                <div class="col-span-1 w-full md:w-auto text-left">
                                    <span class="md:hidden text-xs font-bold text-gray-500 uppercase mr-2">Order:</span>
                                    <span class="font-medium text-gray-900">#${res.id}</span>
                                </div>
                                
                                <div class="col-span-4 w-full flex items-center space-x-4">
                                    <img src="${res.image_url}" alt="${res.cake_name}" class="w-16 h-16 rounded-xl object-cover shadow-sm">
                                    <div>
                                        <h4 class="font-serif text-lg font-bold text-[#283618]">${res.cake_name}</h4>
                                        <p class="text-[#d4a373] font-medium">₱${parseFloat(res.price).toFixed(2)}</p>
                                    </div>
                                </div>
                                
                                <div class="col-span-3 w-full">
                                    <span class="md:hidden text-xs font-bold text-gray-500 uppercase block mb-1">Delivery Info:</span>
                                    <p class="text-gray-600 text-sm line-clamp-2">${res.delivery_address}</p>
                                    <span class="mt-2 inline-block px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded font-medium border border-gray-200">COD Payment</span>
                                </div>
                                
                                <div class="col-span-2 w-full">
                                    <span class="md:hidden text-xs font-bold text-gray-500 uppercase block mb-1">Date & Time:</span>
                                    <div class="text-gray-900 font-medium">${res.reservation_date}</div>
                                    <div class="text-gray-500 text-sm">${res.reservation_time}</div>
                                </div>
                                
                                <div class="col-span-2 w-full md:text-right">
                                    <span class="md:hidden text-xs font-bold text-gray-500 uppercase block mb-2">Status:</span>
                                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full border ${colorClass}">
                                        ${statusName}
                                    </span>
                                </div>
                            </div>
                        </div>
                    `;
                    list.insertAdjacentHTML('beforeend', item);
                });
            } else {
                list.innerHTML = `
                    <div class="p-12 text-center">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        </div>
                        <h4 class="text-xl font-serif font-medium text-gray-900 mb-2">No reservations yet</h4>
                        <p class="text-gray-500 mb-6">Looks like you haven't reserved any cakes yet.</p>
                    </div>
                `;
            }
        });
}

function fetchCakes() {
    fetch('api/get_cakes.php')
        .then(response => response.json())
        .then(data => {
            const cakeContainer = document.getElementById('cakeContainer');
            if (!cakeContainer) return;

            if (data.status === 'success' && data.data.length > 0) {
                cakeContainer.innerHTML = '';
                data.data.forEach((cake, index) => {
                    const delayClass = `delay-${(index % 3 + 1) * 100}`;
                    const card = `
                        <div class="cake-card glass rounded-2xl overflow-hidden fade-in-up ${delayClass}">
                            <div class="relative h-64 overflow-hidden">
                                <img src="${cake.image_url}" alt="${cake.name}" class="w-full h-full object-cover">
                            </div>
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <h3 class="text-2xl font-serif font-bold text-[#283618]">${cake.name}</h3>
                                    <span class="text-xl font-bold text-[#d4a373]">₱${parseFloat(cake.price).toFixed(2)}</span>
                                </div>
                                <p class="text-gray-600 mb-6 line-clamp-2">${cake.description}</p>
                                <button onclick="openModal(${cake.id}, '${cake.name.replace(/'/g, "\\'")}')" class="w-full bg-[#283618] text-white py-3 rounded-xl font-medium hover:bg-[#1a2310] transition-colors shadow-lg hover:shadow-xl">
                                    Reserve Now
                                </button>
                            </div>
                        </div>
                    `;
                    cakeContainer.insertAdjacentHTML('beforeend', card);
                });
            } else {
                cakeContainer.innerHTML = '<div class="col-span-full text-center py-12 text-gray-500 font-serif italic text-xl">No cakes currently available. Please check back later!</div>';
            }
        });
}

function openModal(cakeId, cakeName) {
    document.getElementById('cake_id').value = cakeId;
    document.getElementById('modalCakeName').textContent = cakeName;
    
    // Check auth to get address
    fetch('api/customer_auth.php?action=check')
        .then(response => response.json())
        .then(data => {
            if (data.logged_in && data.customer && data.customer.address) {
                const addressField = document.querySelector('textarea[name="delivery_address"]');
                if (addressField && !addressField.value) {
                    addressField.value = data.customer.address;
                }
            }
        });

    document.getElementById('reservationModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    document.getElementById('formContainer').classList.remove('hidden');
    document.getElementById('successContainer').classList.add('hidden');
}

function closeModal() {
    document.getElementById('reservationModal').classList.add('hidden');
    document.body.style.overflow = '';
    document.getElementById('reservationForm').reset();
}

function submitReservation(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = 'Processing...';
    submitBtn.disabled = true;

    const formData = new FormData(e.target);

    fetch('api/submit_reservation.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            document.getElementById('formContainer').classList.add('hidden');
            document.getElementById('successContainer').classList.remove('hidden');
        } else {
            alert(data.message);
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    })
    .catch(error => {
        alert('An error occurred. Please try again.');
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}
