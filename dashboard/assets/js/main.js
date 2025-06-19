document.addEventListener('DOMContentLoaded', () => {
    initializeEventHandlers()
    initializeModals()
    initializeForms()
})

function initializeEventHandlers() {
    // Patient buttons
    document.querySelectorAll('#addPatientBtn, .view-patient-btn, .edit-patient-btn, .delete-patient-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const action = e.currentTarget.classList.contains('view-patient-btn') ? 'view' :
                          e.currentTarget.classList.contains('edit-patient-btn') ? 'edit' :
                          e.currentTarget.classList.contains('delete-patient-btn') ? 'delete' : 'add';
            const patientId = e.currentTarget.getAttribute('data-id');
            handlePatientAction(action, patientId);
        });
    });

    // Invoice buttons
    document.querySelectorAll('.mark-paid-btn, .download-invoice-btn, .edit-invoice-btn, .delete-invoice-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const action = e.currentTarget.classList.contains('mark-paid-btn') ? 'mark-paid' :
                          e.currentTarget.classList.contains('download-invoice-btn') ? 'download' :
                          e.currentTarget.classList.contains('edit-invoice-btn') ? 'edit' :
                          'delete';
            const invoiceId = e.currentTarget.getAttribute('data-id');
            handleInvoiceAction(action, invoiceId);
        });
    });

    // Appointment buttons
    document.querySelectorAll('#newAppointmentBtn, .reschedule-btn, .cancel-appointment-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const action = e.currentTarget.id === 'newAppointmentBtn' ? 'new' :
                          e.currentTarget.classList.contains('reschedule-btn') ? 'reschedule' : 'cancel';
            const appointmentId = e.currentTarget.getAttribute('data-id');
            handleAppointmentAction(action, appointmentId);
        });
    });
}

function handlePatientAction(action, patientId = null) {
    switch(action) {
        case 'add':
            resetForm('patientForm');
            openModal('patientModal');
            break;
        case 'view':
            fetch(`api/patients.php?id=${patientId}`)
                .then(response => response.json())
                .then(data => {
                    populatePatientDetails(data);
                    openModal('viewPatientModal');
                });
            break;
        case 'edit':
            fetch(`api/patients.php?id=${patientId}`)
                .then(response => response.json())
                .then(data => {
                    populatePatientForm(data);
                    openModal('patientModal');
                });
            break;
        case 'delete':
            confirmDelete('patient', patientId);
            break;
    }
}

function handleInvoiceAction(action, invoiceId) {
    switch(action) {
        case 'mark-paid':
            markInvoicePaid(invoiceId);
            break;
        case 'download':
            window.location.href = `api/invoices.php?action=download&id=${invoiceId}`;
            break;
        case 'edit':
            fetch(`api/invoices.php?id=${invoiceId}`)
                .then(response => response.json())
                .then(data => {
                    populateInvoiceForm(data);
                    openModal('invoiceModal');
                });
            break;
        case 'delete':
            confirmDelete('invoice', invoiceId);
            break;
    }
}

function handleAppointmentAction(action, appointmentId = null) {
    switch(action) {
        case 'new':
            resetForm('appointmentForm');
            loadInitialData();
            openModal('appointmentModal');
            break;
        case 'reschedule':
            showRescheduleDialog(appointmentId);
            break;
        case 'cancel':
            confirmCancel(appointmentId);
            break;
    }
}

// Utility functions
function resetForm(formId) {
    const form = document.getElementById(formId);
    if (form) {
        form.reset();
        form.querySelectorAll('input[type="hidden"]').forEach(input => input.value = '');
    }
}

function loadInitialData() {
    Promise.all([
        fetch('api/patients.php').then(res => res.json()),
        fetch('api/services.php').then(res => res.json()),
        fetch('api/dentists.php').then(res => res.json())
    ]).then(([patients, services, dentists]) => {
        populateSelect('patient-select', patients, 'Sélectionner un patient');
        populateSelect('service-select', services, 'Sélectionner un service');
        populateSelect('dentist-select', dentists, 'Sélectionner un dentiste');
    });
}

function populateSelect(selectId, data, defaultText) {
    const select = document.getElementById(selectId);
    if (select) {
        select.innerHTML = `<option value="">${defaultText}</option>`;
        data.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = item.name || `${item.first_name} ${item.last_name}`;
            select.appendChild(option);
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // Initialize core functionality
    initializeEventHandlers()
    initializeModals()
    
    // Initialize specific features based on current page
    const urlParams = new URLSearchParams(window.location.search);
    const currentPage = urlParams.get('page');
    
    if (currentPage === 'appointments') {
        initializeAppointments()
    }
    
    if (currentPage === 'patients') {
        initializePatients()
    }
    
    if (currentPage === 'invoices') {
        initializeInvoices()
    }

    if (currentPage === 'documents') {
        initializeDocuments()
    }

    // Initialize create invoice button
    const createInvoiceBtn = document.getElementById("createInvoiceBtn")
    if (createInvoiceBtn) {
        createInvoiceBtn.addEventListener("click", () => {
            resetInvoiceForm()
            openModal("invoiceModal")
            loadPatientsForInvoice()
            generateInvoiceNumber()
        })
    }

    // Initialize upload document button
    const uploadDocumentBtn = document.getElementById("uploadDocumentBtn")
    if (uploadDocumentBtn) {
        uploadDocumentBtn.addEventListener("click", () => {
            resetDocumentForm()
            openModal("documentModal")
            loadPatientsForDocument()
        })
    }
})

