<div id="appointmentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
        <div class="flex justify-between items-center border-b p-4">
            <h3 class="text-lg font-semibold">New Appointment</h3>
            <button id="closeModalBtn" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="appointmentForm" class="p-6">
            <!-- Add hidden dentist_id input -->
            <input type="hidden" name="dentist_id" value="<?= $_SESSION['user_id'] ?>">
            <div class="space-y-4">
                <div>
                    <label for="patient-select" class="block text-sm font-medium text-gray-700 mb-1">Patient</label>
                    <select id="patient-select" name="patient_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="">Select a patient</option>
                        <!-- JS will populate this dynamically -->
                    </select>
                </div>
                
                <div>
                    <label for="service-select" class="block text-sm font-medium text-gray-700 mb-1">Service</label>
                    <select id="service-select" name="service_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="">Select a service</option>
                        <!-- JS will populate this dynamically -->
                    </select>
                </div>
                
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <input type="date" id="date" name="date" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>
                
                <div>
                    <label for="time" class="block text-sm font-medium text-gray-700 mb-1">Time</label>
                    <input type="time" id="time" name="time" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>
                
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea id="notes" name="notes" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"></textarea>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" id="cancelAppointmentBtn" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                    Save Appointment
                </button>
            </div>
        </form>
    </div>
</div>
<script>
// Fetch and populate patients
fetch('/Fil_Rouge_Projet/dashboard/api/patients.php')
    .then(res => res.json())
    .then(data => {
        const select = document.getElementById('patient-select');
        if (Array.isArray(data)) {
            data.forEach(patient => {
                const opt = document.createElement('option');
                opt.value = patient.id;
                opt.textContent = patient.first_name + ' ' + patient.last_name;
                select.appendChild(opt);
            });
        }
    });

// Fetch and populate services
fetch('/Fil_Rouge_Projet/dashboard/api/services.php')
    .then(res => res.json())
    .then(data => {
        const select = document.getElementById('service-select');
        if (Array.isArray(data)) {
            data.forEach(service => {
                const opt = document.createElement('option');
                opt.value = service.id;
                opt.textContent = service.name;
                select.appendChild(opt);
            });
        }
    });
</script>
