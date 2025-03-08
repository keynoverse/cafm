<?php $this->layout('layouts/app', ['title' => 'Record Carbon Emissions']) ?>

<div class="container-fluid px-4">
    <!-- Header -->
    <div class="mb-4">
        <h4 class="mb-0">Record Carbon Emissions</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= url('energy') ?>">Energy Management</a></li>
                <li class="breadcrumb-item"><a href="<?= url('energy/carbon') ?>">Carbon Footprint</a></li>
                <li class="breadcrumb-item active">Record Emissions</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form id="emissionForm" action="<?= url('energy/carbon') ?>" method="POST" enctype="multipart/form-data">
                        <!-- Source Information -->
                        <div class="mb-4">
                            <h5>Source Information</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="source_type" class="form-label">Emission Source *</label>
                                    <select class="form-select" id="source_type" name="source_type" required>
                                        <option value="">Select Source Type</option>
                                        <option value="electricity">Electricity</option>
                                        <option value="gas">Natural Gas</option>
                                        <option value="water">Water</option>
                                        <option value="transportation">Transportation</option>
                                        <option value="waste">Waste</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="asset_id" class="form-label">Related Asset</label>
                                    <select class="form-select" id="asset_id" name="asset_id">
                                        <option value="">Select Asset (Optional)</option>
                                        <?php foreach ($assets as $asset): ?>
                                            <option value="<?= $asset['id'] ?>"><?= htmlspecialchars($asset['name']) ?></option>
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
                                               name="emission_amount" step="0.01" required>
                                        <select class="form-select" id="emission_unit" name="emission_unit" required>
                                            <option value="tCO2e">tCO2e</option>
                                            <option value="kgCO2e">kgCO2e</option>
                                            <option value="kWh">kWh</option>
                                            <option value="m3">m³</option>
                                            <option value="L">L</option>
                                            <option value="km">km</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="calculation_method" class="form-label">Calculation Method *</label>
                                    <select class="form-select" id="calculation_method" name="calculation_method" required>
                                        <option value="">Select Method</option>
                                        <option value="direct_measurement">Direct Measurement</option>
                                        <option value="emission_factors">Emission Factors</option>
                                        <option value="energy_bills">Energy Bills</option>
                                        <option value="distance_based">Distance-Based</option>
                                        <option value="waste_analysis">Waste Analysis</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div class="col-12" id="methodDetailsContainer" style="display: none;">
                                    <label for="calculation_details" class="form-label">Calculation Details</label>
                                    <textarea class="form-control" id="calculation_details" name="calculation_details" 
                                              rows="3" placeholder="Explain your calculation method..."></textarea>
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
                                           name="recording_period_start" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="recording_period_end" class="form-label">End Date *</label>
                                    <input type="date" class="form-control" id="recording_period_end" 
                                           name="recording_period_end" required>
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
                                              placeholder="Any additional notes or context..."></textarea>
                                </div>
                                <div class="col-12">
                                    <label for="attachments" class="form-label">Attachments</label>
                                    <input type="file" class="form-control" id="attachments" name="attachments[]" 
                                           multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.png">
                                    <div class="form-text">
                                        Supported files: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG (max 5MB each)
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?= url('energy/carbon') ?>" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Record Emissions</button>
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
        emissionUnitSelect.innerHTML = selectedUnits.map(unit => 
            `<option value="${unit}">${unit}</option>`
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