function initializeModals() {
  // New Appointment Modal
  const newAppointmentBtn = document.getElementById("newAppointmentBtn")
  const quickScheduleBtn = document.getElementById("quickScheduleBtn")
  const scheduleFirstBtn = document.getElementById("scheduleFirstBtn")

  if (newAppointmentBtn) {
    newAppointmentBtn.addEventListener("click", () => {
      openModal("appointmentModal")
      loadPatients()
      loadServices()
      loadDentists()
    })
  }

  if (quickScheduleBtn) {
    quickScheduleBtn.addEventListener("click", () => {
      openModal("appointmentModal")
      loadPatients()
      loadServices()
      loadDentists()
    })
  }

  if (scheduleFirstBtn) {
    scheduleFirstBtn.addEventListener("click", () => {
      openModal("appointmentModal")
      loadPatients()
      loadServices()
      loadDentists()
    })
  }

  // Close modal handlers
  document.querySelectorAll("[data-modal-close]").forEach((button) => {
    button.addEventListener("click", function () {
      const modalId = this.getAttribute("data-modal-close")
      closeModal(modalId)
    })
  })

  // Close modal on backdrop click
  document.querySelectorAll(".modal").forEach((modal) => {
    modal.addEventListener("click", function (e) {
      if (e.target === this) {
        closeModal(this.id)
      }
    })
  })

  // Appointment form submission
  const appointmentForm = document.getElementById("appointmentForm")
  if (appointmentForm) {
    appointmentForm.addEventListener("submit", handleAppointmentFormSubmit)
  }
}

function initializePatients() {
  // Add patient button
  const addPatientBtn = document.getElementById("addPatientBtn")
  if (addPatientBtn) {
    addPatientBtn.addEventListener("click", () => {
      resetPatientForm()
      openModal("patientModal")
    })
  }

  // Patient action buttons
  document.querySelectorAll(".view-patient-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
      const patientId = this.getAttribute("data-id")
      viewPatient(patientId)
    })
  })

  document.querySelectorAll(".edit-patient-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
      const patientId = this.getAttribute("data-id")
      editPatient(patientId)
    })
  })

  document.querySelectorAll(".schedule-appointment-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
      const patientId = this.getAttribute("data-id")
      scheduleAppointmentForPatient(patientId)
    })
  })

  document.querySelectorAll(".delete-patient-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
      const patientId = this.getAttribute("data-id")
      deletePatient(patientId)
    })
  })

  // Patient form submission
  const patientForm = document.getElementById("patientForm")
  if (patientForm) {
    patientForm.addEventListener("submit", handlePatientFormSubmit)
  }
}

function initializeInvoices() {
  // Create invoice button
    const createInvoiceBtn = document.getElementById("createInvoiceBtn")
    if (createInvoiceBtn) {
        createInvoiceBtn.addEventListener("click", () => {
            const invoiceModal = document.getElementById("invoiceModal")
            if (!invoiceModal) {
                console.error("Invoice modal not found. Make sure modals.php is included in your page.")
                showToast("Error: Invoice modal not found", "error")
                return
            }

            try {
                resetInvoiceForm()
                openModal("invoiceModal")
                loadPatientsForInvoice()
                generateInvoiceNumber()
            } catch (error) {
                console.error("Error initializing invoice form:", error)
                showToast("Error initializing invoice form", "error")
            }
        })
    } else {
        console.warn("Create invoice button not found")
    }

  // Invoice action buttons
  document.querySelectorAll(".view-invoice-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
      const invoiceId = this.getAttribute("data-id")
      viewInvoice(invoiceId)
    })
  })

  document.querySelectorAll(".edit-invoice-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
      const invoiceId = this.getAttribute("data-id")
      editInvoice(invoiceId)
    })
  })

  document.querySelectorAll(".download-invoice-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
      const invoiceId = this.getAttribute("data-id")
      downloadInvoice(invoiceId)
    })
  })

  document.querySelectorAll(".mark-paid-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
      const invoiceId = this.getAttribute("data-id")
      markInvoicePaid(invoiceId)
    })
  })

  document.querySelectorAll(".send-invoice-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
      const invoiceId = this.getAttribute("data-id")
      sendInvoice(invoiceId)
    })
  })

  document.querySelectorAll(".delete-invoice-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
      const invoiceId = this.getAttribute("data-id")
      deleteInvoice(invoiceId)
    })
  })

  // Invoice form handlers
  const invoiceForm = document.getElementById("invoiceForm")
  if (invoiceForm) {
    invoiceForm.addEventListener("submit", handleInvoiceFormSubmit)
  }

  const addInvoiceItemBtn = document.getElementById("addInvoiceItem")
  if (addInvoiceItemBtn) {
    addInvoiceItemBtn.addEventListener("click", addInvoiceItem)
  }

  const saveInvoiceDraftBtn = document.getElementById("saveInvoiceDraft")
  if (saveInvoiceDraftBtn) {
    saveInvoiceDraftBtn.addEventListener("click", () => saveInvoice("draft"))
  }

  // Tax and discount change handlers
  const taxAmount = document.getElementById("tax-amount")
  const discountAmount = document.getElementById("discount-amount")

  if (taxAmount) {
    taxAmount.addEventListener("input", calculateInvoiceTotal)
  }

  if (discountAmount) {
    discountAmount.addEventListener("input", calculateInvoiceTotal)
  }
}

