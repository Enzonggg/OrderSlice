<?php
session_start();
if (isset($_SESSION['customer_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account | OrderSlice</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-[#fefae0] flex items-center justify-center min-h-screen relative overflow-hidden py-12">

    <div class="absolute -top-32 -right-32 w-96 h-96 bg-[#d4a373] rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob"></div>
    <div class="absolute -bottom-32 -left-32 w-96 h-96 bg-[#e9edc9] rounded-full mix-blend-multiply filter blur-3xl opacity-60 animate-blob animation-delay-2000"></div>

    <div class="glass w-full max-w-lg p-10 rounded-[2rem] shadow-2xl fade-in-up border border-white relative z-10">
        <div class="text-center mb-8">
            <h1 class="font-serif text-4xl font-bold text-[#283618] mb-2 tracking-tight">Create Account</h1>
            <p class="text-gray-500 font-medium text-sm mt-2">Join OrderSlice to reserve your cakes.</p>
        </div>

        <form id="customerRegisterForm" class="space-y-5">
            <div>
                <label class="block text-sm font-semibold text-[#283618] mb-1 ml-1">Full Name</label>
                <input type="text" name="name" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#d4a373] outline-none transition-all bg-white/60 focus:bg-white text-gray-800" placeholder="John Doe">
            </div>

            <div>
                <label class="block text-sm font-semibold text-[#283618] mb-1 ml-1">Email Address</label>
                <input type="email" name="email" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#d4a373] outline-none transition-all bg-white/60 focus:bg-white text-gray-800" placeholder="you@example.com">
            </div>

            <div>
                <label class="block text-sm font-semibold text-[#283618] mb-1 ml-1">Phone Number</label>
                <input type="tel" name="phone" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#d4a373] outline-none transition-all bg-white/60 focus:bg-white text-gray-800" placeholder="09123456789">
            </div>

            <div>
                <label class="block text-sm font-semibold text-[#283618] mb-1 ml-1">Default Address</label>
                <textarea name="address" required rows="2" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#d4a373] outline-none transition-all bg-white/60 focus:bg-white text-gray-800" placeholder="Full address details"></textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-[#283618] mb-1 ml-1">Password</label>
                <input type="password" name="password" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#d4a373] outline-none transition-all bg-white/60 focus:bg-white text-gray-800" placeholder="••••••••">
            </div>

            <button type="submit" class="w-full bg-[#d4a373] text-white py-4 rounded-xl font-bold text-lg hover:bg-[#b5835a] transition-colors shadow-lg hover:shadow-xl mt-6">
                Create Account
            </button>
        </form>
        
        <div class="mt-8 text-center text-sm font-medium space-y-3">
            <p class="text-gray-600">Already have an account? <a href="login.php" class="text-[#283618] hover:underline font-bold">Sign In</a></p>
            <a href="index.php" class="text-gray-500 hover:text-[#d4a373] transition-colors flex items-center justify-center gap-2 pt-4 border-t border-gray-200/50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Website
            </a>
        </div>
    </div>

    <script src="assets/js/customer.js"></script>
</body>
</html>
