document.addEventListener('DOMContentLoaded', () => {
    initializeButtons()
    initializeFormSubmits()
    initializeModals()
})

function initializeButtons() {
    // Appointment buttons
    const appointmentButtons = {
        'newAppointmentBtn': () => handleNewAppointment(),
        'quickScheduleBtn': () => handleNewAppointment(),
        'scheduleFirstBtn': () => handleNewAppointment(),
        'calendarViewBtn': () => toggleCalendarView()
    }

    // Patient buttons
    const patientButtons = {
        'addPatientBtn': () => handleNewPatient(),
        'view-patient-btn': (id) => viewPatient(id),
        'edit-patient-btn': (id) => editPatient(id),
        'schedule-appointment-btn': (id) => scheduleAppointmentForPatient(id),
        'delete-patient-btn': (id) => deletePatient(id)
    }

    // Invoice buttons
    const invoiceButtons = {
        'createInvoiceBtn': () => handleNewInvoice(),
        'view-invoice-btn': (id) => viewInvoice(id),
        'edit-invoice-btn': (id) => editInvoice(id),
        'delete-invoice-btn': (id) => deleteInvoice(id)
    }

    // Register all button event listeners
    Object.entries(appointmentButtons).forEach(([id, handler]) => {
        const button = document.getElementById(id)
        if (button) {
            button.addEventListener('click', handler)
        }
    })

    // Register class-based button listeners
    Object.entries(patientButtons).forEach(([className, handler]) => {
        document.querySelectorAll(`.${className}`).forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-id')
                handler(id)
            })
        })
    })
}

function initializeFormSubmits() {
    const forms = {
        'appointmentForm': handleAppointmentSubmit,
        'patientForm': handlePatientSubmit,
        'invoiceForm': handleInvoiceSubmit,
        'documentForm': handleDocumentSubmit
    }

    Object.entries(forms).forEach(([formId, handler]) => {
        const form = document.getElementById(formId)
        if (form) {
            form.addEventListener('submit', handler)
        }
    })
}

function handleNewAppointment() {
    resetAppointmentForm()
    openModal('appointmentModal')
    loadPatients()
    loadServices()
    loadDentists()
}

function handleNewPatient() {
    resetPatientForm()
    openModal('patientModal')
}

function handleNewInvoice() {
    resetInvoiceForm()
    openModal('invoiceModal')
    loadPatientsForInvoice()
    generateInvoiceNumber()
}