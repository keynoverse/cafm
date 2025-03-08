-- Users and Authentication
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    role ENUM('admin', 'manager', 'technician', 'user') NOT NULL,
    department VARCHAR(50),
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Assets
CREATE TABLE assets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    asset_tag VARCHAR(50) UNIQUE NOT NULL,
    category_id INT,
    location_id INT,
    status ENUM('active', 'inactive', 'maintenance', 'retired') NOT NULL,
    purchase_date DATE,
    purchase_cost DECIMAL(10,2),
    warranty_expiry DATE,
    description TEXT,
    specifications JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Asset Categories
CREATE TABLE asset_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    parent_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES asset_categories(id)
);

-- Locations
CREATE TABLE locations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    building VARCHAR(100),
    floor VARCHAR(50),
    room VARCHAR(50),
    address TEXT,
    parent_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES locations(id)
);

-- Work Orders
CREATE TABLE work_orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    asset_id INT,
    category_id INT,
    priority ENUM('low', 'medium', 'high', 'urgent') NOT NULL,
    status ENUM('pending', 'assigned', 'in_progress', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
    assigned_to INT,
    created_by INT NOT NULL,
    due_date DATE NOT NULL,
    completed_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (asset_id) REFERENCES assets(id) ON DELETE SET NULL,
    FOREIGN KEY (category_id) REFERENCES work_order_categories(id) ON DELETE SET NULL,
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Work Order Comments
CREATE TABLE work_order_comments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    work_order_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (work_order_id) REFERENCES work_orders(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Work Order Attachments
CREATE TABLE work_order_attachments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    work_order_id INT NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_type VARCHAR(100),
    file_size INT,
    uploaded_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (work_order_id) REFERENCES work_orders(id) ON DELETE CASCADE,
    FOREIGN KEY (uploaded_by) REFERENCES users(id)
);

-- Work Order History
CREATE TABLE work_order_history (
    id INT PRIMARY KEY AUTO_INCREMENT,
    work_order_id INT NOT NULL,
    user_id INT NOT NULL,
    action VARCHAR(50) NOT NULL,
    details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (work_order_id) REFERENCES work_orders(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Work Order Categories
CREATE TABLE work_order_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default work order categories
INSERT INTO work_order_categories (name, description) VALUES
('Preventive Maintenance', 'Regular maintenance tasks to prevent equipment failure'),
('Corrective Maintenance', 'Repair tasks to fix equipment issues'),
('Emergency Maintenance', 'Urgent repairs for critical equipment failures'),
('Inspection', 'Regular equipment inspections and assessments'),
('Installation', 'New equipment installation and setup'),
('Other', 'Miscellaneous work orders');

-- Inventory Management Tables
CREATE TABLE IF NOT EXISTS inventory (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    sku VARCHAR(50) NOT NULL UNIQUE,
    category_id INT,
    location_id INT,
    supplier_id INT,
    quantity INT NOT NULL DEFAULT 0,
    min_quantity INT NOT NULL DEFAULT 0,
    unit VARCHAR(20) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    description TEXT,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES asset_categories(id) ON DELETE SET NULL,
    FOREIGN KEY (location_id) REFERENCES locations(id) ON DELETE SET NULL,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS inventory_transactions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    item_id INT NOT NULL,
    user_id INT NOT NULL,
    type ENUM('add', 'remove') NOT NULL,
    quantity INT NOT NULL,
    new_quantity INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (item_id) REFERENCES inventory(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Indexes for better performance
CREATE INDEX idx_inventory_category ON inventory(category_id);
CREATE INDEX idx_inventory_location ON inventory(location_id);
CREATE INDEX idx_inventory_supplier ON inventory(supplier_id);
CREATE INDEX idx_inventory_sku ON inventory(sku);
CREATE INDEX idx_inventory_created_by ON inventory(created_by);
CREATE INDEX idx_inventory_transactions_item ON inventory_transactions(item_id);
CREATE INDEX idx_inventory_transactions_user ON inventory_transactions(user_id);
CREATE INDEX idx_inventory_transactions_created ON inventory_transactions(created_at);

-- Sample inventory data
INSERT INTO inventory (name, sku, category_id, location_id, supplier_id, quantity, min_quantity, unit, price, description, created_by) VALUES
('HVAC Filter', 'HVAC-FILTER-001', 1, 1, 1, 50, 10, 'pieces', 25.99, 'Standard HVAC filter for maintenance', 1),
('Light Bulb', 'LIGHT-BULB-001', 2, 1, 2, 100, 20, 'pieces', 5.99, 'LED light bulb for general lighting', 1),
('Cleaning Supplies', 'CLEAN-001', 3, 2, 3, 30, 5, 'sets', 49.99, 'Basic cleaning supplies kit', 1),
('Safety Equipment', 'SAFETY-001', 4, 3, 4, 25, 8, 'sets', 199.99, 'Safety equipment for maintenance staff', 1),
('Tools Set', 'TOOLS-001', 5, 4, 5, 15, 3, 'sets', 299.99, 'Basic maintenance tools set', 1);

-- Sample inventory transactions
INSERT INTO inventory_transactions (item_id, user_id, type, quantity, new_quantity) VALUES
(1, 1, 'add', 50, 50),
(2, 1, 'add', 100, 100),
(3, 1, 'add', 30, 30),
(4, 1, 'add', 25, 25),
(5, 1, 'add', 15, 15);

-- Suppliers
CREATE TABLE suppliers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    contact_person VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Documents
CREATE TABLE documents (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    file_path VARCHAR(255) NOT NULL,
    file_type VARCHAR(50),
    file_size INT,
    category_id INT,
    uploaded_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (uploaded_by) REFERENCES users(id)
);

-- Document Categories
CREATE TABLE document_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Preventive Maintenance
CREATE TABLE preventive_maintenance (
    id INT PRIMARY KEY AUTO_INCREMENT,
    asset_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    frequency ENUM('daily', 'weekly', 'monthly', 'quarterly', 'yearly') NOT NULL,
    last_performed DATE,
    next_due DATE,
    assigned_to INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (asset_id) REFERENCES assets(id),
    FOREIGN KEY (assigned_to) REFERENCES users(id)
);

-- Maintenance History
CREATE TABLE maintenance_history (
    id INT PRIMARY KEY AUTO_INCREMENT,
    asset_id INT NOT NULL,
    work_order_id INT,
    description TEXT,
    performed_by INT,
    performed_at TIMESTAMP,
    cost DECIMAL(10,2),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (asset_id) REFERENCES assets(id),
    FOREIGN KEY (work_order_id) REFERENCES work_orders(id),
    FOREIGN KEY (performed_by) REFERENCES users(id)
);

-- Purchase Orders
CREATE TABLE purchase_orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    po_number VARCHAR(20) NOT NULL UNIQUE,
    supplier_id INT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('draft', 'pending', 'approved', 'ordered', 'received', 'cancelled') NOT NULL DEFAULT 'draft',
    notes TEXT,
    created_by INT NOT NULL,
    approved_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (approved_by) REFERENCES users(id)
);

-- Purchase Order Items
CREATE TABLE purchase_order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    purchase_order_id INT NOT NULL,
    inventory_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10, 2) NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (purchase_order_id) REFERENCES purchase_orders(id) ON DELETE CASCADE,
    FOREIGN KEY (inventory_id) REFERENCES inventory(id)
);

-- Indexes
CREATE INDEX idx_purchase_orders_supplier ON purchase_orders(supplier_id);
CREATE INDEX idx_purchase_orders_status ON purchase_orders(status);
CREATE INDEX idx_purchase_orders_created_at ON purchase_orders(created_at);
CREATE INDEX idx_purchase_order_items_order ON purchase_order_items(purchase_order_id);
CREATE INDEX idx_purchase_order_items_inventory ON purchase_order_items(inventory_id);

-- Sample Purchase Orders
INSERT INTO purchase_orders (po_number, supplier_id, total_amount, status, notes, created_by, approved_by) VALUES
('PO-20240301-1001', 1, 1500.00, 'approved', 'Monthly office supplies order', 1, 1),
('PO-20240301-1002', 2, 2500.00, 'pending', 'Equipment maintenance parts', 1, NULL),
('PO-20240301-1003', 3, 800.00, 'draft', 'Cleaning supplies', 1, NULL);

-- Sample Purchase Order Items
INSERT INTO purchase_order_items (purchase_order_id, inventory_id, quantity, unit_price, total_price) VALUES
(1, 1, 50, 10.00, 500.00),
(1, 2, 100, 10.00, 1000.00),
(2, 3, 5, 500.00, 2500.00),
(3, 4, 20, 40.00, 800.00);

-- Facility Bookings
CREATE TABLE facility_bookings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    facility_id INT NOT NULL,
    booked_by INT NOT NULL,
    start_time DATETIME NOT NULL,
    end_time DATETIME NOT NULL,
    purpose TEXT,
    status ENUM('pending', 'approved', 'rejected', 'cancelled') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (facility_id) REFERENCES locations(id),
    FOREIGN KEY (booked_by) REFERENCES users(id)
);

-- Visitor Management
CREATE TABLE visitors (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    company VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(20),
    purpose TEXT,
    host_id INT NOT NULL,
    check_in DATETIME,
    check_out DATETIME,
    status ENUM('expected', 'checked_in', 'checked_out', 'cancelled') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (host_id) REFERENCES users(id)
);

-- Key Management
CREATE TABLE keys (
    id INT PRIMARY KEY AUTO_INCREMENT,
    key_number VARCHAR(50) UNIQUE NOT NULL,
    location_id INT NOT NULL,
    status ENUM('available', 'assigned', 'lost', 'retired') NOT NULL,
    assigned_to INT,
    assigned_at TIMESTAMP NULL,
    returned_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (location_id) REFERENCES locations(id),
    FOREIGN KEY (assigned_to) REFERENCES users(id)
);

-- Risk Assessments
CREATE TABLE risk_assessments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    risk_level ENUM('low', 'medium', 'high', 'critical') NOT NULL,
    status ENUM('draft', 'pending', 'approved', 'implemented', 'reviewed') NOT NULL,
    created_by INT NOT NULL,
    approved_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (approved_by) REFERENCES users(id)
);

