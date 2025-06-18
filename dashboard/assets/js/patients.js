document.addEventListener("DOMContentLoaded", () => {
  initializePatientManagement()
})

function initializePatientManagement() {
  // Add patient button
  const addPatientBtn = document.getElementById("addPatientBtn")
  if (addPatientBtn) {
    addPatientBtn.addEventListener("click", () => {
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

function viewPatient(patientId) {
  // Redirect to patient details page or open modal
  window.location.href = `index.php?page=patients&action=view&id=${patientId}`
}

function editPatient(patientId) {
  // Load patient data and populate form
  fetch(`api/patients.php?id=${patientId}`)
    .then((response) => response.json())
    .then((patient) => {
      populatePatientForm(patient)
      openModal("patientModal")
    })
    .catch((error) => {
      console.error("Error loading patient:", error)
      showToast("Erreur lors du chargement des données du patient", "error")
    })
}

function populatePatientForm(patient) {
  document.getElementById("first-name").value = patient.first_name || ""
  document.getElementById("last-name").value = patient.last_name || ""
  document.getElementById("email").value = patient.email || ""
  document.getElementById("phone").value = patient.phone || ""
  document.getElementById("date-of-birth").value = patient.date_of_birth || ""
  document.getElementById("gender").value = patient.gender || ""
  document.getElementById("address").value = patient.address || ""
  document.getElementById("medical-history").value = patient.medical_history || ""

  // Add patient ID to form for updates
  const form = document.getElementById("patientForm")
  let idInput = form.querySelector('input[name="patient_id"]')
  if (!idInput) {
    idInput = document.createElement("input")
    idInput.type = "hidden"
    idInput.name = "patient_id"
    form.appendChild(idInput)
  }
  idInput.value = patient.id
}

function scheduleAppointmentForPatient(patientId) {
  // Pre-select patient in appointment modal
  openModal("appointmentModal")
  loadPatients().then(() => {
    const patientSelect = document.getElementById("patient-select")
    if (patientSelect) {
      patientSelect.value = patientId
    }
  })
}

function deletePatient(patientId) {
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
  const isUpdate = formData.has("patient_id")

  const url = "api/patients.php"
  const method = isUpdate ? "PUT" : "POST"

  fetch(url, {
    method: method,
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        showToast(isUpdate ? "Patient modifié avec succès !" : "Patient ajouté avec succès !", "success")
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

// Declare variables before using them
function openModal(modalId) {
  // Implementation for openModal
  const modal = document.getElementById(modalId)
  if (modal) {
    modal.style.display = "block"
  }
}

function showToast(message, type) {
  // Implementation for showToast
  const toast = document.createElement("div")
  toast.className = `toast ${type}`
  toast.textContent = message
  document.body.appendChild(toast)
  setTimeout(() => {
    document.body.removeChild(toast)
  }, 3000)
}

function loadPatients() {
  // Implementation for loadPatients
  return fetch("api/patients.php")
    .then((response) => response.json())
    .then((patients) => {
      const patientSelect = document.getElementById("patient-select")
      if (patientSelect) {
        patients.forEach((patient) => {
          const option = document.createElement("option")
          option.value = patient.id
          option.textContent = `${patient.first_name} ${patient.last_name}`
          patientSelect.appendChild(option)
        })
      }
    })
    .catch((error) => {
      console.error("Error loading patients:", error)
      showToast("Error loading patients", "error")
    })
}

function closeModal(modalId) {
  // Implementation for closeModal
  const modal = document.getElementById(modalId)
  if (modal) {
    modal.style.display = "none"
  }
}

// Declare Swal for SweetAlert2
const Swal = window.Swal
