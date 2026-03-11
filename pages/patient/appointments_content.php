<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#bookModal">Book Appointment</button>
<div id="appointments-list"></div>

<!-- Modal for booking -->
<div class="modal fade" id="bookModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="appointment-form">
                <div class="modal-header">
                    <h5 class="modal-title">Book Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    <div class="mb-3">
                        <label for="doctor_name" class="form-label">Doctor</label>
                        <input type="text" class="form-control" id="doctor_name" name="doctor_name" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="appointment_date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="appointment_date" name="appointment_date" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="appointment_time" class="form-label">Time</label>
                            <input type="time" class="form-control" id="appointment_time" name="appointment_time" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason</label>
                        <textarea class="form-control" id="reason" name="reason" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Book</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('appointment-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch('/medtrack/api/appointments.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('bookModal')).hide();
            loadAppointments();
        } else {
            alert('Error: ' + data.error);
        }
    });
});

function loadAppointments() {
    fetch('/medtrack/api/appointments.php')
        .then(response => response.text())
        .then(html => {
            document.getElementById('appointments-list').innerHTML = html;
        });
}
loadAppointments();
</script>