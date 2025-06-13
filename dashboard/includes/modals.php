<!-- New Appointment Modal -->
<div id="appointmentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden modal">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4 modal-backdrop">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Schedule New Appointment</h3>
            <button data-modal-close="appointmentModal" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="appointmentForm" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="patient-select" class="block text-sm font-medium text-gray-700 mb-2">Patient</label>
                    <select id="patient-select" name="patient_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select a patient</option>
                    </select>
                </div>
                
                <div>
                    <label for="service-select" class="block text-sm font-medium text-gray-700 mb-2">Service</label>
                    <select id="service-select" name="service_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select a service</option>
                    </select>
                </div>
                
                <div>
                    <label for="dentist-select" class="block text-sm font-medium text-gray-700 mb-2">Dentist</label>
                    <select id="dentist-select" name="dentist_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select a dentist</option>
                    </select>
                </div>
                
                <div>
                    <label for="appointment-date" class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                    <input type="date" id="appointment-date" name="appointment_date" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div>
                    <label for="appointment-time" class="block text-sm font-medium text-gray-700 mb-2">Time</label>
                    <input type="time" id="appointment-time" name="appointment_time" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div>
                    <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">Duration (minutes)</label>
                    <input type="number" id="duration" name="duration" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" readonly>
                </div>
            </div>
                        
            <div class="mt-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                <textarea id="notes" name="notes" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Additional notes..."></textarea>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                <button type="button" data-modal-close="appointmentModal" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                    Schedule Appointment
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Add Patient Modal -->
<div id="patientModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden modal">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4 modal-backdrop">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900" id="patientModalTitle">Add New Patient</h3>
            <button data-modal-close="patientModal" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="patientForm" class="p-6">
            <input type="hidden" id="patient-id" name="patient_id">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="first-name" class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                    <input type="text" id="first-name" name="first_name" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div>
                    <label for="last-name" class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                    <input type="text" id="last-name" name="last_name" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" id="email" name="email" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone *</label>
                    <input type="tel" id="phone" name="phone" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div>
                    <label for="date-of-birth" class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                    <input type="date" id="date-of-birth" name="date_of_birth" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                    <select id="gender" name="gender" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>
            </div>
            
            <div class="mt-6">
                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                <textarea id="address" name="address" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Full address..."></textarea>
            </div>
            
            <div class="mt-6">
                <label for="medical-history" class="block text-sm font-medium text-gray-700 mb-2">Medical History</label>
                <textarea id="medical-history" name="medical_history" rows="4" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Any relevant medical history, allergies, medications..."></textarea>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                <button type="button" data-modal-close="patientModal" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                    <span id="patientSubmitText">Add Patient</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Create Invoice Modal -->
