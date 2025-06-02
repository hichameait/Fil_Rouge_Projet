<?php
$pageTitle = "Analytics - DentalCare";
include '../includes/header.php';
?>

<div class="flex min-h-screen bg-gray-100">
    <?php include 'includes/sidebar.php'; ?>
    <div class="flex-1">
        <div class="container p-6">
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <h1 class="text-3xl font-bold tracking-tight">Analytics</h1>
                    <div class="inline-flex rounded-md shadow-sm">
                        <button type="button" class="py-2 px-4 text-sm font-medium text-gray-700 bg-white rounded-l-md border border-gray-300 hover:bg-gray-50 focus:z-10 focus:ring-2 focus:ring-blue-500 focus:text-blue-700">
                            Week
                        </button>
                        <button type="button" class="py-2 px-4 text-sm font-medium text-blue-700 bg-blue-50 border-t border-b border-r border-gray-300 hover:bg-blue-100 focus:z-10 focus:ring-2 focus:ring-blue-500">
                            Month
                        </button>
                        <button type="button" class="py-2 px-4 text-sm font-medium text-gray-700 bg-white rounded-r-md border-t border-b border-r border-gray-300 hover:bg-gray-50 focus:z-10 focus:ring-2 focus:ring-blue-500 focus:text-blue-700">
                            Year
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="p-6 border-b">
                            <h2 class="text-lg font-semibold">Monthly Revenue</h2>
                            <p class="text-sm text-gray-500">Revenue trends over the selected period</p>
                        </div>
                        <div class="p-6">
                            <div class="h-80">
                                <!-- Revenue Chart (would be implemented with JavaScript in a real app) -->
                                <div class="flex items-end h-64 space-x-2">
                                    <div class="relative flex flex-col items-center">
                                        <div class="h-40 w-10 bg-blue-500 rounded-t-md"></div>
                                        <span class="text-xs mt-1 text-gray-500">Jan</span>
                                        <span class="absolute bottom-full mb-1 text-xs font-medium">$4000</span>
                                    </div>
                                    <div class="relative flex flex-col items-center">
                                        <div class="h-44 w-10 bg-blue-500 rounded-t-md"></div>
                                        <span class="text-xs mt-1 text-gray-500">Feb</span>
                                        <span class="absolute bottom-full mb-1 text-xs font-medium">$4500</span>
                                    </div>
                                    <div class="relative flex flex-col items-center">
                                        <div class="h-48 w-10 bg-blue-500 rounded-t-md"></div>
                                        <span class="text-xs mt-1 text-gray-500">Mar</span>
                                        <span class="absolute bottom-full mb-1 text-xs font-medium">$5000</span>
                                    </div>
                                    <div class="relative flex flex-col items-center">
                                        <div class="h-56 w-10 bg-blue-500 rounded-t-md"></div>
                                        <span class="text-xs mt-1 text-gray-500">Apr</span>
                                        <span class="absolute bottom-full mb-1 text-xs font-medium">$6000</span>
                                    </div>
                                    <div class="relative flex flex-col items-center">
                                        <div class="h-52 w-10 bg-blue-500 rounded-t-md"></div>
                                        <span class="text-xs mt-1 text-gray-500">May</span>
                                        <span class="absolute bottom-full mb-1 text-xs font-medium">$5500</span>
                                    </div>
                                    <div class="relative flex flex-col items-center">
                                        <div class="h-60 w-10 bg-blue-500 rounded-t-md"></div>
                                        <span class="text-xs mt-1 text-gray-500">Jun</span>
                                        <span class="absolute bottom-full mb-1 text-xs font-medium">$6500</span>
                                    </div>
                                    <div class="relative flex flex-col items-center">
                                        <div class="h-64 w-10 bg-blue-500 rounded-t-md"></div>
                                        <span class="text-xs mt-1 text-gray-500">Jul</span>
                                        <span class="absolute bottom-full mb-1 text-xs font-medium">$7000</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white rounded-lg shadow overflow-hidden">
                            <div class="p-6 border-b">
                                <h2 class="text-lg font-semibold">Patients by Service</h2>
                                <p class="text-sm text-gray-500">Distribution of patients across different services</p>
                            </div>
                            <div class="p-6">
                                <div class="h-64 flex items-center justify-center">
                                    <!-- Pie Chart (would be implemented with JavaScript in a real app) -->
                                    <div class="relative w-48 h-48 rounded-full border-8 border-blue-500">
                                        <div class="absolute inset-0 border-8 border-blue-300" style="clip-path: polygon(50% 50%, 100% 0, 100% 100%, 0 100%, 0 0)"></div>
                                        <div class="absolute inset-0 border-8 border-blue-200" style="clip-path: polygon(50% 50%, 100% 0, 100% 50%, 50% 50%)"></div>
                                        <div class="absolute inset-0 border-8 border-blue-100" style="clip-path: polygon(50% 50%, 100% 50%, 100% 100%, 50% 100%)"></div>
                                    </div>
                                </div>
                                <div class="flex flex-wrap justify-center gap-4 mt-4">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 bg-blue-500 mr-2"></div>
                                        <span class="text-xs text-gray-600">Dental Cleaning (35%)</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 bg-blue-300 mr-2"></div>
                                        <span class="text-xs text-gray-600">Consultation (20%)</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 bg-blue-200 mr-2"></div>
                                        <span class="text-xs text-gray-600">Teeth Whitening (15%)</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 bg-blue-100 mr-2"></div>
                                        <span class="text-xs text-gray-600">Other (30%)</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow overflow-hidden">
                            <div class="p-6 border-b">
                                <h2 class="text-lg font-semibold">Satisfaction Rate</h2>
                                <p class="text-sm text-gray-500">Patient satisfaction ratings over time</p>
                            </div>
                            <div class="p-6">
                                <div class="h-64">
                                    <!-- Line Chart (would be implemented with JavaScript in a real app) -->
                                    <div class="relative h-56">
                                        <!-- Y-axis labels -->
                                        <div class="absolute inset-y-0 left-0 flex flex-col justify-between text-xs text-gray-500 py-2">
                                            <span>100%</span>
                                            <span>95%</span>
                                            <span>90%</span>
                                            <span>85%</span>
                                            <span>80%</span>
                                        </div>
                                        
                                        <!-- Chart area -->
                                        <div class="absolute inset-0 ml-8 border-b border-l border-gray-200">
                                            <!-- Line -->
                                            <svg class="absolute inset-0" viewBox="0 0 100 100" preserveAspectRatio="none">
                                                <path d="M0,30 L10,25 L20,20 L30,22 L40,20 L50,16 L60,14 L70,18 L80,10 L90,12 L100,8" stroke="rgba(59, 130, 246, 0.8)" stroke-width="2" fill="none" />
                                                <path d="M0,30 L10,25 L20,20 L30,22 L40,20 L50,16 L60,14 L70,18 L80,10 L90,12 L100,8" stroke="none" fill="rgba(59, 130, 246, 0.1)" />
                                            </svg>
                                            
                                            <!-- Data points -->
                                            <div class="absolute left-0 bottom-[70%] w-2 h-2 bg-blue-500 rounded-full"></div>
                                            <div class="absolute left-[10%] bottom-[75%] w-2 h-2 bg-blue-500 rounded-full"></div>
                                            <div class="absolute left-[20%] bottom-[80%] w-2 h-2 bg-blue-500 rounded-full"></div>
                                            <div class="absolute left-[30%] bottom-[78%] w-2 h-2 bg-blue-500 rounded-full"></div>
                                            <div class="absolute left-[40%] bottom-[80%] w-2 h-2 bg-blue-500 rounded-full"></div>
                                            <div class="absolute left-[50%] bottom-[84%] w-2 h-2 bg-blue-500 rounded-full"></div>
                                            <div class="absolute left-[60%] bottom-[86%] w-2 h-2 bg-blue-500 rounded-full"></div>
                                            <div class="absolute left-[70%] bottom-[82%] w-2 h-2 bg-blue-500 rounded-full"></div>
                                            <div class="absolute left-[80%] bottom-[90%] w-2 h-2 bg-blue-500 rounded-full"></div>
                                            <div class="absolute left-[90%] bottom-[88%] w-2 h-2 bg-blue-500 rounded-full"></div>
                                            <div class="absolute left-[100%] bottom-[92%] w-2 h-2 bg-blue-500 rounded-full"></div>
                                        </div>
                                        
                                        <!-- X-axis labels -->
                                        <div class="absolute bottom-0 left-8 right-0 flex justify-between text-xs text-gray-500 pt-2">
                                            <span>Jan</span>
                                            <span>Feb</span>
                                            <span>Mar</span>
                                            <span>Apr</span>
                                            <span>May</span>
                                            <span>Jun</span>
                                            <span>Jul</span>
                                            <span>Aug</span>
                                            <span>Sep</span>
                                            <span>Oct</span>
                                            <span>Nov</span>
                                            <span>Dec</span>
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
</div>

<?php include '../includes/footer.php'; ?>