-- Work Permits
CREATE TABLE work_permits (
    id INT PRIMARY KEY AUTO_INCREMENT,
    permit_number VARCHAR(50) UNIQUE NOT NULL,
    work_order_id INT,
    risk_assessment_id INT,
    status ENUM('pending', 'approved', 'rejected', 'completed', 'cancelled') NOT NULL,
    issued_by INT NOT NULL,
    approved_by INT,
    valid_from DATETIME NOT NULL,
    valid_until DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (work_order_id) REFERENCES work_orders(id),
    FOREIGN KEY (risk_assessment_id) REFERENCES risk_assessments(id),
    FOREIGN KEY (issued_by) REFERENCES users(id),
    FOREIGN KEY (approved_by) REFERENCES users(id)
);

-- Pest Control
CREATE TABLE pest_control (
    id INT PRIMARY KEY AUTO_INCREMENT,
    location_id INT NOT NULL,
    pest_type VARCHAR(50) NOT NULL,
    treatment_date DATE NOT NULL,
    next_treatment_date DATE,
    treatment_method TEXT,
    performed_by VARCHAR(100),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (location_id) REFERENCES locations(id)
);

-- Tenant Billing
CREATE TABLE tenant_billing (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tenant_id INT NOT NULL,
    billing_period_start DATE NOT NULL,
    billing_period_end DATE NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'paid', 'overdue', 'cancelled') NOT NULL,
    due_date DATE NOT NULL,
    paid_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tenant_id) REFERENCES users(id)
);

