<?php
session_start();
$is_logged_in = isset($_SESSION['customer_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OrderSlice | Premium Cake Reservations</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="antialiased">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 glass transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0 flex items-center">
                    <span class="font-serif text-3xl font-bold text-[#283618]">Order<span class="text-[#d4a373]">Slice</span></span>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#home" class="text-[#283618] hover:text-[#d4a373] font-medium transition-colors">Home</a>
                    <a href="#cakes" class="text-[#283618] hover:text-[#d4a373] font-medium transition-colors">Our Cakes</a>
                    
                    <?php if($is_logged_in): ?>
                        <a href="dashboard.php" class="bg-[#283618] text-white px-5 py-2 rounded-full font-medium hover:bg-[#1a2310] transition-colors shadow-md text-sm">My Dashboard</a>
                    <?php else: ?>
                        <a href="login.php" class="bg-[#d4a373] text-white px-5 py-2 rounded-full font-medium hover:bg-[#b5835a] transition-colors shadow-md text-sm">Login / Register</a>
                    <?php endif; ?>

                    <a href="admin/index.php" class="text-sm text-gray-400 hover:text-[#d4a373] transition-colors ml-4 border-l pl-4 border-gray-300">Admin</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden hero-gradient min-h-[90vh] flex items-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto">
                <h1 class="text-5xl md:text-7xl font-bold font-serif text-[#283618] mb-6 fade-in-up">
                    Artisan Cakes for <br/>
                    <span class="text-[#d4a373] italic">Special Moments</span>
                </h1>
                <p class="mt-4 text-xl text-gray-700 mb-10 fade-in-up delay-100 font-light">
                    Handcrafted with love, reserved just for you. Browse our premium collection and reserve your cake for pick-up or Cash on Delivery.
                </p>
                <div class="fade-in-up delay-200">
                    <a href="#cakes" class="inline-block bg-[#283618] text-white px-8 py-4 rounded-full font-medium text-lg hover:bg-[#1a2310] transition-colors shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                        Reserve a Cake
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Decorative elements -->
        <div class="absolute top-1/4 left-10 w-64 h-64 bg-[#d4a373] rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-1/3 right-10 w-72 h-72 bg-[#e9edc9] rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-2000"></div>
    </section>

    <!-- Cake Gallery Section -->
    <section id="cakes" class="py-24 bg-[#fefae0]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 fade-in-up">
                <h2 class="text-4xl font-serif font-bold text-[#283618] mb-4">Our Signature Collection</h2>
                <div class="w-24 h-1 bg-[#d4a373] mx-auto rounded"></div>
                <p class="mt-6 text-gray-600 max-w-2xl mx-auto">Choose from our freshly baked selections. Note that all reservations are strictly Cash on Delivery.</p>
            </div>

            <div id="cakeContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                <!-- Cakes will be injected here by JS -->
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[#283618] text-[#fefae0] py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h3 class="font-serif text-2xl mb-4 text-[#d4a373]">OrderSlice</h3>
            <p class="text-gray-400 mb-6">Sweetening your life, one slice at a time.</p>
            <div class="border-t border-gray-700 pt-8 mt-8 text-sm text-gray-500">
                &copy; 2026 OrderSlice Cake Reservations. All rights reserved.
            </div>
        </div>
    </footer>

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
                            <textarea name="delivery_address" required rows="2" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#d4a373] focus:border-[#d4a373] outline-none transition-all bg-gray-50 focus:bg-white" placeholder="Full address details"></textarea>
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
                            <p>Payment Method: <strong>Strictly Cash on Delivery (COD)</strong>. You will pay upon receiving the cake.</p>
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
                    <p class="text-gray-600 mb-8">Thank you for choosing OrderSlice. We will prepare your cake and you can pay via Cash on Delivery.</p>
                    <button onclick="closeModal()" class="bg-[#283618] text-white px-8 py-3 rounded-xl font-medium hover:bg-[#1a2310] transition-colors">
                        Done
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>
