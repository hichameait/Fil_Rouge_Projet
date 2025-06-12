document.addEventListener("DOMContentLoaded", () => {
  initializeAppointmentManagement()
})

function initializeAppointmentManagement() {
  // Load patient and service data when the appointment modal opens
  document.getElementById("newAppointmentBtn")?.addEventListener("click", () => {
    loadPatients()
    loadServices()
  })

  // Appointment form submission
  const appointmentForm = document.getElementById("appointmentForm")
  if (appointmentForm) {
    appointmentForm.addEventListener("submit", handleAppointmentFormSubmit)
  }

  document.querySelectorAll(".confirm-appointment-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
      const appointmentId = this.getAttribute("data-id")
      updateAppointmentStatus(appointmentId, "confirmed")
    })
  })

  document.querySelectorAll(".start-appointment-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
      const appointmentId = this.getAttribute("data-id")
      updateAppointmentStatus(appointmentId, "in_progress")
    })
  })

  document.querySelectorAll(".complete-appointment-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
      const appointmentId = this.getAttribute("data-id")
      updateAppointmentStatus(appointmentId, "completed")
    })
  })

  document.querySelectorAll(".cancel-appointment-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
      const appointmentId = this.getAttribute("data-id")
      cancelAppointment(appointmentId)
    })
  })

  // Appointment menu handling
  document.querySelectorAll(".appointment-menu-btn").forEach((btn) => {
    btn.addEventListener("click", function (e) {
      e.stopPropagation()
      const menu = this.nextElementSibling
      const appointmentId = this.getAttribute("data-id")

      // Close all other menus
      document.querySelectorAll(".appointment-menu").forEach((m) => {
        if (m !== menu) m.classList.add("hidden")
      })

      menu.classList.toggle("hidden")

      // Initialize menu item handlers
      const menuItems = menu.querySelectorAll("a")
      menuItems.forEach((item) => {
        item.addEventListener("click", function (e) {
          e.preventDefault()
          const action = this.getAttribute("data-action")
          handleMenuAction(action, appointmentId)
        })
      })
    })
  })

  // Close menus when clicking outside
  document.addEventListener("click", () => {
    document.querySelectorAll(".appointment-menu").forEach((menu) => {
      menu.classList.add("hidden")
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

  // Mini tooth chart in modal
  document.querySelectorAll(".tooth-mini").forEach((tooth) => {
    tooth.addEventListener("click", function () {
      this.classList.toggle("selected")
      this.classList.toggle("bg-blue-500")
      this.classList.toggle("text-white")
      updateSelectedTeethInput()
    })
  })
}

// Patients and Services Loading
function loadPatients() {
  fetch("api/patients.php")
    .then((response) => response.json())
    .then((patients) => {
      const select = document.getElementById("patient-select")
      if (select) {
        select.innerHTML = '<option value="">Select a patient</option>'
        patients.forEach((patient) => {
          const option = document.createElement("option")
          option.value = patient.id
          option.textContent = `${patient.first_name} ${patient.last_name}`
          select.appendChild(option)
        })
      }
    })
    .catch((error) => {
      console.error("Error loading patients:", error)
      showToast("Error loading patients", "error")
    })
}

function loadServices() {
  fetch("api/services.php")
    .then((response) => response.json())
    .then((services) => {
      const select = document.getElementById("service-select")
      if (select) {
        select.innerHTML = '<option value="">Select a service</option>'
        services.forEach((service) => {
          const option = document.createElement("option")
          option.value = service.id
          option.textContent = `${service.name} - $${service.price}`
          option.setAttribute("data-duration", service.duration)
          select.appendChild(option)
        })
      }
    })
    .catch((error) => {
      console.error("Error loading services:", error)
      showToast("Error loading services", "error")
    })
}

function handleAppointmentFormSubmit(e) {
  e.preventDefault()
  const formData = new FormData(e.target)
  
  fetch("api/appointments.php", {
    method: "POST",
    body: formData
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        showToast("Appointment created successfully", "success")
        closeModal("appointmentModal")
        setTimeout(() => location.reload(), 1000)
      } else {
        showToast(data.error || "Error creating appointment", "error")
      }
    })
    .catch((error) => {
      console.error("Error:", error)
      showToast("An error occurred. Please try again.", "error")
    })
}

function updateAppointmentStatus(appointmentId, status) {
  fetch("api/appointments.php", {
    method: "PUT",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      id: appointmentId,
      status: status,
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        window.showToast(`Appointment ${status.replace("_", " ")} successfully!`, "success")
        setTimeout(() => location.reload(), 1000)
      } else {
        window.showToast("Error: " + data.message, "error")
      }
    })
    .catch((error) => {
      console.error("Error:", error)
      window.showToast("An error occurred. Please try again.", "error")
    })
}

function cancelAppointment(appointmentId) {
  window.Swal.fire({
    title: "Cancel Appointment?",
    text: "Are you sure you want to cancel this appointment?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Yes, cancel it!",
  }).then((result) => {
    if (result.isConfirmed) {
      updateAppointmentStatus(appointmentId, "cancelled")
    }
  })
}

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

function handleMenuAction(action, appointmentId) {
  switch (action) {
    case "view":
      viewAppointmentDetails(appointmentId)
      break
    case "edit":
      editAppointment(appointmentId)
      break
    case "call":
      showActionModal("Call Patient", `<div class="mb-2">Call functionality for appointment #${appointmentId}.</div>`)
      break
    case "sms":
      showActionModal("Send SMS", `<div class="mb-2">SMS functionality for appointment #${appointmentId}.</div>`)
      break
    case "cancel":
      cancelAppointment(appointmentId)
      break
  }
}

// Populate the appointment edit form fields
function populateAppointmentForm(appointment) {
  document.getElementById("appointment-id").value = appointment.id || "";
  document.getElementById("appointment-date").value = appointment.appointment_date || "";
  document.getElementById("appointment-time").value = appointment.appointment_time || "";
  document.getElementById("duration").value = appointment.duration || "";
  document.getElementById("notes").value = appointment.notes || "";
  document.getElementById("status").value = appointment.status || "scheduled";
  // Set patient and service selects (wait for options to be loaded)
  setTimeout(() => {
    document.getElementById("patient-select").value = appointment.patient_id || "";
    document.getElementById("service-select").value = appointment.service_id || "";
  }, 100);
}

// Show generic action modal
function showActionModal(title, html) {
  document.getElementById("actionModalTitle").textContent = title;
  document.getElementById("actionModalContent").innerHTML = html;
  openModal("actionModal");
}

function viewAppointmentDetails(appointmentId) {
  fetch(`api/appointments.php?id=${appointmentId}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        showAppointmentDetails(data.appointment)
      } else {
        showToast(data.error || "Error loading appointment details", "error")
      }
    })
    .catch((error) => {
      console.error("Error:", error)
      showToast("Failed to load appointment details", "error")
    })
}

function editAppointment(appointmentId) {
  fetch(`api/appointments.php?id=${appointmentId}`)
    .then((response) => response.json())
    .then((data) => {
      // Try both data.appointment and data.data for compatibility
      const appointment = data.appointment || data.data;
      if (data.success && appointment) {
        // Ensure patients/services are loaded before populating the form
        Promise.all([loadPatients(), loadServices()]).then(() => {
          populateAppointmentForm(appointment);
          openModal("appointmentModal");
        });
      } else {
        showToast(data.error || "Error loading appointment", "error");
      }
    })
    .catch((error) => {
      console.error("Error:", error)
      showToast("Failed to load appointment", "error")
    })
}

function showAppointmentDetails(appointment) {
  Swal.fire({
    title: "Appointment Details",
    html: `
            <div class="text-left">
                <p class="mb-2"><strong>Patient:</strong> ${appointment.first_name} ${appointment.last_name}</p>
                <p class="mb-2"><strong>Service:</strong> ${appointment.service_name}</p>
                <p class="mb-2"><strong>Date:</strong> ${appointment.appointment_date}</p>
                <p class="mb-2"><strong>Time:</strong> ${appointment.appointment_time}</p>
                <p class="mb-2"><strong>Status:</strong> ${appointment.status}</p>
                <p class="mb-2"><strong>Dentist:</strong> Dr. ${appointment.dentist_first_name} ${appointment.dentist_last_name}</p>
                ${appointment.notes ? `<p class="mb-2"><strong>Notes:</strong> ${appointment.notes}</p>` : ""}
            </div>
        `,
    width: "500px",
    showCloseButton: true,
    showConfirmButton: false,
  })
}

// Declare showToast and Swal variables
window.showToast = (message, type) => {
  console.log(`Toast: ${message} (${type})`)
}

window.Swal = {
  fire: (options) => {
    console.log("Swal:", options)
    return new Promise((resolve) => {
      resolve({ isConfirmed: true })
    })
  },
}
