<?php $this->layout('layouts/app', ['title' => 'Record Energy Consumption']) ?>

<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('energy') ?>">Energy Management</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('energy/consumption') ?>">Consumption</a></li>
                    <li class="breadcrumb-item active">Record Reading</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Record Energy Consumption</h5>
        </div>
        <div class="card-body">
            <form action="<?= url('energy/consumption/create') ?>" method="POST" id="consumptionForm">
                <?= csrf_field() ?>

                <!-- Asset Selection -->
                <div class="mb-3">
                    <label for="asset_id" class="form-label required">Equipment/Asset</label>
                    <select class="form-select" id="asset_id" name="asset_id" required>
                        <option value="">Select Equipment</option>
                        <?php foreach ($assets as $asset): ?>
                            <option value="<?= $asset['id'] ?>" <?= old('asset_id') == $asset['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($asset['name']) ?> 
                                (<?= $asset['asset_tag'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-text">Select the equipment or location for this reading</div>
                </div>

                <!-- Meter ID -->
                <div class="mb-3">
                    <label for="meter_id" class="form-label required">Meter ID</label>
                    <input type="text" class="form-control" id="meter_id" name="meter_id" 
                           required maxlength="50" value="<?= old('meter_id') ?>"
                           placeholder="Enter meter identification number">
                    <div class="form-text">Enter the unique meter identification number</div>
                </div>

                <!-- Reading Type -->
                <div class="mb-3">
                    <label for="reading_type" class="form-label required">Reading Type</label>
                    <select class="form-select" id="reading_type" name="reading_type" required>
                        <option value="">Select Type</option>
                        <option value="electricity" <?= old('reading_type') === 'electricity' ? 'selected' : '' ?>>Electricity</option>
                        <option value="gas" <?= old('reading_type') === 'gas' ? 'selected' : '' ?>>Gas</option>
                        <option value="water" <?= old('reading_type') === 'water' ? 'selected' : '' ?>>Water</option>
                        <option value="other" <?= old('reading_type') === 'other' ? 'selected' : '' ?>>Other</option>
                    </select>
                    <div class="form-text">Select the type of consumption being recorded</div>
                </div>

                <!-- Reading Value -->
                <div class="mb-3">
                    <label for="reading_value" class="form-label required">Reading Value</label>
                    <div class="input-group">
                        <input type="number" class="form-control" id="reading_value" name="reading_value" 
                               required min="0" step="0.01" value="<?= old('reading_value') ?>"
                               placeholder="Enter reading value">
                        <select class="form-select" id="reading_unit" name="reading_unit" required style="max-width: 100px;">
                            <option value="">Unit</option>
                            <option value="kWh" <?= old('reading_unit') === 'kWh' ? 'selected' : '' ?>>kWh</option>
                            <option value="m³" <?= old('reading_unit') === 'm³' ? 'selected' : '' ?>>m³</option>
                            <option value="L" <?= old('reading_unit') === 'L' ? 'selected' : '' ?>>L</option>
                            <option value="other" <?= old('reading_unit') === 'other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                    <div class="form-text">Enter the consumption reading value and select the appropriate unit</div>
                </div>

                <!-- Reading Date -->
                <div class="mb-3">
                    <label for="reading_date" class="form-label required">Reading Date & Time</label>
                    <input type="datetime-local" class="form-control" id="reading_date" name="reading_date" 
                           required value="<?= old('reading_date') ?? date('Y-m-d\TH:i') ?>">
                    <div class="form-text">Select the date and time when the reading was taken</div>
                </div>

                <!-- Peak Hour -->
                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="peak_hour" 
                               name="peak_hour" value="1" <?= old('peak_hour') ? 'checked' : '' ?>>
                        <label class="form-check-label" for="peak_hour">
                            Peak Hour Reading
                        </label>
                    </div>
                    <div class="form-text">Check if this reading was taken during peak hours</div>
                </div>

                <!-- Previous Reading -->
                <div class="mb-3">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2">Previous Reading</h6>
                            <div id="previousReading">
                                <?php if (isset($previousReading)): ?>
                                    <p class="mb-1">
                                        Value: <?= number_format($previousReading['reading_value'], 2) ?> 
                                        <?= htmlspecialchars($previousReading['reading_unit']) ?>
                                    </p>
                                    <p class="mb-1">
                                        Date: <?= date('M j, Y g:i A', strtotime($previousReading['reading_date'])) ?>
                                    </p>
                                    <p class="mb-0">
                                        Recorded by: <?= htmlspecialchars($previousReading['recorded_by']) ?>
                                    </p>
                                <?php else: ?>
                                    <p class="mb-0 text-muted">No previous reading found</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="mb-3">
                    <label for="notes" class="form-label">Additional Notes</label>
                    <textarea class="form-control" id="notes" name="notes" 
                              rows="3"><?= old('notes') ?></textarea>
                    <div class="form-text">Any additional notes or comments about this reading</div>
                </div>

                <!-- Submit Buttons -->
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save"></i> Save Reading
                    </button>
                    <a href="<?= url('energy/consumption') ?>" class="btn btn-outline-secondary">
                        <i class="bx bx-x"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('consumptionForm');
    const assetSelect = document.getElementById('asset_id');
    const readingTypeSelect = document.getElementById('reading_type');
    const readingUnitSelect = document.getElementById('reading_unit');
    const readingValueInput = document.getElementById('reading_value');
    const readingDateInput = document.getElementById('reading_date');

    // Form validation
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Required fields validation
        ['asset_id', 'meter_id', 'reading_type', 'reading_value', 'reading_unit', 'reading_date']
            .forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });

        // Reading value validation
        const readingValue = parseFloat(readingValueInput.value);
        if (isNaN(readingValue) || readingValue < 0) {
            readingValueInput.classList.add('is-invalid');
            isValid = false;
        }

        // Date validation
        const readingDate = new Date(readingDateInput.value);
        const now = new Date();
        if (readingDate > now) {
            readingDateInput.classList.add('is-invalid');
            alert('Reading date cannot be in the future');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields correctly');
        }
    });

    // Clear validation on input
    form.querySelectorAll('input, select, textarea').forEach(element => {
        element.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });

    // Update units based on reading type
    readingTypeSelect.addEventListener('change', function() {
        readingUnitSelect.innerHTML = '<option value="">Unit</option>';
        
        const units = {
            'electricity': [
                { value: 'kWh', label: 'kWh' },
                { value: 'MWh', label: 'MWh' }
            ],
            'gas': [
                { value: 'm³', label: 'm³' },
                { value: 'ft³', label: 'ft³' }
            ],
            'water': [
                { value: 'm³', label: 'm³' },
                { value: 'L', label: 'L' },
                { value: 'gal', label: 'gal' }
            ],
            'other': [
                { value: 'other', label: 'Other' }
            ]
        };

        if (this.value && units[this.value]) {
            units[this.value].forEach(unit => {
                const option = new Option(unit.label, unit.value);
                readingUnitSelect.add(option);
            });
        }
    });

    // Load previous reading when asset and type are selected
    function loadPreviousReading() {
        const assetId = assetSelect.value;
        const readingType = readingTypeSelect.value;
        
        if (assetId && readingType) {
            fetch(`<?= url('energy/consumption/previous-reading') ?>?asset_id=${assetId}&type=${readingType}`)
                .then(response => response.json())
                .then(data => {
                    const previousReadingDiv = document.getElementById('previousReading');
                    if (data.reading) {
                        previousReadingDiv.innerHTML = `
                            <p class="mb-1">
                                Value: ${parseFloat(data.reading.reading_value).toFixed(2)} 
                                ${data.reading.reading_unit}
                            </p>
                            <p class="mb-1">
                                Date: ${new Date(data.reading.reading_date).toLocaleString()}
                            </p>
                            <p class="mb-0">
                                Recorded by: ${data.reading.recorded_by}
                            </p>
                        `;
                    } else {
                        previousReadingDiv.innerHTML = '<p class="mb-0 text-muted">No previous reading found</p>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    }

    assetSelect.addEventListener('change', loadPreviousReading);
    readingTypeSelect.addEventListener('change', loadPreviousReading);

    // Set maximum date/time to now
    readingDateInput.max = new Date().toISOString().slice(0, 16);
});
</script>

<style>
.required:after {
    content: " *";
    color: red;
}

.form-label {
    font-weight: 500;
}

.is-invalid {
    border-color: #dc3545;
}

.is-invalid + .form-text {
    color: #dc3545;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

@media (max-width: 768px) {
    .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }

    .input-group {
        flex-direction: column;
    }

    .input-group > * {
        width: 100%;
        margin-bottom: 0.5rem;
    }

    .input-group > :last-child {
        margin-bottom: 0;
    }
}
</style> 