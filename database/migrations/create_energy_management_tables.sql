-- Energy Consumption Records
CREATE TABLE energy_consumption (
    id INT PRIMARY KEY AUTO_INCREMENT,
    asset_id INT,
    meter_id VARCHAR(50),
    reading_value DECIMAL(10,2),
    reading_unit VARCHAR(20),
    reading_type ENUM('electricity', 'gas', 'water', 'other'),
    reading_date DATETIME,
    peak_hour BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (asset_id) REFERENCES assets(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (updated_by) REFERENCES users(id)
);

-- Utility Bills
CREATE TABLE utility_bills (
    id INT PRIMARY KEY AUTO_INCREMENT,
    bill_number VARCHAR(50),
    utility_type ENUM('electricity', 'gas', 'water', 'other'),
    billing_period_start DATE,
    billing_period_end DATE,
    amount DECIMAL(10,2),
    consumption_amount DECIMAL(10,2),
    consumption_unit VARCHAR(20),
    supplier_id INT,
    payment_status ENUM('pending', 'paid', 'overdue', 'disputed'),
    payment_date DATE,
    due_date DATE,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (updated_by) REFERENCES users(id)
);

-- Carbon Footprint Records
CREATE TABLE carbon_footprint (
    id INT PRIMARY KEY AUTO_INCREMENT,
    source_type ENUM('electricity', 'gas', 'water', 'transportation', 'waste', 'other'),
    emission_amount DECIMAL(10,2),
    emission_unit VARCHAR(20),
    calculation_method VARCHAR(100),
    recording_period_start DATE,
    recording_period_end DATE,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (updated_by) REFERENCES users(id)
);

-- Energy Efficiency Projects
CREATE TABLE energy_efficiency_projects (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255),
    description TEXT,
    status ENUM('planned', 'in_progress', 'completed', 'cancelled'),
    start_date DATE,
    completion_date DATE,
    estimated_cost DECIMAL(10,2),
    actual_cost DECIMAL(10,2),
    estimated_savings DECIMAL(10,2),
    actual_savings DECIMAL(10,2),
    roi_percentage DECIMAL(5,2),
    priority ENUM('low', 'medium', 'high'),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (updated_by) REFERENCES users(id)
);

-- Smart Building Integration
CREATE TABLE smart_building_devices (
    id INT PRIMARY KEY AUTO_INCREMENT,
    device_name VARCHAR(255),
    device_type VARCHAR(100),
    location VARCHAR(255),
    ip_address VARCHAR(45),
    mac_address VARCHAR(17),
    protocol VARCHAR(50),
    status ENUM('active', 'inactive', 'maintenance', 'error'),
    last_communication DATETIME,
    firmware_version VARCHAR(50),
    configuration JSON,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (updated_by) REFERENCES users(id)
);

-- Device Readings
CREATE TABLE smart_device_readings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    device_id INT,
    reading_type VARCHAR(50),
    reading_value JSON,
    reading_timestamp DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (device_id) REFERENCES smart_building_devices(id)
);

-- Sustainability Reports
CREATE TABLE sustainability_reports (
    id INT PRIMARY KEY AUTO_INCREMENT,
    report_title VARCHAR(255),
    report_period_start DATE,
    report_period_end DATE,
    report_type ENUM('monthly', 'quarterly', 'annual', 'custom'),
    status ENUM('draft', 'published', 'archived'),
    content JSON,
    metrics JSON,
    file_path VARCHAR(255),
    published_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (updated_by) REFERENCES users(id)
);

-- Energy Efficiency Targets
CREATE TABLE energy_efficiency_targets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    target_type ENUM('consumption', 'cost', 'emissions'),
    target_value DECIMAL(10,2),
    target_unit VARCHAR(20),
    start_date DATE,
    end_date DATE,
    status ENUM('active', 'achieved', 'missed', 'cancelled'),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (updated_by) REFERENCES users(id)
); 