function initializeDocuments() {
  // Upload document button
  const uploadDocumentBtn = document.getElementById("uploadDocumentBtn")
  if (uploadDocumentBtn) {
    uploadDocumentBtn.addEventListener("click", () => {
      resetDocumentForm()
      openModal("documentModal")
      loadPatientsForDocument()
    })
  }

  // Document action buttons
  document.querySelectorAll(".view-document-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
      const documentId = this.getAttribute("data-id")
      viewDocument(documentId)
    })
  })

  document.querySelectorAll(".download-document-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
      const documentId = this.getAttribute("data-id")
      downloadDocument(documentId)
    })
  })

  document.querySelectorAll(".edit-document-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
      const documentId = this.getAttribute("data-id")
      editDocument(documentId)
    })
  })

  document.querySelectorAll(".delete-document-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
      const documentId = this.getAttribute("data-id")
      deleteDocument(documentId)
    })
  })

  // Document menu toggles
  document.querySelectorAll(".document-menu-btn").forEach((btn) => {
    btn.addEventListener("click", function (e) {
      e.stopPropagation()
      const menu = this.nextElementSibling

      // Close all other menus
      document.querySelectorAll(".document-menu").forEach((m) => {
        if (m !== menu) m.classList.add("hidden")
      })

      menu.classList.toggle("hidden")
    })
  })

  // File upload handlers
  const fileInput = document.getElementById("document-file")
  const fileDropZone = document.getElementById("file-drop-zone")
  const filePreview = document.getElementById("file-preview")
  const removeFileBtn = document.getElementById("remove-file")

  if (fileInput && fileDropZone) {
    fileDropZone.addEventListener("click", () => fileInput.click())

    fileInput.addEventListener("change", handleFileSelect)

    // Drag and drop
    fileDropZone.addEventListener("dragover", (e) => {
      e.preventDefault()
      fileDropZone.classList.add("border-blue-500", "bg-blue-50")
    })

    fileDropZone.addEventListener("dragleave", () => {
      fileDropZone.classList.remove("border-blue-500", "bg-blue-50")
    })

    fileDropZone.addEventListener("drop", (e) => {
      e.preventDefault()
      fileDropZone.classList.remove("border-blue-500", "bg-blue-50")
      const files = e.dataTransfer.files
      if (files.length > 0) {
        fileInput.files = files
        handleFileSelect({ target: { files } })
      }
    })
  }

  if (removeFileBtn) {
    removeFileBtn.addEventListener("click", () => {
      fileInput.value = ""
      fileDropZone.classList.remove("hidden")
      filePreview.classList.add("hidden")
    })
  }

  // Document form submission
  const documentForm = document.getElementById("documentForm")
  if (documentForm) {
    documentForm.addEventListener("submit", handleDocumentFormSubmit)
  }

  // Close menus when clicking outside
  document.addEventListener("click", () => {
    document.querySelectorAll(".document-menu").forEach((menu) => {
      menu.classList.add("hidden")
    })
  })
}

// Patient Functions
function resetPatientForm() {
  const form = document.getElementById("patientForm")
  form.reset()
  document.getElementById("patient-id").value = ""
  document.getElementById("patientModalTitle").textContent = "Ajouter un nouveau patient"
  document.getElementById("patientSubmitText").textContent = "Ajouter le patient"
}

function viewPatient(patientId) {
  fetch(`api/patients.php?id=${patientId}`)
    .then((response) => response.json())
    .then((patient) => {
      displayPatientDetails(patient)
      openModal("viewPatientModal")
    })
    .catch((error) => {
      console.error("Error loading patient:", error)
      showToast("Erreur lors du chargement des données du patient", "error")
    })
}

function editPatient(patientId) {
  fetch(`api/patients.php?id=${patientId}`)
    .then((response) => response.json())
    .then((patient) => {
      populatePatientForm(patient)
      document.getElementById("patientModalTitle").textContent = "Modifier le patient"
      document.getElementById("patientSubmitText").textContent = "Mettre à jour le patient"
      openModal("patientModal")
    })
    .catch((error) => {
      console.error("Error loading patient:", error)
      showToast("Erreur lors du chargement des données du patient", "error")
    })
}

function populatePatientForm(patient) {
  document.getElementById("patient-id").value = patient.id
  document.getElementById("first-name").value = patient.first_name || ""
  document.getElementById("last-name").value = patient.last_name || ""
  document.getElementById("email").value = patient.email || ""
  document.getElementById("phone").value = patient.phone || ""
  document.getElementById("date-of-birth").value = patient.date_of_birth || ""
  document.getElementById("gender").value = patient.gender || ""
  document.getElementById("address").value = patient.address || ""
  document.getElementById("medical-history").value = patient.medical_history || ""
}

