<?php
session_start();
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reservations | OrderSlice</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-900" id="customerDashboard">

    <!-- Navbar -->
    <nav class="bg-white shadow-sm border-b border-gray-100 z-50 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0 flex items-center">
                    <a href="index.php" class="font-serif text-3xl font-bold text-[#283618]">Order<span class="text-[#d4a373]">Slice</span></a>
                </div>
                <div class="flex items-center space-x-6">
                    <a href="index.php" class="text-gray-600 hover:text-[#d4a373] font-medium transition-colors hidden md:block">Reserve More</a>
                    <div class="border-l border-gray-200 h-8 hidden md:block"></div>
                    <span class="text-[#283618] font-medium">Hi, <?php echo htmlspecialchars($_SESSION['customer_name']); ?>!</span>
                    <button id="logoutBtn" class="text-sm bg-red-50 text-red-600 hover:bg-red-100 px-4 py-2 rounded-lg font-medium transition-colors">Logout</button>
                </div>
            </div>
            <!-- Tabs -->
            <div class="flex space-x-8 mt-2 pb-4">
                <button id="tabMyReservations" class="pb-2 text-[#283618] font-bold border-b-2 border-[#d4a373] transition-colors">My Reservations</button>
                <button id="tabBrowseCakes" class="pb-2 text-gray-500 hover:text-[#283618] font-medium border-b-2 border-transparent hover:border-gray-300 transition-colors">Browse Cakes</button>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Reservations Section -->
        <div id="sectionReservations">
            <div class="mb-10 fade-in-up">
                <h1 class="text-4xl font-serif font-bold text-[#283618] mb-2">My Reservations</h1>
                <p class="text-gray-600">Track your order status here. Please prepare cash for upon delivery/pickup.</p>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden fade-in-up delay-100">
                <div class="p-8 hidden md:grid grid-cols-12 gap-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100 bg-gray-50/50">
                    <div class="col-span-1">Order #</div>
                    <div class="col-span-4">Cake Details</div>
                    <div class="col-span-3">Delivery Info</div>
                    <div class="col-span-2">Date & Time</div>
                    <div class="col-span-2 text-right">Status</div>
                </div>
                
                <div id="reservationsList" class="divide-y divide-gray-100">
                    <!-- Injected via JS -->
                </div>
            </div>
        </div>

        <!-- Browse Cakes Section -->
        <div id="sectionBrowse" class="hidden">
            <div class="mb-10 fade-in-up">
                <h1 class="text-4xl font-serif font-bold text-[#283618] mb-2">Browse Cakes</h1>
                <p class="text-gray-600">Discover and reserve from our premium collection.</p>
            </div>
            <div id="cakeContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                <!-- Cakes will be injected here by JS -->
            </div>
        </div>
    </main>

    <!-- Reservation Modal -->
    <div id="reservationModal" class="modal hidden fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div class="modal-content bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden relative">
            <button id="closeModalBtn" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 transition-colors z-10">
                &times;
            </button>
            
            <div class="p-8">
                <h3 class="text-3xl font-serif font-bold text-[#283618] mb-2">Reserve Cake</h3>
                <p class="text-gray-500 mb-6 border-b pb-4">You are reserving: <span id="modalCakeName" class="font-medium text-[#d4a373]"></span></p>
                
                <div id="formContainer">
                    <form id="reservationForm" class="space-y-5">
                        <input type="hidden" name="cake_id" id="cake_id">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Delivery/Pickup Address</label>
                            <textarea name="delivery_address" id="delivery_address" required rows="2" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#d4a373] focus:border-[#d4a373] outline-none transition-all bg-gray-50 focus:bg-white" placeholder="Full address details"></textarea>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                                <input type="date" name="reservation_date" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#d4a373] focus:border-[#d4a373] outline-none transition-all bg-gray-50 focus:bg-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Time</label>
                                <input type="time" name="reservation_time" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#d4a373] focus:border-[#d4a373] outline-none transition-all bg-gray-50 focus:bg-white">
                            </div>
                        </div>

                        <div class="bg-blue-50 text-blue-800 p-4 rounded-xl text-sm mt-4 border border-blue-100 flex items-start">
                            <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p>Payment Method: <strong>Strictly Cash on Delivery (COD)</strong>.</p>
                        </div>
                        
                        <button type="submit" id="submitBtn" class="w-full bg-[#d4a373] text-white py-4 rounded-xl font-bold text-lg hover:bg-[#b5835a] transition-all shadow-lg hover:shadow-xl mt-6">
                            Confirm Reservation
                        </button>
                    </form>
                </div>

                <div id="successContainer" class="hidden py-8 text-center">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h4 class="text-2xl font-serif font-bold text-gray-900 mb-2">Reservation Received!</h4>
                    <p class="text-gray-600 mb-8">Your reservation has been added to your dashboard.</p>
                    <button onclick="closeModal(); fetchReservations(); switchCustomerTab('reservations');" class="bg-[#283618] text-white px-8 py-3 rounded-xl font-medium hover:bg-[#1a2310] transition-colors">
                        View Reservations
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/customer.js"></script>
</body>
</html>