-- Mail Room
CREATE TABLE mail_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tracking_number VARCHAR(50) UNIQUE NOT NULL,
    recipient_id INT NOT NULL,
    sender_name VARCHAR(100),
    sender_address TEXT,
    type ENUM('letter', 'package', 'registered', 'express') NOT NULL,
    status ENUM('received', 'processed', 'delivered', 'returned') NOT NULL,
    received_at TIMESTAMP,
    delivered_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (recipient_id) REFERENCES users(id)
);

-- Feedback
CREATE TABLE feedback (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    category VARCHAR(50) NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    status ENUM('pending', 'in_progress', 'resolved', 'closed') NOT NULL,
    priority ENUM('low', 'medium', 'high') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Cafeteria Management
CREATE TABLE cafeteria_orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    menu_item_id INT NOT NULL,
    quantity INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'preparing', 'ready', 'delivered', 'cancelled') NOT NULL,
    order_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Patrol Management
CREATE TABLE patrols (
    id INT PRIMARY KEY AUTO_INCREMENT,
    guard_id INT NOT NULL,
    route_id INT NOT NULL,
    start_time DATETIME NOT NULL,
    end_time DATETIME,
    status ENUM('in_progress', 'completed', 'cancelled') NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (guard_id) REFERENCES users(id)
);