function displayPatientDetails(patient) {
  const content = document.getElementById("patientDetailsContent")
  content.innerHTML = `
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <h4 class="text-lg font-semibold mb-4">Informations personnelles</h4>
        <div class="space-y-3">
          <div><strong>Nom&nbsp;:</strong> ${patient.first_name} ${patient.last_name}</div>
          <div><strong>ID Patient&nbsp;:</strong> ${patient.patient_number}</div>
          <div><strong>Email&nbsp;:</strong> ${patient.email || "N/A"}</div>
          <div><strong>Téléphone&nbsp;:</strong> ${patient.phone}</div>
          <div><strong>Date de naissance&nbsp;:</strong> ${patient.date_of_birth || "N/A"}</div>
          <div><strong>Sexe&nbsp;:</strong> ${patient.gender || "N/A"}</div>
          <div><strong>Adresse&nbsp;:</strong> ${patient.address || "N/A"}</div>
        </div>
      </div>
      <div>
        <h4 class="text-lg font-semibold mb-4">Informations médicales</h4>
        <div class="space-y-3">
          <div><strong>Antécédents médicaux&nbsp;:</strong><br>${patient.medical_history || "Aucun antécédent médical enregistré"}</div>
          <div><strong>Statut&nbsp;:</strong> <span class="px-2 py-1 rounded text-sm ${patient.status === "active" ? "bg-green-100 text-green-800" : "bg-red-100 text-red-800"}">${patient.status === "active" ? "Actif" : "Inactif"}</span></div>
        </div>
      </div>
    </div>
  `
}

function scheduleAppointmentForPatient(patientId) {
  openModal("appointmentModal")
  loadPatients().then(() => {
    const patientSelect = document.getElementById("patient-select")
    if (patientSelect) {
      patientSelect.value = patientId
    }
  })
  loadServices()
  loadDentists()
}

function deletePatient(patientId) {
  const Swal = window.Swal
  Swal.fire({
    title: "Êtes-vous sûr ?",
    text: "Cela supprimera définitivement le patient et toutes les données associées !",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Oui, supprimer !",
  }).then((result) => {
    if (result.isConfirmed) {
      fetch("api/patients.php", {
        method: "DELETE",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ id: patientId }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            Swal.fire("Supprimé !", "Le patient a été supprimé.", "success").then(() => location.reload())
          } else {
            Swal.fire("Erreur !", data.message, "error")
          }
        })
        .catch((error) => {
          Swal.fire("Erreur !", "Échec de la suppression du patient", "error")
        })
    }
  })
}

function handlePatientFormSubmit(e) {
  e.preventDefault()

  const formData = new FormData(e.target)
  const isUpdate = formData.get("patient_id") !== ""

  const url = "api/patients.php"
  const method = isUpdate ? "PUT" : "POST"

  // Convert FormData to JSON for PUT requests
  if (method === "PUT") {
    const data = {}
    formData.forEach((value, key) => {
      data[key] = value
    })

    fetch(url, {
      method: method,
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(data),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          showToast(isUpdate ? "Patient mis à jour avec succès !" : "Patient ajouté avec succès !", "success")
          closeModal("patientModal")
          setTimeout(() => location.reload(), 1000)
        } else {
          showToast("Erreur : " + data.message, "error")
        }
      })
      .catch((error) => {
        console.error("Error:", error)
        showToast("Une erreur est survenue. Veuillez réessayer.", "error")
      })
  } else {
    fetch(url, {
      method: method,
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          showToast(isUpdate ? "Patient mis à jour avec succès !" : "Patient ajouté avec succès !", "success")
          closeModal("patientModal")
          setTimeout(() => location.reload(), 1000)
        } else {
          showToast("Erreur : " + data.message, "error")
        }
      })
      .catch((error) => {
        console.error("Error:", error)
        showToast("Une erreur est survenue. Veuillez réessayer.", "error")
      })
  }
}

// Invoice Functions
function resetInvoiceForm() {
  const form = document.getElementById("invoiceForm")
  form.reset()
  document.getElementById("invoice-id").value = ""
  document.getElementById("invoiceModalTitle").textContent = "Créer une nouvelle facture"
  document.getElementById("invoiceSubmitText").textContent = "Créer une facture"
  document.getElementById("invoiceItemsTable").innerHTML = ""
  calculateInvoiceTotal()
}

function generateInvoiceNumber() {
  const now = new Date()
  const year = now.getFullYear()
  const month = String(now.getMonth() + 1).padStart(2, "0")
  const day = String(now.getDate()).padStart(2, "0")
  const random = Math.floor(Math.random() * 1000)
    .toString()
    .padStart(3, "0")

  const invoiceNumber = `INV-${year}${month}${day}-${random}`
  document.getElementById("invoice-number").value = invoiceNumber
}

function loadPatientsForInvoice() {
  fetch("api/patients.php")
    .then((response) => response.json())
    .then((patients) => {
      const select = document.getElementById("invoice-patient")
      select.innerHTML = '<option value="">Sélectionner un patient</option>'
      patients.forEach((patient) => {
        const option = document.createElement("option")
        option.value = patient.id
        option.textContent = `${patient.first_name} ${patient.last_name}`
        select.appendChild(option)
      })
    })
    .catch((error) => console.error("Error loading patients:", error))
}

