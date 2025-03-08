-- Vendor Directory
CREATE TABLE vendors (
    id INT PRIMARY KEY AUTO_INCREMENT,
    company_name VARCHAR(255) NOT NULL,
    business_type VARCHAR(100) NOT NULL,
    registration_number VARCHAR(100),
    tax_id VARCHAR(100),
    website VARCHAR(255),
    primary_contact_name VARCHAR(255),
    primary_contact_email VARCHAR(255),
    primary_contact_phone VARCHAR(50),
    secondary_contact_name VARCHAR(255),
    secondary_contact_email VARCHAR(255),
    secondary_contact_phone VARCHAR(50),
    billing_address TEXT,
    shipping_address TEXT,
    payment_terms VARCHAR(255),
    payment_method VARCHAR(100),
    bank_name VARCHAR(255),
    bank_account_number VARCHAR(100),
    status ENUM('active', 'inactive', 'blacklisted', 'pending') DEFAULT 'pending',
    rating DECIMAL(3,2),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (updated_by) REFERENCES users(id)
);

-- Vendor Categories and Services
CREATE TABLE vendor_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    parent_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES vendor_categories(id)
);

CREATE TABLE vendor_services (
    id INT PRIMARY KEY AUTO_INCREMENT,
    vendor_id INT,
    category_id INT,
    service_name VARCHAR(255) NOT NULL,
    description TEXT,
    rate_type ENUM('hourly', 'fixed', 'variable') NOT NULL,
    rate_amount DECIMAL(10,2),
    currency VARCHAR(3) DEFAULT 'USD',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vendor_id) REFERENCES vendors(id),
    FOREIGN KEY (category_id) REFERENCES vendor_categories(id)
);

-- Vendor Documents
CREATE TABLE vendor_documents (
    id INT PRIMARY KEY AUTO_INCREMENT,
    vendor_id INT,
    document_type VARCHAR(100) NOT NULL,
    document_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    expiry_date DATE,
    status ENUM('valid', 'expired', 'pending') DEFAULT 'valid',
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    uploaded_by INT,
    FOREIGN KEY (vendor_id) REFERENCES vendors(id),
    FOREIGN KEY (uploaded_by) REFERENCES users(id)
);

-- Vendor Contracts
CREATE TABLE vendor_contracts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    vendor_id INT,
    contract_number VARCHAR(100) UNIQUE,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    renewal_reminder_date DATE,
    contract_value DECIMAL(15,2),
    payment_terms TEXT,
    notice_period INT,
    status ENUM('draft', 'active', 'expired', 'terminated', 'renewed') DEFAULT 'draft',
    termination_reason TEXT,
    file_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (vendor_id) REFERENCES vendors(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (updated_by) REFERENCES users(id)
);

-- Performance Tracking
CREATE TABLE vendor_performance_metrics (
    id INT PRIMARY KEY AUTO_INCREMENT,
    vendor_id INT,
    metric_name VARCHAR(255) NOT NULL,
    target_value DECIMAL(10,2),
    weight DECIMAL(5,2),
    evaluation_period VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vendor_id) REFERENCES vendors(id)
);

CREATE TABLE vendor_performance_evaluations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    vendor_id INT,
    evaluation_period_start DATE,
    evaluation_period_end DATE,
    overall_score DECIMAL(5,2),
    review_comments TEXT,
    reviewed_by INT,
    review_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('draft', 'submitted', 'approved', 'rejected') DEFAULT 'draft',
    FOREIGN KEY (vendor_id) REFERENCES vendors(id),
    FOREIGN KEY (reviewed_by) REFERENCES users(id)
);

CREATE TABLE vendor_performance_scores (
    id INT PRIMARY KEY AUTO_INCREMENT,
    evaluation_id INT,
    metric_id INT,
    actual_value DECIMAL(10,2),
    score DECIMAL(5,2),
    comments TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (evaluation_id) REFERENCES vendor_performance_evaluations(id),
    FOREIGN KEY (metric_id) REFERENCES vendor_performance_metrics(id)
);

