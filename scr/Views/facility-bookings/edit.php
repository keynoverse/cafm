<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3">Edit Facility Booking</h1>
        </div>
        <div class="col text-end">
            <a href="<?= $this->url('facility-bookings') ?>" class="btn btn-secondary">
                <i class='bx bx-arrow-back'></i> Back to Bookings
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="<?= $this->url("facility-bookings/{$booking['id']}/edit") ?>" data-validate>
                <?= $this->csrf_field() ?>

                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-3">Booking Information</h5>
                        
                        <div class="mb-3">
                            <label class="form-label">Facility <span class="text-danger">*</span></label>
                            <select name="facility_id" class="form-select <?= $this->hasError('facility_id') ? 'is-invalid' : '' ?>" required>
                                <option value="">Select a facility</option>
                                <?php foreach ($facilities as $facility): ?>
                                    <option value="<?= $facility['id'] ?>" 
                                        <?= $this->old('facility_id', $booking['facility_id']) == $facility['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($facility['name']) ?> 
                                        (<?= htmlspecialchars($facility['building']) ?> - 
                                        <?= htmlspecialchars($facility['floor']) ?> - 
                                        <?= htmlspecialchars($facility['room']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($this->hasError('facility_id')): ?>
                                <div class="invalid-feedback"><?= $this->error('facility_id') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Start Time <span class="text-danger">*</span></label>
                                    <input type="datetime-local" name="start_time" 
                                        class="form-control <?= $this->hasError('start_time') ? 'is-invalid' : '' ?>" 
                                        value="<?= $this->old('start_time', date('Y-m-d\TH:i', strtotime($booking['start_time']))) ?>" required>
                                    <?php if ($this->hasError('start_time')): ?>
                                        <div class="invalid-feedback"><?= $this->error('start_time') ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">End Time <span class="text-danger">*</span></label>
                                    <input type="datetime-local" name="end_time" 
                                        class="form-control <?= $this->hasError('end_time') ? 'is-invalid' : '' ?>" 
                                        value="<?= $this->old('end_time', date('Y-m-d\TH:i', strtotime($booking['end_time']))) ?>" required>
                                    <?php if ($this->hasError('end_time')): ?>
                                        <div class="invalid-feedback"><?= $this->error('end_time') ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Purpose <span class="text-danger">*</span></label>
                            <textarea name="purpose" class="form-control <?= $this->hasError('purpose') ? 'is-invalid' : '' ?>" 
                                rows="3" required><?= $this->old('purpose', $booking['purpose']) ?></textarea>
                            <?php if ($this->hasError('purpose')): ?>
                                <div class="invalid-feedback"><?= $this->error('purpose') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select <?= $this->hasError('status') ? 'is-invalid' : '' ?>" required>
                                <option value="pending" <?= $this->old('status', $booking['status']) === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="approved" <?= $this->old('status', $booking['status']) === 'approved' ? 'selected' : '' ?>>Approved</option>
                                <option value="rejected" <?= $this->old('status', $booking['status']) === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                <option value="cancelled" <?= $this->old('status', $booking['status']) === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                            <?php if ($this->hasError('status')): ?>
                                <div class="invalid-feedback"><?= $this->error('status') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class='bx bx-save'></i> Update Booking
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const facilitySelect = document.querySelector('select[name="facility_id"]');
    const startTimeInput = document.querySelector('input[name="start_time"]');
    const endTimeInput = document.querySelector('input[name="end_time"]');

    // Set minimum datetime to current time
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    startTimeInput.min = now.toISOString().slice(0, 16);
    endTimeInput.min = now.toISOString().slice(0, 16);

    // Update end time minimum when start time changes
    startTimeInput.addEventListener('change', function() {
        endTimeInput.min = this.value;
        if (endTimeInput.value && endTimeInput.value < this.value) {
            endTimeInput.value = this.value;
        }
    });

    // Check availability when form is submitted
    document.querySelector('form').addEventListener('submit', async function(e) {
        e.preventDefault();

        const facilityId = facilitySelect.value;
        const startTime = startTimeInput.value;
        const endTime = endTimeInput.value;

        try {
            const response = await fetch(`<?= $this->url('facility-bookings/check-availability') ?>?facility_id=${facilityId}&start_time=${startTime}&end_time=${endTime}&exclude_id=<?= $booking['id'] ?>`);
            const data = await response.json();

            if (!data.available) {
                alert('The facility is not available for the selected time period. Please choose a different time.');
                return;
            }

            this.submit();
        } catch (error) {
            console.error('Error checking availability:', error);
            alert('An error occurred while checking availability. Please try again.');
        }
    });
});
</script> 