function addInvoiceItem() {
  const tableBody = document.getElementById("invoiceItemsTable")
  const rowCount = tableBody.children.length

  const row = document.createElement("tr")
  row.innerHTML = `
    <td class="px-4 py-2 border-b">
      <select name="items[${rowCount}][service_id]" class="w-full border border-gray-300 rounded px-2 py-1 text-sm service-select">
        <option value="">Sélectionner un service</option>
      </select>
    </td>
    <td class="px-4 py-2 border-b">
      <input type="text" name="items[${rowCount}][description]" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" placeholder="Description">
    </td>
    <td class="px-4 py-2 border-b">
      <input type="number" name="items[${rowCount}][quantity]" value="1" min="1" class="w-full border border-gray-300 rounded px-2 py-1 text-sm quantity-input">
    </td>
    <td class="px-4 py-2 border-b">
      <input type="number" name="items[${rowCount}][unit_price]" step="0.01" min="0" class="w-full border border-gray-300 rounded px-2 py-1 text-sm price-input" placeholder="0.00">
    </td>
    <td class="px-4 py-2 border-b">
      <span class="item-total">$0.00</span>
    </td>
    <td class="px-4 py-2 border-b">
      <button type="button" class="text-red-600 hover:text-red-800 remove-item-btn">
        <i class="fas fa-trash"></i>
      </button>
    </td>
  `

  tableBody.appendChild(row)

  // Load services for the new row
  loadServicesForInvoiceItem(row.querySelector(".service-select"))

  // Add event listeners
  const quantityInput = row.querySelector(".quantity-input")
  const priceInput = row.querySelector(".price-input")
  const removeBtn = row.querySelector(".remove-item-btn")

  quantityInput.addEventListener("input", calculateInvoiceTotal)
  priceInput.addEventListener("input", calculateInvoiceTotal)
  removeBtn.addEventListener("click", () => {
    row.remove()
    calculateInvoiceTotal()
  })

  // Service selection handler
  const serviceSelect = row.querySelector(".service-select")
  serviceSelect.addEventListener("change", function () {
    const selectedOption = this.options[this.selectedIndex]
    if (selectedOption.value) {
      const price = selectedOption.getAttribute("data-price")
      const description = selectedOption.getAttribute("data-description")

      priceInput.value = price
      row.querySelector('input[name*="[description]"]').value = description
      calculateInvoiceTotal()
    }
  })
}

function loadServicesForInvoiceItem(selectElement) {
  fetch("api/services.php")
    .then((response) => response.json())
    .then((services) => {
      selectElement.innerHTML = '<option value="">Sélectionner un service</option>'
      services.forEach((service) => {
        const option = document.createElement("option")
        option.value = service.id
        option.textContent = service.name
        option.setAttribute("data-price", service.price)
        option.setAttribute("data-description", service.description || service.name)
        selectElement.appendChild(option)
      })
    })
    .catch((error) => console.error("Error loading services:", error))
}

function calculateInvoiceTotal() {
  let subtotal = 0

  // Calculate subtotal from items
  document.querySelectorAll("#invoiceItemsTable tr").forEach((row) => {
    const quantity = Number.parseFloat(row.querySelector(".quantity-input")?.value || 0)
    const price = Number.parseFloat(row.querySelector(".price-input")?.value || 0)
    const itemTotal = quantity * price

    const totalSpan = row.querySelector(".item-total")
    if (totalSpan) {
      totalSpan.textContent = `${itemTotal.toFixed(2)}`
    }

    subtotal += itemTotal
  })

  // Get tax and discount
  const tax = Number.parseFloat(document.getElementById("tax-amount")?.value || 0)
  const discount = Number.parseFloat(document.getElementById("discount-amount")?.value || 0)

  // Calculate total
  const total = subtotal + tax - discount

  // Update display
  document.getElementById("invoiceSubtotal").textContent = `$${subtotal.toFixed(2)}`
  document.getElementById("invoiceTotal").textContent = `$${total.toFixed(2)}`
}

function viewInvoice(invoiceId) {
  fetch(`api/invoices.php?id=${invoiceId}`)
    .then((response) => response.json())
    .then((invoice) => {
      displayInvoiceDetails(invoice)
      openModal("viewInvoiceModal")
    })
    .catch((error) => {
      console.error("Error loading invoice:", error)
      showToast("Error loading invoice data", "error")
    })
}

function displayInvoiceDetails(invoice) {
  const content = document.getElementById("invoiceDetailsContent")
  content.innerHTML = `
    <div class="space-y-6">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <h4 class="text-lg font-semibold mb-4">Informations de la facture</h4>
          <div class="space-y-2">
            <div><strong>Numéro de facture :</strong> ${invoice.invoice_number}</div>
            <div><strong>Patient :</strong> ${invoice.patient_name}</div>
            <div><strong>Date :</strong> ${new Date(invoice.created_at).toLocaleDateString()}</div>
            <div><strong>Date d'échéance :</strong> ${invoice.due_date ? new Date(invoice.due_date).toLocaleDateString() : "N/A"}</div>
            <div><strong>Statut :</strong> <span class="px-2 py-1 rounded text-sm">${invoice.status}</span></div>
          </div>
        </div>
        <div>
          <h4 class="text-lg font-semibold mb-4">Informations de paiement</h4>
          <div class="space-y-2">
            <div><strong>Sous-total :</strong> ${Number.parseFloat(invoice.subtotal).toFixed(2)} DH</div>
            <div><strong>Taxe :</strong> ${Number.parseFloat(invoice.tax_amount).toFixed(2)} DH</div>
            <div><strong>Remise :</strong> ${Number.parseFloat(invoice.discount_amount).toFixed(2)} DH</div>
            <div class="text-lg"><strong>Total :</strong> ${Number.parseFloat(invoice.total_amount).toFixed(2)} DH</div>
          </div>
        </div>
      </div>
      ${invoice.notes ? `<div><strong>Notes :</strong><br>${invoice.notes}</div>` : ""}
    </div>
  `
}

