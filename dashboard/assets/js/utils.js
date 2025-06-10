function handleApiResponse(response, successMessage = '') {
    if (response.success) {
        showToast(successMessage || 'Operation completed successfully', 'success')
        setTimeout(() => location.reload(), 1000)
        return true
    } else {
        showToast('Error: ' + (response.message || 'Operation failed'), 'error')
        return false
    }
}

function confirmAction(title, text, icon = 'warning') {
    return Swal.fire({
        title,
        text,
        icon,
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes',
        cancelButtonText: 'No'
    })
}

function openModal(modalId) {
    const modal = document.getElementById(modalId)
    if (modal) {
        modal.classList.remove('hidden')
        document.body.style.overflow = 'hidden'
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
    Swal.fire({
        text: message,
        icon: type,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    })
}

function resetForm(formId) {
    const form = document.getElementById(formId)
    if (form) {
        form.reset()
        // Clear any hidden fields
        form.querySelectorAll('input[type="hidden"]').forEach(input => {
            input.value = ''
        })
    }
}

async function fetchData(url, options = {}) {
    try {
        const response = await fetch(url, {
            credentials: 'same-origin',
            ...options
        })
        const data = await response.json()
        if (!response.ok) {
            throw new Error(data.error || 'Server error')
        }
        return data
    } catch (error) {
        console.error('Fetch error:', error)
        showToast(error.message, 'error')
        throw error
    }
}