-- Library Management
CREATE TABLE library_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(100),
    isbn VARCHAR(13),
    category VARCHAR(50),
    status ENUM('available', 'borrowed', 'reserved', 'maintenance') NOT NULL,
    location VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE library_borrowings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    item_id INT NOT NULL,
    user_id INT NOT NULL,
    borrowed_at TIMESTAMP NOT NULL,
    due_date DATE NOT NULL,
    returned_at TIMESTAMP NULL,
    status ENUM('borrowed', 'returned', 'overdue', 'lost') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (item_id) REFERENCES library_items(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Create preventive maintenance tables
CREATE TABLE IF NOT EXISTS preventive_maintenance_tasks (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    asset_id INT NOT NULL,
    frequency ENUM('daily', 'weekly', 'monthly', 'quarterly', 'yearly') NOT NULL,
    assigned_to INT,
    created_by INT NOT NULL,
    last_performed_at DATETIME,
    next_due_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (asset_id) REFERENCES assets(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS preventive_maintenance_history (
    id INT PRIMARY KEY AUTO_INCREMENT,
    task_id INT NOT NULL,
    user_id INT NOT NULL,
    action ENUM('created', 'updated', 'completed', 'deleted') NOT NULL,
    details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (task_id) REFERENCES preventive_maintenance_tasks(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Add indexes for better performance
CREATE INDEX idx_preventive_maintenance_asset ON preventive_maintenance_tasks(asset_id);
CREATE INDEX idx_preventive_maintenance_assigned ON preventive_maintenance_tasks(assigned_to);
CREATE INDEX idx_preventive_maintenance_next_due ON preventive_maintenance_tasks(next_due_date);
CREATE INDEX idx_preventive_maintenance_history_task ON preventive_maintenance_history(task_id);
CREATE INDEX idx_preventive_maintenance_history_user ON preventive_maintenance_history(user_id);

-- Insert some default preventive maintenance tasks
INSERT INTO preventive_maintenance_tasks (title, description, asset_id, frequency, assigned_to, created_by, next_due_date)
VALUES 
('HVAC Filter Replacement', 'Replace all HVAC filters in the building', 1, 'monthly', 1, 1, DATE_ADD(CURRENT_DATE, INTERVAL 1 MONTH)),
('Fire Alarm Testing', 'Test all fire alarms and emergency lighting', 2, 'quarterly', 2, 1, DATE_ADD(CURRENT_DATE, INTERVAL 3 MONTH)),
('Elevator Maintenance', 'Perform routine maintenance on all elevators', 3, 'monthly', 3, 1, DATE_ADD(CURRENT_DATE, INTERVAL 1 MONTH)),
('Generator Testing', 'Test emergency generator and backup systems', 4, 'monthly', 4, 1, DATE_ADD(CURRENT_DATE, INTERVAL 1 MONTH)),
('Security System Check', 'Inspect and test all security cameras and access control systems', 5, 'weekly', 5, 1, DATE_ADD(CURRENT_DATE, INTERVAL 1 WEEK));

-- Work Order Tasks
CREATE TABLE work_order_tasks (
    id INT PRIMARY KEY AUTO_INCREMENT,
    work_order_id INT NOT NULL,
    description TEXT NOT NULL,
    priority ENUM('low', 'medium', 'high', 'urgent'),
    status ENUM('pending', 'in_progress', 'completed') NOT NULL DEFAULT 'pending',
    assigned_to INT,
    due_date DATE,
    completed_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (work_order_id) REFERENCES work_orders(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL
);

-- Work Order Task Notes
CREATE TABLE work_order_task_notes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    task_id INT NOT NULL,
    user_id INT NOT NULL,
    notes TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (task_id) REFERENCES work_order_tasks(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Work Order Task Dependencies
CREATE TABLE work_order_task_dependencies (
    id INT PRIMARY KEY AUTO_INCREMENT,
    task_id INT NOT NULL,
    dependent_task_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (task_id) REFERENCES work_order_tasks(id) ON DELETE CASCADE,
    FOREIGN KEY (dependent_task_id) REFERENCES work_order_tasks(id) ON DELETE CASCADE,
    UNIQUE KEY unique_dependency (task_id, dependent_task_id)
);

-- Work Order Task Time Tracking
CREATE TABLE work_order_task_time (
    id INT PRIMARY KEY AUTO_INCREMENT,
    task_id INT NOT NULL,
    user_id INT NOT NULL,
    start_time DATETIME NOT NULL,
    end_time DATETIME,
    duration INT, -- in seconds
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (task_id) REFERENCES work_order_tasks(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Add indexes for better performance
CREATE INDEX idx_work_order_tasks_work_order ON work_order_tasks(work_order_id);
CREATE INDEX idx_work_order_tasks_assigned ON work_order_tasks(assigned_to);
CREATE INDEX idx_work_order_tasks_status ON work_order_tasks(status);
CREATE INDEX idx_work_order_task_notes_task ON work_order_task_notes(task_id);
CREATE INDEX idx_work_order_task_dependencies_task ON work_order_task_dependencies(task_id);
CREATE INDEX idx_work_order_task_time_task ON work_order_task_time(task_id);

-- Maintenance Management Module

-- Preventive Maintenance Schedules
CREATE TABLE preventive_maintenance_schedules (
    id INT PRIMARY KEY AUTO_INCREMENT,
    asset_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    frequency ENUM('daily', 'weekly', 'bi_weekly', 'monthly', 'quarterly', 'semi_annual', 'annual') NOT NULL,
    next_due_date DATE NOT NULL,
    last_completed_date DATE,
    assigned_to INT,
    status ENUM('active', 'inactive', 'completed', 'overdue') NOT NULL DEFAULT 'active',
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (asset_id) REFERENCES assets(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_to) REFERENCES users(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Equipment Calibration
CREATE TABLE equipment_calibrations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    asset_id INT NOT NULL,
    calibration_type VARCHAR(100) NOT NULL,
    calibration_standard VARCHAR(255),
    last_calibration_date DATE,
    next_calibration_date DATE NOT NULL,
    calibration_result TEXT,
    performed_by INT,
    status ENUM('pending', 'in_progress', 'completed', 'failed', 'cancelled') NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (asset_id) REFERENCES assets(id) ON DELETE CASCADE,
    FOREIGN KEY (performed_by) REFERENCES users(id)
);

-- Maintenance Requests
CREATE TABLE maintenance_requests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    request_number VARCHAR(50) UNIQUE NOT NULL,
    requester_id INT NOT NULL,
    asset_id INT,
    location_id INT,
    priority ENUM('low', 'medium', 'high', 'urgent') NOT NULL,
    type ENUM('repair', 'maintenance', 'inspection', 'installation', 'other') NOT NULL,
    description TEXT NOT NULL,
    status ENUM('pending', 'approved', 'in_progress', 'completed', 'rejected', 'cancelled') NOT NULL,
    assigned_to INT,
    approved_by INT,
    due_date DATE,
    completed_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (requester_id) REFERENCES users(id),
    FOREIGN KEY (asset_id) REFERENCES assets(id) ON DELETE SET NULL,
    FOREIGN KEY (location_id) REFERENCES locations(id),
    FOREIGN KEY (assigned_to) REFERENCES users(id),
    FOREIGN KEY (approved_by) REFERENCES users(id)
);

-- Service Level Agreements
CREATE TABLE service_level_agreements (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    priority_level ENUM('low', 'medium', 'high', 'urgent') NOT NULL,
    response_time INT NOT NULL, -- in minutes
    resolution_time INT NOT NULL, -- in minutes
    escalation_time INT, -- in minutes
    escalation_contact INT,
    business_hours_only BOOLEAN DEFAULT true,
    active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (escalation_contact) REFERENCES users(id)
);

-- Mobile Maintenance Sessions
CREATE TABLE mobile_maintenance_sessions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    device_id VARCHAR(255) NOT NULL,
    device_type VARCHAR(100),
    login_time TIMESTAMP NOT NULL,
    logout_time TIMESTAMP,
    last_activity TIMESTAMP,
    status ENUM('active', 'inactive', 'expired') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Warranty Management
CREATE TABLE warranties (
    id INT PRIMARY KEY AUTO_INCREMENT,
    asset_id INT NOT NULL,
    warranty_number VARCHAR(100),
    provider VARCHAR(255) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    coverage_type VARCHAR(100),
    coverage_details TEXT,
    contact_info TEXT,
    status ENUM('active', 'expired', 'claimed', 'void') NOT NULL,
    documents TEXT, -- JSON array of document URLs
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (asset_id) REFERENCES assets(id) ON DELETE CASCADE
);

-- Warranty Claims
CREATE TABLE warranty_claims (
    id INT PRIMARY KEY AUTO_INCREMENT,
    warranty_id INT NOT NULL,
    claim_number VARCHAR(100) UNIQUE NOT NULL,
    description TEXT NOT NULL,
    claim_date DATE NOT NULL,
    resolution_date DATE,
    status ENUM('pending', 'approved', 'in_progress', 'resolved', 'rejected') NOT NULL,
    resolution_details TEXT,
    cost_covered DECIMAL(10, 2),
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (warranty_id) REFERENCES warranties(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Maintenance History
CREATE TABLE maintenance_history (
    id INT PRIMARY KEY AUTO_INCREMENT,
    asset_id INT NOT NULL,
    maintenance_type ENUM('preventive', 'corrective', 'calibration', 'inspection') NOT NULL,
    reference_id INT NOT NULL, -- ID from respective tables
    reference_type VARCHAR(50) NOT NULL, -- Table name reference
    performed_by INT NOT NULL,
    performed_date DATE NOT NULL,
    description TEXT,
    cost DECIMAL(10, 2),
    parts_used TEXT, -- JSON array of parts
    labor_hours DECIMAL(5, 2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (asset_id) REFERENCES assets(id) ON DELETE CASCADE,
    FOREIGN KEY (performed_by) REFERENCES users(id)
);

-- Indexes for better performance
CREATE INDEX idx_pm_schedules_asset ON preventive_maintenance_schedules(asset_id);
CREATE INDEX idx_pm_schedules_next_due ON preventive_maintenance_schedules(next_due_date);
CREATE INDEX idx_calibrations_asset ON equipment_calibrations(asset_id);
CREATE INDEX idx_calibrations_next_date ON equipment_calibrations(next_calibration_date);
CREATE INDEX idx_maintenance_requests_number ON maintenance_requests(request_number);
CREATE INDEX idx_maintenance_requests_status ON maintenance_requests(status);
CREATE INDEX idx_warranties_asset ON warranties(asset_id);
CREATE INDEX idx_warranties_status ON warranties(status);
CREATE INDEX idx_warranty_claims_number ON warranty_claims(claim_number);
CREATE INDEX idx_maintenance_history_asset ON maintenance_history(asset_id);
CREATE INDEX idx_maintenance_history_type ON maintenance_history(maintenance_type);

-- Insert default SLA configurations
INSERT INTO service_level_agreements (title, description, priority_level, response_time, resolution_time, business_hours_only) VALUES
('Urgent Response', 'Critical issues requiring immediate attention', 'urgent', 30, 240, false),
('High Priority', 'Important issues affecting operations', 'high', 60, 480, true),
('Medium Priority', 'Standard maintenance requests', 'medium', 120, 1440, true),
('Low Priority', 'Non-critical maintenance tasks', 'low', 240, 2880, true); 