function editInvoice(invoiceId) {
  // Load invoice data and populate form
  showToast("Edit invoice functionality coming soon", "info")
}

function downloadInvoice(invoiceId) {
  window.open(`api/invoices.php?action=download&id=${invoiceId}`, "_blank")
}

function markInvoicePaid(invoiceId) {
  const Swal = window.Swal
  Swal.fire({
    title: "Marquer comme payée ?",
    text: "Cela va marquer la facture comme payée.",
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "Oui, marquer comme payée",
  }).then((result) => {
    if (result.isConfirmed) {
      // Send reschedule request
      fetch("api/invoices.php", {
        method: "PUT",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          id: invoiceId,
          status: "paid",
          paid_date: new Date().toISOString(),
        }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            Swal.fire("Succès !", "Facture marquée comme payée avec succès", "success").then(() => location.reload())
          } else {
            Swal.fire("Erreur !", data.message, "error")
          }
        })
        .catch((error) => {
          Swal.fire("Erreur !", "Échec lors du marquage de la facture comme payée", "error")
        })
    }
  })
}

function sendInvoice(invoiceId) {
  showToast("Send invoice functionality coming soon", "info")
}

function deleteInvoice(invoiceId) {
  const Swal = window.Swal
  Swal.fire({
    title: "Êtes-vous sûr ?",
    text: "Cela supprimera définitivement la facture !",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Oui, supprimer !",
  }).then((result) => {
    if (result.isConfirmed) {
      fetch("api/invoices.php", {
        method: "DELETE",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ id: invoiceId }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            Swal.fire("Supprimé !", "La facture a été supprimée.", "success").then(() => location.reload())
          } else {
            Swal.fire("Erreur !", data.message, "error")
          }
        })
        .catch((error) => {
          Swal.fire("Erreur !", "Échec de la suppression de la facture", "error")
        })
    }
  })
}

function handleInvoiceFormSubmit(e) {
  e.preventDefault()
  saveInvoice("sent")
}

function saveInvoice(status) {
  const formData = new FormData(document.getElementById("invoiceForm"))
  formData.append("status", status)

  fetch("api/invoices.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        showToast(`Invoice ${status === "draft" ? "saved as draft" : "created"} successfully!`, "success")
        closeModal("invoiceModal")
        setTimeout(() => location.reload(), 1000)
      } else {
        showToast("Error: " + data.message, "error")
      }
    })
    .catch((error) => {
      console.error("Error:", error)
      showToast("An error occurred. Please try again.", "error")
    })
}

// Document Functions
function resetDocumentForm() {
  const form = document.getElementById("documentForm")
  form.reset()
  document.getElementById("document-id").value = ""
  document.getElementById("documentModalTitle").textContent = "Télécharger un document"
  document.getElementById("documentSubmitText").textContent = "Télécharger le document"

  // Reset file upload area
  const fileDropZone = document.getElementById("file-drop-zone")
  const filePreview = document.getElementById("file-preview")
  if (fileDropZone && filePreview) {
    fileDropZone.classList.remove("hidden")
    filePreview.classList.add("hidden")
  }
}

function loadPatientsForDocument() {
  fetch("api/patients.php")
    .then((response) => response.json())
    .then((patients) => {
      const select = document.getElementById("document-patient")
      select.innerHTML = '<option value="">Sélectionner un patient (optionnel)</option>'
      patients.forEach((patient) => {
        const option = document.createElement("option")
        option.value = patient.id
        option.textContent = `${patient.first_name} ${patient.last_name}`
        select.appendChild(option)
      })
    })
    .catch((error) => console.error("Error loading patients:", error))
}

function handleFileSelect(e) {
  const files = e.target.files
  if (files.length > 0) {
    const file = files[0]
    const fileDropZone = document.getElementById("file-drop-zone")
    const filePreview = document.getElementById("file-preview")
    const fileName = document.getElementById("file-name")

    if (fileName) {
      fileName.textContent = file.name
    }

    if (fileDropZone && filePreview) {
      fileDropZone.classList.add("hidden")
      filePreview.classList.remove("hidden")
    }
  }
}

function viewDocument(documentId) {
  fetch(`api/documents.php?id=${documentId}`)
    .then((response) => response.json())
    .then((document) => {
      displayDocumentDetails(document)
      openModal("viewDocumentModal")
    })
    .catch((error) => {
      console.error("Error loading document:", error)
      showToast("Error loading document data", "error")
    })
}

function displayDocumentDetails(document) {
  const content = document.getElementById("documentDetailsContent")
  content.innerHTML = `
    <div class="space-y-6">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <h4 class="text-lg font-semibold mb-4">Informations du document</h4>
          <div class="space-y-2">
            <div><strong>Titre :</strong> ${document.title}</div>
            <div><strong>Type :</strong> ${document.type.replace("_", " ")}</div>
            <div><strong>Patient :</strong> ${document.patient_name || "N/A"}</div>
            <div><strong>Ajouté :</strong> ${new Date(document.created_at).toLocaleDateString("fr-FR", {
              year: "numeric",
              month: "short",
              day: "numeric",
            })}</div>
            <div><strong>Taille du fichier :</strong> ${formatFileSize(document.file_size)}</div>
          </div>
        </div>
        <div>
          <h4 class="text-lg font-semibold mb-4">Description</h4>
          <p>${document.description || "Aucune description fournie"}</p>
        </div>
      </div>
      ${
        document.file_path
          ? `
        <div class="text-center">
          <button onclick="downloadDocument(${document.id})" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            <i class="fas fa-download mr-2"></i>Télécharger le fichier
          </button>
        </div>
      `
          : ""
      }
    </div>
  `
}

