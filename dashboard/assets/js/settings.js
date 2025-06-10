document.addEventListener("DOMContentLoaded", () => {
  initializeSettings()
})

function initializeSettings() {
  // Tab switching
  document.querySelectorAll(".settings-tab-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
      const tabId = this.getAttribute("data-tab")
      switchSettingsTab(tabId)
    })
  })

  // Working hours checkboxes
  document.querySelectorAll(".day-closed-checkbox").forEach((checkbox) => {
    checkbox.addEventListener("change", function () {
      const day = this.id.replace("_closed", "")
      const timeInputs = document.querySelectorAll(`input[name="${day}_open"], input[name="${day}_close"]`)

      timeInputs.forEach((input) => {
        input.disabled = this.checked
        if (this.checked) {
          input.classList.add("bg-gray-100")
        } else {
          input.classList.remove("bg-gray-100")
        }
      })
    })
  })

  // User management buttons
  document.querySelectorAll(".edit-user-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
      const userId = this.getAttribute("data-id")
      editUser(userId)
    })
  })

  document.querySelectorAll(".toggle-user-status-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
      const userId = this.getAttribute("data-id")
      toggleUserStatus(userId)
    })
  })

  document.querySelectorAll(".delete-user-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
      const userId = this.getAttribute("data-id")
      deleteUser(userId)
    })
  })

  // Add user button
  const addUserBtn = document.getElementById("addUserBtn")
  if (addUserBtn) {
    addUserBtn.addEventListener("click", () => {
      openModal("addUserModal")
    })
  }
}

function switchSettingsTab(tabId) {
  // Update tab buttons
  document.querySelectorAll(".settings-tab-btn").forEach((btn) => {
    btn.classList.remove("border-blue-500", "text-blue-600")
    btn.classList.add("border-transparent", "text-gray-500")
  })

  const activeBtn = document.querySelector(`[data-tab="${tabId}"]`)
  if (activeBtn) {
    activeBtn.classList.remove("border-transparent", "text-gray-500")
    activeBtn.classList.add("border-blue-500", "text-blue-600")
  }

  // Update tab content
  document.querySelectorAll(".settings-tab-content").forEach((content) => {
    content.classList.add("hidden")
  })

  const activeContent = document.getElementById(`${tabId}-tab`)
  if (activeContent) {
    activeContent.classList.remove("hidden")
  }
}

function editUser(userId) {
  // Load user data and show edit modal
  fetch(`api/users.php?id=${userId}`)
    .then((response) => response.json())
    .then((user) => {
      populateUserForm(user)
      openModal("editUserModal")
    })
    .catch((error) => {
      console.error("Error loading user:", error)
      showToast("Error loading user data", "error")
    })
}

function toggleUserStatus(userId) {
  fetch("api/users.php", {
    method: "PUT",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      id: userId,
      action: "toggle_status",
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        showToast("User status updated successfully!", "success")
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

function deleteUser(userId) {
  Swal.fire({
    title: "Are you sure?",
    text: "This will permanently delete the user account!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      fetch("api/users.php", {
        method: "DELETE",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ id: userId }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            Swal.fire("Deleted!", "User has been deleted.", "success").then(() => location.reload())
          } else {
            Swal.fire("Error!", data.message, "error")
          }
        })
        .catch((error) => {
          Swal.fire("Error!", "Failed to delete user", "error")
        })
    }
  })
}

function openModal(modalId) {
  // Implementation for openModal
  const modal = document.getElementById(modalId)
  if (modal) {
    modal.classList.remove("hidden")
  }
}

function populateUserForm(user) {
  // Implementation for populateUserForm
  document.getElementById("editUserForm").elements["name"].value = user.name
  document.getElementById("editUserForm").elements["email"].value = user.email
}

function showToast(message, type) {
  // Implementation for showToast
  const toast = document.createElement("div")
  toast.className = `toast ${type}`
  toast.textContent = message
  document.body.appendChild(toast)
  setTimeout(() => toast.remove(), 3000)
}

const Swal = {
  fire: (options) => {
    // Implementation for Swal.fire
    return new Promise((resolve) => {
      const result = confirm(options.title + "\n" + options.text)
      resolve({ isConfirmed: result })
    })
  },
}
