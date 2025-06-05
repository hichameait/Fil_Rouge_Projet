<?php
$pageTitle = "Dashboard - DentalCare";
include '../includes/header.php';
?>

<div class="flex min-h-screen bg-gray-100">
    <?php include 'includes/sidebar.php'; ?>
    <div class="flex-1">
        <div class="container p-6">
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <h1 class="text-3xl font-bold tracking-tight">Dashboard</h1>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        New Appointment
                    </button>
                </div>

                <!-- Stats Cards -->
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex justify-between items-center pb-2">
                            <h2 class="text-sm font-medium text-gray-600">Total Patients</h2>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="text-2xl font-bold">1,248</div>
                        <p class="text-xs text-gray-500">12% increase from last month</p>
                    </div>
                    
                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex justify-between items-center pb-2">
                            <h2 class="text-sm font-medium text-gray-600">Appointments Today</h2>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="text-2xl font-bold">12</div>
                        <p class="text-xs text-gray-500">4 remaining for today</p>
                    </div>
                    
                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex justify-between items-center pb-2">
                            <h2 class="text-sm font-medium text-gray-600">Average Wait Time</h2>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="text-2xl font-bold">14 min</div>
                        <p class="text-xs text-gray-500">2 min less than last week</p>
                    </div>
                    
                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex justify-between items-center pb-2">
                            <h2 class="text-sm font-medium text-gray-600">Revenue This Month</h2>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="text-2xl font-bold">$12,234</div>
                        <p class="text-xs text-gray-500">8% increase from last month</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Upcoming Appointments -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-6 border-b">
                            <h2 class="text-lg font-semibold">Upcoming Appointments</h2>
                            <p class="text-sm text-gray-500">Your schedule for the next 24 hours</p>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="flex items-center justify-between border-b pb-4">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-700 font-medium">
                                            SJ
                                        </div>
                                        <div>
                                            <p class="font-medium">Sarah Johnson</p>
                                            <p class="text-sm text-gray-500">Dental Cleaning</p>
                                            <p class="text-sm text-gray-500">9:00 AM (1 hour)</p>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button class="text-gray-700 hover:text-gray-900 border border-gray-300 rounded-md px-3 py-1 text-sm">
                                            Reschedule
                                        </button>
                                        <button class="bg-blue-600 text-white rounded-md px-3 py-1 text-sm">
                                            View
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between border-b pb-4">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-700 font-medium">
                                            MB
                                        </div>
                                        <div>
                                            <p class="font-medium">Michael Brown</p>
                                            <p class="text-sm text-gray-500">Root Canal</p>
                                            <p class="text-sm text-gray-500">10:30 AM (1.5 hours)</p>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button class="text-gray-700 hover:text-gray-900 border border-gray-300 rounded-md px-3 py-1 text-sm">
                                            Reschedule
                                        </button>
                                        <button class="bg-blue-600 text-white rounded-md px-3 py-1 text-sm">
                                            View
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-700 font-medium">
                                            ED
                                        </div>
                                        <div>
                                            <p class="font-medium">Emily Davis</p>
                                            <p class="text-sm text-gray-500">Consultation</p>
                                            <p class="text-sm text-gray-500">1:00 PM (30 minutes)</p>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button class="text-gray-700 hover:text-gray-900 border border-gray-300 rounded-md px-3 py-1 text-sm">
                                            Reschedule
                                        </button>
                                        <button class="bg-blue-600 text-white rounded-md px-3 py-1 text-sm">
                                            View
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-6 border-b">
                            <h2 class="text-lg font-semibold">Recent Activity</h2>
                            <p class="text-sm text-gray-500">Latest updates and notifications</p>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="flex items-start space-x-4">
                                    <div class="bg-blue-100 p-2 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium">New patient registered</p>
                                        <p class="text-sm text-gray-500">Emily Johnson completed registration</p>
                                        <p class="text-xs text-gray-500">10 minutes ago</p>
                                    </div>
                                </div>

                                <div class="flex items-start space-x-4">
                                    <div class="bg-blue-100 p-2 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium">Appointment rescheduled</p>
                                        <p class="text-sm text-gray-500">Michael Brown moved to tomorrow at 2:00 PM</p>
                                        <p class="text-xs text-gray-500">1 hour ago</p>
                                    </div>
                                </div>

                                <div class="flex items-start space-x-4">
                                    <div class="bg-blue-100 p-2 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium">Payment received</p>
                                        <p class="text-sm text-gray-500">Sarah Wilson paid $150 for dental cleaning</p>
                                        <p class="text-xs text-gray-500">3 hours ago</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>