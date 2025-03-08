<?php $this->layout('layouts/app', ['title' => 'Edit Carbon Emission Record']) ?>

<div class="container-fluid px-4">
    <!-- Header -->
    <div class="mb-4">
        <h4 class="mb-0">Edit Carbon Emission Record</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= url('energy') ?>">Energy Management</a></li>
                <li class="breadcrumb-item"><a href="<?= url('energy/carbon') ?>">Carbon Footprint</a></li>
                <li class="breadcrumb-item">
                    <a href="<?= url("energy/carbon/{$emission['id']}") ?>">Details</a>
                </li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form id="emissionForm" action="<?= url("energy/carbon/{$emission['id']}") ?>" method="POST" 
                          enctype="multipart/form-data">
                        <input type="hidden" name="_method" value="PUT">

                        <!-- Source Information -->
                        <div class="mb-4">
                            <h5>Source Information</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="source_type" class="form-label">Emission Source *</label>
                                    <select class="form-select" id="source_type" name="source_type" required>
                                        <option value="">Select Source Type</option>
                                        <option value="electricity" <?= $emission['source_type'] === 'electricity' ? 'selected' : '' ?>>
                                            Electricity
                                        </option>
                                        <option value="gas" <?= $emission['source_type'] === 'gas' ? 'selected' : '' ?>>
                                            Natural Gas
                                        </option>
                                        <option value="water" <?= $emission['source_type'] === 'water' ? 'selected' : '' ?>>
                                            Water
                                        </option>
                                        <option value="transportation" <?= $emission['source_type'] === 'transportation' ? 'selected' : '' ?>>
                                            Transportation
                                        </option>
                                        <option value="waste" <?= $emission['source_type'] === 'waste' ? 'selected' : '' ?>>
                                            Waste
                                        </option>
                                        <option value="other" <?= $emission['source_type'] === 'other' ? 'selected' : '' ?>>
                                            Other
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="asset_id" class="form-label">Related Asset</label>
                                    <select class="form-select" id="asset_id" name="asset_id">
                                        <option value="">Select Asset (Optional)</option>
                                        <?php foreach ($assets as $asset): ?>
                                            <option value="<?= $asset['id'] ?>" 
                                                    <?= $emission['asset_id'] === $asset['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($asset['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Emission Details -->
                        <div class="mb-4">
                            <h5>Emission Details</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="emission_amount" class="form-label">Amount *</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="emission_amount" 
                                               name="emission_amount" step="0.01" required
                                               value="<?= htmlspecialchars($emission['emission_amount']) ?>">
                                        <select class="form-select" id="emission_unit" name="emission_unit" required>
                                            <option value="tCO2e" <?= $emission['emission_unit'] === 'tCO2e' ? 'selected' : '' ?>>
                                                tCO2e
                                            </option>
                                            <option value="kgCO2e" <?= $emission['emission_unit'] === 'kgCO2e' ? 'selected' : '' ?>>
                                                kgCO2e
                                            </option>
                                            <option value="kWh" <?= $emission['emission_unit'] === 'kWh' ? 'selected' : '' ?>>
                                                kWh
                                            </option>
                                            <option value="m3" <?= $emission['emission_unit'] === 'm3' ? 'selected' : '' ?>>
                                                m³
                                            </option>
                                            <option value="L" <?= $emission['emission_unit'] === 'L' ? 'selected' : '' ?>>
                                                L
                                            </option>
                                            <option value="km" <?= $emission['emission_unit'] === 'km' ? 'selected' : '' ?>>
                                                km
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="calculation_method" class="form-label">Calculation Method *</label>
                                    <select class="form-select" id="calculation_method" name="calculation_method" required>
                                        <option value="">Select Method</option>
                                        <option value="direct_measurement" 
                                                <?= $emission['calculation_method'] === 'direct_measurement' ? 'selected' : '' ?>>
                                            Direct Measurement
                                        </option>
                                        <option value="emission_factors"
                                                <?= $emission['calculation_method'] === 'emission_factors' ? 'selected' : '' ?>>
                                            Emission Factors
                                        </option>
                                        <option value="energy_bills"
                                                <?= $emission['calculation_method'] === 'energy_bills' ? 'selected' : '' ?>>
                                            Energy Bills
                                        </option>
                                        <option value="distance_based"
                                                <?= $emission['calculation_method'] === 'distance_based' ? 'selected' : '' ?>>
                                            Distance-Based
                                        </option>
                                        <option value="waste_analysis"
                                                <?= $emission['calculation_method'] === 'waste_analysis' ? 'selected' : '' ?>>
                                            Waste Analysis
                                        </option>
                                        <option value="other"
                                                <?= $emission['calculation_method'] === 'other' ? 'selected' : '' ?>>
                                            Other
                                        </option>
                                    </select>
                                </div>
                                <div class="col-12" id="methodDetailsContainer" 
                                     style="display: <?= $emission['calculation_method'] === 'other' ? 'block' : 'none' ?>;">
                                    <label for="calculation_details" class="form-label">Calculation Details</label>
                                    <textarea class="form-control" id="calculation_details" name="calculation_details" 
                                              rows="3" placeholder="Explain your calculation method..."
                                    ><?= htmlspecialchars($emission['calculation_details']) ?></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Recording Period -->
                        <div class="mb-4">
                            <h5>Recording Period</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="recording_period_start" class="form-label">Start Date *</label>
                                    <input type="date" class="form-control" id="recording_period_start" 
                                           name="recording_period_start" required
                                           value="<?= date('Y-m-d', strtotime($emission['recording_period_start'])) ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="recording_period_end" class="form-label">End Date *</label>
                                    <input type="date" class="form-control" id="recording_period_end" 
                                           name="recording_period_end" required
                                           value="<?= date('Y-m-d', strtotime($emission['recording_period_end'])) ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="mb-4">
                            <h5>Additional Information</h5>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3" 
                                              placeholder="Any additional notes or context..."
                                    ><?= htmlspecialchars($emission['notes']) ?></textarea>
                                </div>
                                <div class="col-12">
                                    <label for="attachments" class="form-label">New Attachments</label>
                                    <input type="file" class="form-control" id="attachments" name="attachments[]" 
                                           multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.png">
                                    <div class="form-text">
                                        Supported files: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG (max 5MB each)
                                    </div>
                                </div>
                                <?php if (!empty($attachments)): ?>
                                    <div class="col-12">
                                        <label class="form-label">Current Attachments</label>
                                        <div class="row g-2">
                                            <?php foreach ($attachments as $attachment): ?>
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center p-2 border rounded">
                                                        <i class="bx bx-file me-2 fs-4"></i>
                                                        <div class="flex-grow-1">
                                                            <div class="text-truncate">
                                                                <?= htmlspecialchars($attachment['filename']) ?>
                                                            </div>
                                                            <small class="text-muted">
                                                                <?= formatFileSize($attachment['size']) ?>
                                                            </small>
                                                        </div>
                                                        <div class="d-flex gap-2">
                                                            <a href="<?= url("energy/carbon/attachments/{$attachment['id']}") ?>" 
                                                               class="btn btn-sm btn-outline-primary" download>
                                                                <i class="bx bx-download"></i>
                                                            </a>
                                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                                    onclick="removeAttachment(<?= $attachment['id'] ?>)">
                                                                <i class="bx bx-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <hr>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?= url("energy/carbon/{$emission['id']}") ?>" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Help Sidebar -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Recording Guidelines</h5>
                    <div class="mb-4">
                        <h6>Emission Sources</h6>
                        <ul class="list-unstyled">
                            <li><i class="bx bx-bulb text-primary"></i> Electricity: Record consumption in kWh</li>
                            <li><i class="bx bx-flame text-danger"></i> Gas: Record consumption in m³</li>
                            <li><i class="bx bx-water text-info"></i> Water: Record consumption in m³ or L</li>
                            <li><i class="bx bx-car text-warning"></i> Transportation: Record distance in km</li>
                            <li><i class="bx bx-trash text-secondary"></i> Waste: Record weight in kg</li>
                        </ul>
                    </div>
                    <div class="mb-4">
                        <h6>Calculation Methods</h6>
                        <ul class="list-unstyled">
                            <li><i class="bx bx-check"></i> Direct Measurement: Use meter readings</li>
                            <li><i class="bx bx-check"></i> Emission Factors: Use standard conversion factors</li>
                            <li><i class="bx bx-check"></i> Energy Bills: Extract data from utility bills</li>
                            <li><i class="bx bx-check"></i> Distance-Based: Calculate based on travel distance</li>
                            <li><i class="bx bx-check"></i> Waste Analysis: Use waste composition data</li>
                        </ul>
                    </div>
                    <div>
                        <h6>Need Help?</h6>
                        <p class="mb-0">
                            <a href="#" class="text-primary" data-bs-toggle="modal" data-bs-target="#helpModal">
                                <i class="bx bx-help-circle"></i> View Calculation Guide
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Help Modal -->
<div class="modal fade" id="helpModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Carbon Emissions Calculation Guide</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Add detailed calculation guide content here -->
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('emissionForm');
    const sourceTypeSelect = document.getElementById('source_type');
    const emissionUnitSelect = document.getElementById('emission_unit');
    const calculationMethodSelect = document.getElementById('calculation_method');
    const methodDetailsContainer = document.getElementById('methodDetailsContainer');
    const startDateInput = document.getElementById('recording_period_start');
    const endDateInput = document.getElementById('recording_period_end');

    // Update emission units based on source type
    sourceTypeSelect.addEventListener('change', function() {
        const units = {
            'electricity': ['kWh', 'tCO2e', 'kgCO2e'],
            'gas': ['m3', 'tCO2e', 'kgCO2e'],
            'water': ['m3', 'L'],
            'transportation': ['km', 'tCO2e', 'kgCO2e'],
            'waste': ['kg', 'tCO2e', 'kgCO2e'],
            'other': ['tCO2e', 'kgCO2e']
        };

        const selectedUnits = units[this.value] || ['tCO2e', 'kgCO2e'];
        const currentUnit = emissionUnitSelect.value;
        
        emissionUnitSelect.innerHTML = selectedUnits.map(unit => 
            `<option value="${unit}" ${unit === currentUnit ? 'selected' : ''}>${unit}</option>`
        ).join('');
    });

    // Show/hide calculation details based on method
    calculationMethodSelect.addEventListener('change', function() {
        methodDetailsContainer.style.display = 
            this.value === 'other' ? 'block' : 'none';
    });

    // Set minimum date for end date based on start date
    startDateInput.addEventListener('change', function() {
        endDateInput.min = this.value;
        if (endDateInput.value && endDateInput.value < this.value) {
            endDateInput.value = this.value;
        }
    });

    // Form validation
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Validate dates
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);
        const today = new Date();

        if (startDate > today || endDate > today) {
            alert('Recording dates cannot be in the future');
            return;
        }

        if (startDate > endDate) {
            alert('End date must be after start date');
            return;
        }

        // Validate file sizes
        const attachments = document.getElementById('attachments').files;
        const maxSize = 5 * 1024 * 1024; // 5MB
        for (let file of attachments) {
            if (file.size > maxSize) {
                alert(`File ${file.name} exceeds 5MB limit`);
                return;
            }
        }

        // If all validations pass, submit the form
        this.submit();
    });
});

function removeAttachment(attachmentId) {
    if (confirm('Are you sure you want to remove this attachment?')) {
        fetch(`<?= url('energy/carbon/attachments') ?>/${attachmentId}`, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(response => {
            if (response.ok) {
                window.location.reload();
            } else {
                alert('Failed to remove attachment');
            }
        }).catch(error => {
            console.error('Error:', error);
            alert('Failed to remove attachment');
        });
    }
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}
</script>

<style>
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.list-unstyled li {
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.list-unstyled i {
    font-size: 1.25rem;
}

@media (max-width: 768px) {
    .col-md-4 {
        margin-top: 1.5rem;
    }
}
</style> 