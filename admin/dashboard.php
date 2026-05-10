<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | OrderSlice Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-900" id="dashboard">

    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-[#283618] text-white flex flex-col shadow-xl z-20 hidden md:flex">
            <div class="h-20 flex items-center justify-center border-b border-white/10 mt-4 mb-4">
                <span class="font-serif text-3xl font-bold">Order<span class="text-[#d4a373]">Slice</span></span>
            </div>
            <div class="p-6 flex-1">
                <p class="text-xs uppercase tracking-widest text-gray-400 mb-6 font-semibold">Management</p>
                <nav class="space-y-3">
                    <button id="tabReservations" class="tab-btn w-full flex items-center px-4 py-3 rounded-lg bg-white/10 text-white font-medium transition-all border-l-4 border-[#d4a373] hover:bg-white/20">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        Reservations
                    </button>
                    <button id="tabCakes" class="tab-btn w-full flex items-center px-4 py-3 rounded-lg text-gray-300 font-medium transition-all border-l-4 border-transparent hover:bg-white/10 hover:text-white">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z"></path></svg>
                        Cakes Inventory
                    </button>
                </nav>
            </div>
            <div class="p-6 border-t border-white/10">
                <button id="logoutBtn" class="w-full flex items-center justify-center px-4 py-3 rounded-lg text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-colors font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout
                </button>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-50/50">
            <!-- Mobile Header -->
            <header class="h-20 bg-white shadow-sm flex items-center px-6 justify-between z-10 md:hidden">
                <span class="font-serif text-2xl font-bold text-[#283618]">Order<span class="text-[#d4a373]">Slice</span></span>
                <button onclick="document.getElementById('logoutBtn').click()" class="text-red-500 font-medium">Logout</button>
            </header>
            
            <div class="flex-1 overflow-auto p-4 md:p-10">
                
                <!-- Reservations Section -->
                <section id="reservationsSection" class="fade-in-up">
                    <div class="mb-10">
                        <h2 class="text-4xl font-serif font-bold text-[#283618]">Reservations</h2>
                        <p class="text-gray-500 mt-2 text-lg">Manage customer orders and update payment/delivery status.</p>
                    </div>

                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50/80">
                                    <tr>
                                        <th class="px-8 py-5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Order ID</th>
                                        <th class="px-8 py-5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Customer Info</th>
                                        <th class="px-8 py-5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Reserved Cake</th>
                                        <th class="px-8 py-5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Delivery Time</th>
                                        <th class="px-8 py-5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Address</th>
                                        <th class="px-8 py-5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-8 py-5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="reservationsTableBody" class="bg-white divide-y divide-gray-100">
                                    <!-- Dynamic rows -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <!-- Cakes Section -->
                <section id="cakesSection" class="hidden fade-in-up">
                    <div class="mb-10">
                        <h2 class="text-4xl font-serif font-bold text-[#283618]">Cakes Inventory</h2>
                        <p class="text-gray-500 mt-2 text-lg">Add or remove cakes available on the customer reservation page.</p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                        <div class="lg:col-span-1">
                            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 sticky top-8">
                                <h3 class="text-2xl font-serif font-bold text-[#283618] mb-6">Add New Cake</h3>
                                <form id="addCakeForm" class="space-y-5" enctype="multipart/form-data">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1 ml-1">Cake Name</label>
                                        <input type="text" name="name" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#d4a373] outline-none bg-gray-50 focus:bg-white transition-colors">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1 ml-1">Description</label>
                                        <textarea name="description" required rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#d4a373] outline-none bg-gray-50 focus:bg-white transition-colors"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1 ml-1">Price (₱)</label>
                                        <input type="number" step="0.01" name="price" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#d4a373] outline-none bg-gray-50 focus:bg-white transition-colors">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1 ml-1">Upload Image</label>
                                        <input type="file" name="image" accept="image/*" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#d4a373] outline-none bg-gray-50 focus:bg-white transition-colors">
                                    </div>
                                    <button type="submit" id="addCakeBtn" class="w-full bg-[#d4a373] text-white py-4 rounded-xl font-bold text-lg hover:bg-[#b5835a] transition-all shadow-md hover:shadow-lg mt-4">
                                        Publish Cake
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="lg:col-span-2">
                            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 min-h-[500px]">
                                <h3 class="text-2xl font-serif font-bold text-[#283618] mb-8 border-b pb-4">Available Selection</h3>
                                <div id="cakesList" class="space-y-4">
                                    <!-- Cakes will be injected here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

            </div>
        </main>
    </div>

    <!-- Edit Cake Modal -->
    <div id="editCakeModal" class="modal hidden fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div class="modal-content bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden relative p-8">
            <button onclick="closeEditModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 transition-colors">
                &times;
            </button>
            <h3 class="text-2xl font-serif font-bold text-[#283618] mb-6">Edit Cake</h3>
            <form id="editCakeForm" class="space-y-4" enctype="multipart/form-data">
                <input type="hidden" name="id" id="edit_cake_id">
                <input type="hidden" name="action" value="edit">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 ml-1">Cake Name</label>
                    <input type="text" name="name" id="edit_cake_name" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#d4a373] outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 ml-1">Description</label>
                    <textarea name="description" id="edit_cake_desc" required rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#d4a373] outline-none"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 ml-1">Price (₱)</label>
                    <input type="number" step="0.01" name="price" id="edit_cake_price" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#d4a373] outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 ml-1">Update Image (Optional)</label>
                    <input type="file" name="image" accept="image/*" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#d4a373] outline-none">
                </div>
                <button type="submit" class="w-full bg-[#d4a373] text-white py-4 rounded-xl font-bold mt-4 hover:bg-[#b5835a] transition-all">Save Changes</button>
            </form>
        </div>
    </div>

    <script src="../assets/js/admin.js"></script>
</body>
</html>
