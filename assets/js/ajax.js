/**
 * AJAX Helpers for MedTrack
 * 
 * - loadAppointments() – fetches and displays upcoming appointments (dashboard)
 * - bookAppointment() – submits appointment form via fetch
 * - payBill() – marks a bill as paid
 */

// ---------- Appointments ----------
window.loadAppointments = function() {
    fetch('/medtrack/api/appointments.php')
        .then(response => {
            if (!response.ok) throw new Error('Network error');
            return response.text();
        })
        .then(html => {
            const container = document.getElementById('appointment-list');
            if (container) container.innerHTML = html;
        })
        .catch(err => console.error('Failed to load appointments:', err));
};

// Set up appointment booking (to be called after DOM is ready)
function initAppointmentBooking() {
    const form = document.getElementById('appointment-form');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(form);

        fetch('/medtrack/api/appointments.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('bookModal'));
                if (modal) modal.hide();
                
                // Reload appointments list
                if (window.loadAppointments) window.loadAppointments();
                
                // Show success toast (optional)
                showNotification('Appointment booked successfully', 'success');
            } else {
                alert('Error: ' + (data.error || 'Unknown error'));
            }
        })
        .catch(err => {
            console.error('Booking error:', err);
            alert('Could not book appointment. Please try again.');
        });
    });
}

// ---------- Bill Payment ----------
window.payBill = function(billId, csrfToken) {
    if (!confirm('Mark this bill as paid?')) return;

    const formData = new URLSearchParams();
    formData.append('bill_id', billId);
    formData.append('csrf_token', csrfToken);

    fetch('/medtrack/api/pay_bill.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: formData.toString()
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the row without reload
            const row = document.getElementById('bill-row-' + billId);
            if (row) {
                row.querySelector('.bill-status').innerText = 'paid';
                row.querySelector('.bill-paid-date').innerText = data.paid_date;
                const payBtn = row.querySelector('.pay-bill');
                if (payBtn) payBtn.remove();
            }
            showNotification('Bill marked as paid', 'success');
        } else {
            alert('Error: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(err => {
        console.error('Payment error:', err);
        alert('Could not process payment. Please try again.');
    });
};

// ---------- Utility: Show temporary notification ----------
function showNotification(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
    alertDiv.style.zIndex = '9999';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(alertDiv);
    setTimeout(() => {
        alertDiv.classList.remove('show');
        setTimeout(() => alertDiv.remove(), 300);
    }, 4000);
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    initAppointmentBooking();
    
    // Attach pay bill handlers (they might be added dynamically)
    document.body.addEventListener('click', function(e) {
        if (e.target.classList.contains('pay-bill') || e.target.closest('.pay-bill')) {
            const btn = e.target.closest('.pay-bill');
            const billId = btn.dataset.billId;
            const csrfToken = btn.dataset.csrf; // you'll need to set this in HTML
            if (billId && csrfToken) {
                e.preventDefault();
                window.payBill(billId, csrfToken);
            }
        }
    });
});