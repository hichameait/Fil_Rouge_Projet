<!-- Nouvelle modale de rendez-vous -->
<div id="appointmentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden modal">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4 modal-backdrop">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Planifier un nouveau rendez-vous</h3>
            <button data-modal-close="appointmentModal" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="appointmentForm" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="patient-select" class="block text-sm font-medium text-gray-700 mb-2">Patient</label>
                    <select id="patient-select" name="patient_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Sélectionner un patient</option>
                    </select>
                </div>
                
                <div>
                    <label for="service-select" class="block text-sm font-medium text-gray-700 mb-2">Service</label>
                    <select id="service-select" name="service_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Sélectionner un service</option>
                    </select>
                </div>
                <div>
                    <label for="appointment-date" class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                    <input type="date" id="appointment-date" name="appointment_date" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div>
                    <label for="appointment-time" class="block text-sm font-medium text-gray-700 mb-2">Heure</label>
                    <input type="time" id="appointment-time" name="appointment_time" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div>
                    <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">Durée (minutes)</label>
                    <input type="number" id="duration" name="duration" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" readonly>
                </div>
            </div>
                        
            <div class="mt-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                <textarea id="notes" name="notes" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Notes supplémentaires..."></textarea>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                <button type="button" data-modal-close="appointmentModal" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Annuler
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                    Planifier le rendez-vous
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modale Ajouter Patient -->
<div id="patientModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden modal">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4 modal-backdrop">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900" id="patientModalTitle">Ajouter un nouveau patient</h3>
            <button data-modal-close="patientModal" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="patientForm" class="p-6">
            <input type="hidden" id="patient-id" name="patient_id">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="first-name" class="block text-sm font-medium text-gray-700 mb-2">Prénom *</label>
                    <input type="text" id="first-name" name="first_name" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div>
                    <label for="last-name" class="block text-sm font-medium text-gray-700 mb-2">Nom *</label>
                    <input type="text" id="last-name" name="last_name" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" id="email" name="email" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Téléphone *</label>
                    <input type="tel" id="phone" name="phone" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div>
                    <label for="date-of-birth" class="block text-sm font-medium text-gray-700 mb-2">Date de naissance</label>
                    <input type="date" id="date-of-birth" name="date_of_birth" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Genre</label>
                    <select id="gender" name="gender" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Sélectionner le genre</option>
                        <option value="male">Homme</option>
                        <option value="female">Femme</option>
                        <option value="other">Autre</option>
                    </select>
                </div>
            </div>
            
            <div class="mt-6">
                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Adresse</label>
                <textarea id="address" name="address" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Adresse complète..."></textarea>
            </div>
            
            <div class="mt-6">
                <label for="medical-history" class="block text-sm font-medium text-gray-700 mb-2">Antécédents médicaux</label>
                <textarea id="medical-history" name="medical_history" rows="4" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Antécédents médicaux, allergies, médicaments..."></textarea>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                <button type="button" data-modal-close="patientModal" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Annuler
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                    <span id="patientSubmitText">Ajouter le patient</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modale Créer Facture -->
<div id="invoiceModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden modal">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl mx-4 modal-backdrop max-h-screen overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900" id="invoiceModalTitle">Créer une nouvelle facture</h3>
            <button data-modal-close="invoiceModal" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="invoiceForm" class="p-6">
            <input type="hidden" id="invoice-id" name="invoice_id">
            
            <!-- En-tête de la facture -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="invoice-patient" class="block text-sm font-medium text-gray-700 mb-2">Patient *</label>
                    <select id="invoice-patient" name="patient_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Sélectionner un patient</option>
                    </select>
                </div>
                
                <div>
                    <label for="invoice-number" class="block text-sm font-medium text-gray-700 mb-2">Numéro de facture</label>
                    <input type="text" id="invoice-number" name="invoice_number" class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100" readonly>
                </div>
                
                <div>
                    <label for="due-date" class="block text-sm font-medium text-gray-700 mb-2">Date d'échéance</label>
                    <input type="date" id="due-date" name="due_date" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label for="payment-method" class="block text-sm font-medium text-gray-700 mb-2">Mode de paiement</label>
                    <select id="payment-method" name="payment_method" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Sélectionner le mode de paiement</option>
                        <option value="cash">Espèces</option>
                        <option value="credit_card">Carte bancaire</option>
                        <option value="bank_transfer">Virement bancaire</option>
                        <option value="insurance">Assurance</option>
                        <option value="other">Autre</option>
                    </select>
                </div>
            </div>

            <!-- Lignes de facture -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-medium text-gray-900">Lignes de facture</h4>
                    <button type="button" id="addInvoiceItem" class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">
                        <i class="fas fa-plus mr-1"></i>Ajouter une ligne
                    </button>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Service</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Qté</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Prix unitaire</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody id="invoiceItemsTable">
                            <!-- Les lignes de facture seront ajoutées ici dynamiquement -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Totaux de la facture -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="invoice-notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea id="invoice-notes" name="notes" rows="4" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Notes supplémentaires..."></textarea>
                </div>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-700">Sous-total :</span>
                        <span id="invoiceSubtotal" class="text-sm text-gray-900">0,00 DH</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <label for="tax-amount" class="text-sm font-medium text-gray-700">Taxe :</label>
                        <input type="number" id="tax-amount" name="tax_amount" step="0.01" min="0" value="0" class="w-24 border border-gray-300 rounded px-2 py-1 text-sm text-right">
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <label for="discount-amount" class="text-sm font-medium text-gray-700">Remise :</label>
                        <input type="number" id="discount-amount" name="discount_amount" step="0.01" min="0" value="0" class="w-24 border border-gray-300 rounded px-2 py-1 text-sm text-right">
                    </div>
                    
                    <div class="flex justify-between border-t pt-3">
                        <span class="text-lg font-bold text-gray-900">Total :</span>
                        <span id="invoiceTotal" class="text-lg font-bold text-gray-900">0,00 DH</span>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <button type="button" data-modal-close="invoiceModal" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Annuler
                </button>
                <button type="button" id="saveInvoiceDraft" class="px-4 py-2 bg-gray-600 text-white rounded-md text-sm font-medium hover:bg-gray-700">
                    Enregistrer comme brouillon
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                    <span id="invoiceSubmitText">Créer la facture</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modale Ajouter Document -->
<div id="documentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden modal">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4 modal-backdrop">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900" id="documentModalTitle">Ajouter un document</h3>
            <button data-modal-close="documentModal" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="documentForm" class="p-6" enctype="multipart/form-data">
            <input type="hidden" id="document-id" name="document_id">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="document-title" class="block text-sm font-medium text-gray-700 mb-2">Titre *</label>
                    <input type="text" id="document-title" name="title" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div>
                    <label for="document-type" class="block text-sm font-medium text-gray-700 mb-2">Type de document *</label>
                    <select id="document-type" name="type" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Sélectionner le type</option>
                        <option value="xray">Radiographie</option>
                        <option value="treatment_plan">Plan de traitement</option>
                        <option value="consent_form">Formulaire de consentement</option>
                        <option value="report">Rapport</option>
                        <option value="prescription">Ordonnance</option>
                        <option value="other">Autre</option>
                    </select>
                </div>
                
                <div>
                    <label for="document-patient" class="block text-sm font-medium text-gray-700 mb-2">Patient</label>
                    <select id="document-patient" name="patient_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Sélectionner un patient (optionnel)</option>
                    </select>
                </div>
                
                <div>
                    <label for="document-appointment" class="block text-sm font-medium text-gray-700 mb-2">Rendez-vous lié</label>
                    <select id="document-appointment" name="appointment_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Sélectionner un rendez-vous (optionnel)</option>
                    </select>
                </div>
            </div>
            
            <div class="mt-6">
                <label for="document-description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea id="document-description" name="description" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Description du document..."></textarea>
            </div>
            
            <div class="mt-6">
                <label for="document-file" class="block text-sm font-medium text-gray-700 mb-2">Fichier *</label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                    <input type="file" id="document-file" name="document_file" class="hidden" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif" required>
                    <div id="file-drop-zone" class="cursor-pointer">
                        <i class="fas fa-cloud-upload-alt text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-600 mb-2">Cliquez pour ajouter ou glissez-déposez</p>
                        <p class="text-sm text-gray-500">PDF, DOC, DOCX, JPG, PNG, GIF (Max 10Mo)</p>
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
                    Annuler
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                    <span id="documentSubmitText">Ajouter le document</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modale Voir Patient -->
<div id="viewPatientModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden modal">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl mx-4 modal-backdrop max-h-screen overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Détails du patient</h3>
            <button data-modal-close="viewPatientModal" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div id="patientDetailsContent" class="p-6">
            <!-- Les détails du patient seront chargés ici -->
        </div>
    </div>
</div>

<!-- Modale Voir Facture -->
<div id="viewInvoiceModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden modal">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl mx-4 modal-backdrop max-h-screen overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Détails de la facture</h3>
            <button data-modal-close="viewInvoiceModal" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div id="invoiceDetailsContent" class="p-6">
            <!-- Les détails de la facture seront chargés ici -->
        </div>
    </div>
</div>

<!-- Modale Voir Document -->
<div id="viewDocumentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden modal">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl mx-4 modal-backdrop max-h-screen overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Détails du document</h3>
            <button data-modal-close="viewDocumentModal" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div id="documentDetailsContent" class="p-6">
            <!-- Les détails du document seront chargés ici -->
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
