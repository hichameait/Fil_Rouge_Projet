async function handleAppointmentSubmit(e) {
    e.preventDefault()
    
    try {
        const formData = new FormData(e.target)
        const response = await fetch('api/appointments.php', {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
        
        const data = await response.json()
        if (data.success) {
            showToast('Appointment scheduled successfully!', 'success')
            closeModal('appointmentModal')
            setTimeout(() => location.reload(), 1000)
        } else {
            showToast(data.error || 'Failed to schedule appointment', 'error')
        }
    } catch (error) {
        console.error('Error:', error)
        showToast('An error occurred. Please try again.', 'error')
    }
}

async function handlePatientSubmit(e) {
    e.preventDefault()
    
    try {
        const formData = new FormData(e.target)
        const isUpdate = formData.get('patient_id')
        
        const response = await fetch('api/patients.php', {
            method: isUpdate ? 'PUT' : 'POST',
            body: isUpdate ? JSON.stringify(Object.fromEntries(formData)) : formData,
            headers: isUpdate ? {
                'Content-Type': 'application/json'
            } : undefined,
            credentials: 'same-origin'
        })
        
        const data = await response.json()
        if (data.success) {
            showToast(`Patient ${isUpdate ? 'updated' : 'added'} successfully!`, 'success')
            closeModal('patientModal')
            setTimeout(() => location.reload(), 1000)
        } else {
            showToast(data.error || `Failed to ${isUpdate ? 'update' : 'add'} patient`, 'error')
        }
    } catch (error) {
        console.error('Error:', error)
        showToast('An error occurred. Please try again.', 'error')
    }
}