-- Service Quality Monitoring
CREATE TABLE vendor_slas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    vendor_id INT,
    service_id INT,
    metric_name VARCHAR(255) NOT NULL,
    description TEXT,
    target_value DECIMAL(10,2),
    minimum_value DECIMAL(10,2),
    measurement_unit VARCHAR(50),
    measurement_frequency VARCHAR(50),
    penalty_rate DECIMAL(10,2),
    penalty_unit VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (vendor_id) REFERENCES vendors(id),
    FOREIGN KEY (service_id) REFERENCES vendor_services(id)
);

CREATE TABLE vendor_incidents (
    id INT PRIMARY KEY AUTO_INCREMENT,
    vendor_id INT,
    service_id INT,
    incident_type VARCHAR(100),
    description TEXT,
    severity ENUM('low', 'medium', 'high', 'critical') NOT NULL,
    status ENUM('open', 'in_progress', 'resolved', 'closed') DEFAULT 'open',
    reported_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    resolved_date TIMESTAMP,
    resolution_comments TEXT,
    reported_by INT,
    resolved_by INT,
    FOREIGN KEY (vendor_id) REFERENCES vendors(id),
    FOREIGN KEY (service_id) REFERENCES vendor_services(id),
    FOREIGN KEY (reported_by) REFERENCES users(id),
    FOREIGN KEY (resolved_by) REFERENCES users(id)
);

-- Payment Processing
CREATE TABLE vendor_invoices (
    id INT PRIMARY KEY AUTO_INCREMENT,
    vendor_id INT,
    contract_id INT,
    invoice_number VARCHAR(100) UNIQUE,
    invoice_date DATE NOT NULL,
    due_date DATE NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    tax_amount DECIMAL(15,2),
    total_amount DECIMAL(15,2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'USD',
    status ENUM('pending', 'approved', 'paid', 'rejected', 'cancelled') DEFAULT 'pending',
    payment_date DATE,
    payment_reference VARCHAR(100),
    notes TEXT,
    file_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (vendor_id) REFERENCES vendors(id),
    FOREIGN KEY (contract_id) REFERENCES vendor_contracts(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (updated_by) REFERENCES users(id)
);

CREATE TABLE vendor_payments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    invoice_id INT,
    payment_date DATE NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    payment_method VARCHAR(100),
    reference_number VARCHAR(100),
    status ENUM('pending', 'completed', 'failed', 'cancelled') DEFAULT 'pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by INT,
    FOREIGN KEY (invoice_id) REFERENCES vendor_invoices(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Vendor Portal Access
CREATE TABLE vendor_portal_users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    vendor_id INT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    status ENUM('active', 'inactive', 'blocked') DEFAULT 'active',
    last_login TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (vendor_id) REFERENCES vendors(id)
);

CREATE TABLE vendor_portal_activities (
    id INT PRIMARY KEY AUTO_INCREMENT,
    vendor_id INT,
    user_id INT,
    activity_type VARCHAR(100) NOT NULL,
    description TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vendor_id) REFERENCES vendors(id),
    FOREIGN KEY (user_id) REFERENCES vendor_portal_users(id)
);

-- Indexes for better performance
CREATE INDEX idx_vendor_status ON vendors(status);
CREATE INDEX idx_vendor_rating ON vendors(rating);
CREATE INDEX idx_contract_dates ON vendor_contracts(start_date, end_date);
CREATE INDEX idx_contract_status ON vendor_contracts(status);
CREATE INDEX idx_evaluation_period ON vendor_performance_evaluations(evaluation_period_start, evaluation_period_end);
CREATE INDEX idx_incident_status ON vendor_incidents(status);
CREATE INDEX idx_invoice_dates ON vendor_invoices(invoice_date, due_date);
CREATE INDEX idx_invoice_status ON vendor_invoices(status);
CREATE INDEX idx_payment_status ON vendor_payments(status); 