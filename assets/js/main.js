document.addEventListener('DOMContentLoaded', () => {
    fetchCakes();

    const reservationForm = document.getElementById('reservationForm');
    if (reservationForm) {
        reservationForm.addEventListener('submit', submitReservation);
    }

    const modalCloseBtn = document.getElementById('closeModalBtn');
    if (modalCloseBtn) {
        modalCloseBtn.addEventListener('click', closeModal);
    }
    
    // Close modal on click outside
    const modal = document.getElementById('reservationModal');
    if(modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });
    }
});

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
                                <button onclick="openModal(${cake.id}, '${cake.name}')" class="w-full bg-[#283618] text-white py-3 rounded-xl font-medium hover:bg-[#1a2310] transition-colors shadow-lg hover:shadow-xl">
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
        })
        .catch(error => console.error('Error fetching cakes:', error));
}

function openModal(cakeId, cakeName) {
    fetch('api/customer_auth.php?action=check')
        .then(response => response.json())
        .then(data => {
            if (data.logged_in) {
                document.getElementById('cake_id').value = cakeId;
                document.getElementById('modalCakeName').textContent = cakeName;
                
                if (data.customer && data.customer.address) {
                    const addressField = document.querySelector('textarea[name="delivery_address"]');
                    if (addressField && !addressField.value) {
                        addressField.value = data.customer.address;
                    }
                }

                document.getElementById('reservationModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden'; 
                
                document.getElementById('formContainer').classList.remove('hidden');
                document.getElementById('successContainer').classList.add('hidden');
            } else {
                if (confirm('You need to be logged in to reserve a cake. Would you like to log in now?')) {
                    window.location.href = 'login.php';
                }
            }
        });
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
            // Show success message
            const formContainer = document.getElementById('formContainer');
            const successContainer = document.getElementById('successContainer');
            formContainer.classList.add('hidden');
            successContainer.classList.remove('hidden');
        } else {
            alert(data.message);
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error submitting reservation:', error);
        alert('An error occurred. Please try again.');
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}