<div id="invoiceModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden modal">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl mx-4 modal-backdrop max-h-screen overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900" id="invoiceModalTitle">Create New Invoice</h3>
            <button data-modal-close="invoiceModal" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="invoiceForm" class="p-6">
            <input type="hidden" id="invoice-id" name="invoice_id">
            
            <!-- Invoice Header -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="invoice-patient" class="block text-sm font-medium text-gray-700 mb-2">Patient *</label>
                    <select id="invoice-patient" name="patient_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select a patient</option>
                    </select>
                </div>
                
                <div>
                    <label for="invoice-number" class="block text-sm font-medium text-gray-700 mb-2">Invoice Number</label>
                    <input type="text" id="invoice-number" name="invoice_number" class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100" readonly>
                </div>
                
                <div>
                    <label for="due-date" class="block text-sm font-medium text-gray-700 mb-2">Due Date</label>
                    <input type="date" id="due-date" name="due_date" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label for="payment-method" class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                    <select id="payment-method" name="payment_method" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select payment method</option>
                        <option value="cash">Cash</option>
                        <option value="credit_card">Credit Card</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="insurance">Insurance</option>
                        <option value="other">Other</option>
                    </select>
                </div>
            </div>

            <!-- Invoice Items -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-medium text-gray-900">Invoice Items</h4>
                    <button type="button" id="addInvoiceItem" class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">
                        <i class="fas fa-plus mr-1"></i>Add Item
                    </button>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Service</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Unit Price</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody id="invoiceItemsTable">
                            <!-- Invoice items will be added here dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Invoice Totals -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="invoice-notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea id="invoice-notes" name="notes" rows="4" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Additional notes..."></textarea>
                </div>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-700">Subtotal:</span>
                        <span id="invoiceSubtotal" class="text-sm text-gray-900">$0.00</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <label for="tax-amount" class="text-sm font-medium text-gray-700">Tax:</label>
                        <input type="number" id="tax-amount" name="tax_amount" step="0.01" min="0" value="0" class="w-24 border border-gray-300 rounded px-2 py-1 text-sm text-right">
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <label for="discount-amount" class="text-sm font-medium text-gray-700">Discount:</label>
                        <input type="number" id="discount-amount" name="discount_amount" step="0.01" min="0" value="0" class="w-24 border border-gray-300 rounded px-2 py-1 text-sm text-right">
                    </div>
                    
                    <div class="flex justify-between border-t pt-3">
                        <span class="text-lg font-bold text-gray-900">Total:</span>
                        <span id="invoiceTotal" class="text-lg font-bold text-gray-900">$0.00</span>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <button type="button" data-modal-close="invoiceModal" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="button" id="saveInvoiceDraft" class="px-4 py-2 bg-gray-600 text-white rounded-md text-sm font-medium hover:bg-gray-700">
                    Save as Draft
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                    <span id="invoiceSubmitText">Create Invoice</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Upload Document Modal -->
<div id="documentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden modal">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4 modal-backdrop">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900" id="documentModalTitle">Upload Document</h3>
            <button data-modal-close="documentModal" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="documentForm" class="p-6" enctype="multipart/form-data">
            <input type="hidden" id="document-id" name="document_id">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="document-title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                    <input type="text" id="document-title" name="title" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div>
                    <label for="document-type" class="block text-sm font-medium text-gray-700 mb-2">Document Type *</label>
                    <select id="document-type" name="type" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select type</option>
                        <option value="xray">X-Ray</option>
                        <option value="treatment_plan">Treatment Plan</option>
                        <option value="consent_form">Consent Form</option>
                        <option value="report">Report</option>
                        <option value="prescription">Prescription</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                
                <div>
                    <label for="document-patient" class="block text-sm font-medium text-gray-700 mb-2">Patient</label>
                    <select id="document-patient" name="patient_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select a patient (optional)</option>
                    </select>
                </div>
                
                <div>
                    <label for="document-appointment" class="block text-sm font-medium text-gray-700 mb-2">Related Appointment</label>
                    <select id="document-appointment" name="appointment_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select appointment (optional)</option>
                    </select>
                </div>
            </div>
            
            <div class="mt-6">
                <label for="document-description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea id="document-description" name="description" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Document description..."></textarea>
            </div>
            
            <div class="mt-6">
                <label for="document-file" class="block text-sm font-medium text-gray-700 mb-2">File *</label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                    <input type="file" id="document-file" name="document_file" class="hidden" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif" required>
                    <div id="file-drop-zone" class="cursor-pointer">
                        <i class="fas fa-cloud-upload-alt text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-600 mb-2">Click to upload or drag and drop</p>
                        <p class="text-sm text-gray-500">PDF, DOC, DOCX, JPG, PNG, GIF (Max 10MB)</p>
                    </div>
                    <div id="file-preview" class="hidden">
                        <div class="flex items-center justify-center space-x-2">
                            <i class="fas fa-file text-blue-600"></i>
                            <span id="file-name" class="text-sm text-gray-700"></span>
                            <button type="button" id="remove-file" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                <button type="button" data-modal-close="documentModal" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                    <span id="documentSubmitText">Upload Document</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- View Patient Modal -->
<div id="viewPatientModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden modal">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl mx-4 modal-backdrop max-h-screen overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Patient Details</h3>
            <button data-modal-close="viewPatientModal" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div id="patientDetailsContent" class="p-6">
            <!-- Patient details will be loaded here -->
        </div>
    </div>
</div>

<!-- View Invoice Modal -->
<div id="viewInvoiceModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden modal">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl mx-4 modal-backdrop max-h-screen overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Invoice Details</h3>
            <button data-modal-close="viewInvoiceModal" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div id="invoiceDetailsContent" class="p-6">
            <!-- Invoice details will be loaded here -->
        </div>
    </div>
</div>

<!-- View Document Modal -->
<div id="viewDocumentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden modal">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl mx-4 modal-backdrop max-h-screen overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Document Details</h3>
            <button data-modal-close="viewDocumentModal" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div id="documentDetailsContent" class="p-6">
            <!-- Document details will be loaded here -->
        </div>
    </div>
</div>

<script>
// Fetch and populate dentists for the appointment modal
fetch('/Fil_Rouge_Projet/dashboard/api/dentists.php')
    .then(res => res.json())
    .then(data => {
        const select = document.getElementById('dentist-select');
        if (select && Array.isArray(data)) {
            data.forEach(dentist => {
                const opt = document.createElement('option');
                opt.value = dentist.id;
                opt.textContent = dentist.first_name + ' ' + dentist.last_name;
                select.appendChild(opt);
            });
        }
    });
</script>
