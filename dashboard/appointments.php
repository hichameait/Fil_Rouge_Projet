<?php
$pageTitle = "Appointments - DentalCare";
include '../includes/header.php';
?>

<div class="flex min-h-screen bg-gray-100">
    <?php include 'includes/sidebar.php'; ?>
    <div class="flex-1">
        <div class="container p-6">
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <h1 class="text-3xl font-bold tracking-tight">Appointments</h1>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        New Appointment
                    </button>
                </div>

                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="border-b border-gray-200">
                        <nav class="flex -mb-px">
                            <button class="text-blue-600 border-b-2 border-blue-500 py-4 px-6 font-medium text-sm">
                                Calendar
                            </button>
                            <button class="text-gray-500 hover:text-gray-700 py-4 px-6 font-medium text-sm">
                                List View
                            </button>
                        </nav>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-7 gap-6">
                            <div class="md:col-span-5">
                                <!-- Calendar -->
                                <div class="bg-white border rounded-lg shadow-sm">
                                    <div class="flex items-center justify-between p-4 border-b">
                                        <button class="p-1 rounded-full hover:bg-gray-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                            </svg>
                                        </button>
                                        <h2 class="text-lg font-semibold">May 2025</h2>
                                        <button class="p-1 rounded-full hover:bg-gray-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-7 gap-px bg-gray-200">
                                        <div class="bg-gray-50 p-2 text-center text-xs font-medium text-gray-500">Sun</div>
                                        <div class="bg-gray-50 p-2 text-center text-xs font-medium text-gray-500">Mon</div>
                                        <div class="bg-gray-50 p-2 text-center text-xs font-medium text-gray-500">Tue</div>
                                        <div class="bg-gray-50 p-2 text-center text-xs font-medium text-gray-500">Wed</div>
                                        <div class="bg-gray-50 p-2 text-center text-xs font-medium text-gray-500">Thu</div>
                                        <div class="bg-gray-50 p-2 text-center text-xs font-medium text-gray-500">Fri</div>
                                        <div class="bg-gray-50 p-2 text-center text-xs font-medium text-gray-500">Sat</div>
                                    </div>
                                    <div class="grid grid-cols-7 gap-px bg-gray-200">
                                        <div class="bg-white p-2 h-24 text-gray-400">28</div>
                                        <div class="bg-white p-2 h-24 text-gray-400">29</div>
                                        <div class="bg-white p-2 h-24 text-gray-400">30</div>
                                        <div class="bg-white p-2 h-24">
                                            <div class="font-medium">1</div>
                                            <div class="mt-1 overflow-y-auto max-h-16">
                                                <div class="px-1 py-0.5 text-xs rounded bg-blue-100 text-blue-800 mb-1">9:00 AM - Sarah J.</div>
                                                <div class="px-1 py-0.5 text-xs rounded bg-blue-100 text-blue-800">2:00 PM - David W.</div>
                                            </div>
                                        </div>
                                        <div class="bg-white p-2 h-24">
                                            <div class="font-medium">2</div>
                                            <div class="mt-1 overflow-y-auto max-h-16">
                                                <div class="px-1 py-0.5 text-xs rounded bg-blue-100 text-blue-800">11:30 AM - Emily D.</div>
                                            </div>
                                        </div>
                                        <div class="bg-white p-2 h-24">
                                            <div class="font-medium">3</div>
                                        </div>
                                        <div class="bg-white p-2 h-24">
                                            <div class="font-medium">4</div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-7 gap-px bg-gray-200">
                                        <div class="bg-white p-2 h-24">
                                            <div class="font-medium">5</div>
                                            <div class="mt-1 overflow-y-auto max-h-16">
                                                <div class="px-1 py-0.5 text-xs rounded bg-blue-100 text-blue-800 mb-1">9:00 AM - Sarah J.</div>
                                                <div class="px-1 py-0.5 text-xs rounded bg-blue-100 text-blue-800 mb-1">10:30 AM - Michael B.</div>
                                                <div class="px-1 py-0.5 text-xs rounded bg-blue-100 text-blue-800">1:00 PM - Emily D.</div>
                                            </div>
                                        </div>
                                        <div class="bg-white p-2 h-24">
                                            <div class="font-medium">6</div>
                                            <div class="mt-1 overflow-y-auto max-h-16">
                                                <div class="px-1 py-0.5 text-xs rounded bg-blue-100 text-blue-800">3:30 PM - Jennifer T.</div>
                                            </div>
                                        </div>
                                        <div class="bg-white p-2 h-24">
                                            <div class="font-medium">7</div>
                                        </div>
                                        <div class="bg-white p-2 h-24">
                                            <div class="font-medium">8</div>
                                            <div class="mt-1 overflow-y-auto max-h-16">
                                                <div class="px-1 py-0.5 text-xs rounded bg-blue-100 text-blue-800">10:00 AM - David W.</div>
                                            </div>
                                        </div>
                                        <div class="bg-white p-2 h-24">
                                            <div class="font-medium">9</div>
                                        </div>
                                        <div class="bg-white p-2 h-24">
                                            <div class="font-medium">10</div>
                                            <div class="mt-1 overflow-y-auto max-h-16">
                                                <div class="px-1 py-0.5 text-xs rounded bg-blue-100 text-blue-800">2:30 PM - Michael B.</div>
                                            </div>
                                        </div>
                                        <div class="bg-white p-2 h-24">
                                            <div class="font-medium">11</div>
                                        </div>
                                    </div>
                                    <!-- More weeks would go here -->
                                </div>
                            </div>
                            
                            <div class="md:col-span-2">
                                <div class="bg-white border rounded-lg shadow-sm p-4">
                                    <div class="space-y-2">
                                        <h3 class="font-medium">May 5, 2025</h3>
                                        <p class="text-sm text-gray-500">6 appointments scheduled</p>
                                    </div>

                                    <div class="mt-4 space-y-2">
                                        <div class="flex items-center justify-between rounded-md bg-blue-50 p-2">
                                            <div>
                                                <p class="font-medium">Sarah Johnson</p>
                                                <p class="text-xs text-gray-500">Dental Cleaning</p>
                                            </div>
                                            <p class="text-sm font-medium">9:00 AM</p>
                                        </div>

                                        <div class="flex items-center justify-between rounded-md bg-blue-50 p-2">
                                            <div>
                                                <p class="font-medium">Michael Brown</p>
                                                <p class="text-xs text-gray-500">Root Canal</p>
                                            </div>
                                            <p class="text-sm font-medium">10:30 AM</p>
                                        </div>

                                        <div class="flex items-center justify-between rounded-md bg-blue-50 p-2">
                                            <div>
                                                <p class="font-medium">Emily Davis</p>
                                                <p class="text-xs text-gray-500">Consultation</p>
                                            </div>
                                            <p class="text-sm font-medium">1:00 PM</p>
                                        </div>

                                        <button class="w-full mt-2 text-sm text-gray-600 border border-gray-300 rounded-md py-1 hover:bg-gray-50">
                                            View All
                                        </button>
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