function downloadDocument(documentId) {
  window.open(`api/documents.php?action=download&id=${documentId}`, "_blank")
}

function editDocument(documentId) {
  showToast("Edit document functionality coming soon", "info")
}

function deleteDocument(documentId) {
  const Swal = window.Swal
  Swal.fire({
    title: "Êtes-vous sûr ?",
    text: "Cela supprimera définitivement le document !",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Oui, supprimer !",
  }).then((result) => {
    if (result.isConfirmed) {
      fetch("api/documents.php", {
        method: "DELETE",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ id: documentId }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            Swal.fire("Supprimé !", "Le document a été supprimé.", "success").then(() => location.reload())
          } else {
            Swal.fire("Erreur !", data.message, "error")
          }
        })
        .catch((error) => {
          Swal.fire("Erreur !", "Échec de la suppression du document", "error")
        })
    }
  })
}

function handleDocumentFormSubmit(e) {
  e.preventDefault()

  const formData = new FormData(e.target)
  const isUpdate = formData.get("document_id") !== ""

  const url = "api/documents.php"
  const method = isUpdate ? "PUT" : "POST"

  fetch(url, {
    method: method,
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        showToast(isUpdate ? "Document modifié avec succès !" : "Document ajouté avec succès !", "success")
        closeModal("documentModal")
        setTimeout(() => location.reload(), 1000)
      } else {
        showToast("Erreur : " + data.message, "error")
      }
    })
    .catch((error) => {
      console.error("Error:", error)
      showToast("Une erreur est survenue. Veuillez réessayer.", "error")
    })
}

function initializeToothChart() {
  const toothButtons = document.querySelectorAll(".tooth-button")
  const selectedTeethSpan = document.getElementById("selectedTeeth")
  const clearSelectionBtn = document.getElementById("clearSelection")
  let selectedTeeth = []

  toothButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const toothNumber = this.getAttribute("data-tooth")

      if (this.classList.contains("selected")) {
        // Deselect tooth
        this.classList.remove("selected", "bg-blue-500", "text-white")
        this.classList.add("border-gray-300")
        selectedTeeth = selectedTeeth.filter((tooth) => tooth !== toothNumber)
      } else {
        // Select tooth
        this.classList.add("selected", "bg-blue-500", "text-white")
        this.classList.remove("border-gray-300")
        selectedTeeth.push(toothNumber)
      }

      updateSelectedTeethDisplay()
    })
  })

  if (clearSelectionBtn) {
    clearSelectionBtn.addEventListener("click", () => {
      toothButtons.forEach((button) => {
        button.classList.remove("selected", "bg-blue-500", "text-white")
        button.classList.add("border-gray-300")
      })
      selectedTeeth = []
      updateSelectedTeethDisplay()
    })
  }

  function updateSelectedTeethDisplay() {
    if (selectedTeethSpan) {
      selectedTeethSpan.textContent = selectedTeeth.length > 0 ? selectedTeeth.join(", ") : "Aucun"
    }
  }

  // Mini tooth chart in modal
  document.querySelectorAll(".tooth-mini").forEach((tooth) => {
    tooth.addEventListener("click", function () {
      this.classList.toggle("selected")
      this.classList.toggle("bg-blue-500")
      this.classList.toggle("text-white")
      updateSelectedTeethInput()
    })
  })

  function updateSelectedTeethInput() {
    const selectedTeeth = []
    document.querySelectorAll(".tooth-mini.selected").forEach((tooth) => {
      selectedTeeth.push(tooth.getAttribute("data-tooth"))
    })

    const hiddenInput = document.getElementById("selected-teeth")
    if (hiddenInput) {
      hiddenInput.value = JSON.stringify(selectedTeeth)
    }
  }
}

function initializeAppointments() {
  // Reschedule buttons
  document.querySelectorAll(".reschedule-btn").forEach((button) => {
    button.addEventListener("click", function () {
      const appointmentId = this.getAttribute("data-id")
      rescheduleAppointment(appointmentId)
    })
  })

  // View appointment buttons
  document.querySelectorAll(".view-appointment-btn").forEach((button) => {
    button.addEventListener("click", function () {
      const appointmentId = this.getAttribute("data-id")
      viewAppointment(appointmentId)
    })
  })

  // Service selection change handler
  const serviceSelect = document.getElementById("service-select")
  if (serviceSelect) {
    serviceSelect.addEventListener("change", function () {
      const selectedOption = this.options[this.selectedIndex]
      const duration = selectedOption.getAttribute("data-duration")
      const toothRequired = selectedOption.getAttribute("data-tooth-required") === "1"

      // Update duration field
      const durationField = document.getElementById("duration")
      if (durationField) {
        durationField.value = duration || ""
      }

      // Show/hide tooth selection
      const toothSelection = document.getElementById("tooth-selection")
      if (toothSelection) {
        if (toothRequired) {
          toothSelection.classList.remove("hidden")
        } else {
          toothSelection.classList.add("hidden")
        }
      }
    })
  }
}

function rescheduleAppointment(appointmentId) {
  const Swal = window.Swal
  Swal.fire({
    title: "Replanifier le rendez-vous",
    html: `
      <div class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Nouvelle date</label>
          <input type="date" id="reschedule-date" class="w-full border border-gray-300 rounded-md px-3 py-2">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Nouvelle heure</label>
          <input type="time" id="reschedule-time" class="w-full border border-gray-300 rounded-md px-3 py-2">
        </div>
      </div>
    `,
    showCancelButton: true,
    confirmButtonText: "Replanifier",
    cancelButtonText: "Annuler",
    preConfirm: () => {
      const date = document.getElementById("reschedule-date").value
      const time = document.getElementById("reschedule-time").value

      if (!date || !time) {
        Swal.showValidationMessage("Veuillez sélectionner la date et l'heure")
        return false
      }

      return { date, time }
    },
  }).then((result) => {
    if (result.isConfirmed) {
      // Send reschedule request
      fetch("api/appointments.php", {
        method: "PUT",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          id: appointmentId,
          appointment_date: result.value.date,
          appointment_time: result.value.time,
        }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            Swal.fire("Succès !", "Rendez-vous replanifié avec succès", "success").then(() => location.reload())
          } else {
            Swal.fire("Erreur !", data.message, "error")
          }
        })
        .catch((error) => {
          Swal.fire("Erreur !", "Échec de la replanification du rendez-vous", "error")
        })
    }
  })
}

function viewAppointment(appointmentId) {
  // Redirect to appointment details page
  window.location.href = `index.php?page=appointments&action=view&id=${appointmentId}`
}

function handleAppointmentFormSubmit(e) {
  e.preventDefault()

  const formData = new FormData(e.target)

  fetch("api/appointments.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        showToast("Rendez-vous programmé avec succès !", "success")
        closeModal("appointmentModal")
        setTimeout(() => location.reload(), 1000)
      } else {
        showToast("Erreur : " + data.message, "error")
      }
    })
    .catch((error) => {
      console.error("Error:", error)
      showToast("Une erreur est survenue. Veuillez réessayer.", "error")
    })
}

function loadPatients() {
  return fetch("api/patients.php")
    .then((response) => response.json())
    .then((data) => {
      const select = document.getElementById("patient-select")
      if (select) {
        select.innerHTML = '<option value="">Sélectionner un patient</option>'
        data.forEach((patient) => {
          const option = document.createElement("option")
          option.value = patient.id
          option.textContent = `${patient.first_name} ${patient.last_name}`
          select.appendChild(option)
        })
      }
    })
    .catch((error) => console.error("Error loading patients:", error))
}

function loadServices() {
  fetch("api/services.php")
    .then((response) => response.json())
    .then((data) => {
      const select = document.getElementById("service-select")
      if (select) {
        select.innerHTML = '<option value="">Sélectionner un service</option>'
        data.forEach((service) => {
          const option = document.createElement("option")
          option.value = service.id
          option.textContent = `${service.name}`
          option.setAttribute("data-duration", service.duration)
          option.setAttribute("data-tooth-required", service.requires_tooth_selection)
          select.appendChild(option)
        })
      }
    })
    .catch((error) => console.error("Error loading services:", error))
}

function loadDentists() {
  fetch("api/dentists.php")
    .then((response) => response.json())
    .then((data) => {
      const select = document.getElementById("dentist-select")
      if (select) {
        select.innerHTML = '<option value="">Sélectionner un dentiste</option>'
        data.forEach((dentist) => {
          const option = document.createElement("option")
          option.value = dentist.id
          option.textContent = `Dr. ${dentist.first_name} ${dentist.last_name}`
          select.appendChild(option)
        })
      }
    })
    .catch((error) => console.error("Error loading dentists:", error))
}

function openModal(modalId) {
    const modal = document.getElementById(modalId)
    if (modal) {
        modal.classList.remove('hidden')
        document.body.style.overflow = 'hidden'
        
        // Trigger any required initialization
        if (modalId === 'appointmentModal') {
            initializeAppointmentModal()
        }
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId)
    if (modal) {
        modal.classList.add('hidden')
        document.body.style.overflow = 'auto'
    }
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div')
    toast.className = `fixed top-4 right-4 p-4 rounded-lg text-white z-50 ${
        type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500'
    }`
    toast.textContent = message
    document.body.appendChild(toast)
    setTimeout(() => toast.remove(), 3000)
}

function formatCurrency(amount) {
  return new Intl.NumberFormat("en-US", {
    style: "currency",
    currency: "USD",
  }).format(amount)
}

function formatDate(dateString) {
  return new Date(dateString).toLocaleDateString("en-US", {
    year: "numeric",
    month: "short",
    day: "numeric",
  })
}

function formatTime(timeString) {
  return new Date(`2000-01-01 ${timeString}`).toLocaleTimeString("en-US", {
    hour: "numeric",
    minute: "2-digit",
    hour12: true,
  })
}

function formatFileSize(bytes) {
  if (bytes >= 1073741824) {
    return (bytes / 1073741824).toFixed(2) + " GB"
  } else if (bytes >= 1048576) {
    return (bytes / 1048576).toFixed(2) + " MB"
  } else if (bytes >= 1024) {
    return (bytes / 1024).toFixed(2) + " KB"
  } else {
    return bytes + " bytes"
  }
}

// Make Swal available globally
window.Swal = Swal