<?php

use App\Controllers\AssetController;
use App\Controllers\WorkOrderController;
use App\Controllers\MaintenanceRequestController;
use App\Controllers\SLAController;
use App\Controllers\WarrantyController;
use App\Controllers\PreventiveMaintenanceController;
use App\Controllers\CalibrationController;
use App\Controllers\InventoryController;
use App\Controllers\SupplierController;
use App\Controllers\PurchaseOrderController;
use App\Controllers\FacilityBookingController;
use App\Controllers\LocationController;
use App\Controllers\BuildingController;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\FloorController;
use App\Controllers\RoomController;

// Asset Management Routes
$router->get('/assets', [AssetController::class, 'index']);
$router->get('/assets/create', [AssetController::class, 'create']);
$router->post('/assets/create', [AssetController::class, 'create']);
$router->get('/assets/{id}', [AssetController::class, 'show']);
$router->get('/assets/{id}/edit', [AssetController::class, 'edit']);
$router->post('/assets/{id}/edit', [AssetController::class, 'edit']);
$router->post('/assets/{id}/delete', [AssetController::class, 'delete']);
$router->post('/assets/{id}/status', [AssetController::class, 'updateStatus']);
$router->get('/assets/search', [AssetController::class, 'search']);
$router->get('/assets/counts', [AssetController::class, 'getAssetCounts']);

// Work Order Management Routes
$router->get('/work-orders', [WorkOrderController::class, 'index']);
$router->get('/work-orders/create', [WorkOrderController::class, 'create']);
$router->post('/work-orders/create', [WorkOrderController::class, 'create']);
$router->get('/work-orders/{id}', [WorkOrderController::class, 'show']);
$router->get('/work-orders/{id}/edit', [WorkOrderController::class, 'edit']);
$router->post('/work-orders/{id}/edit', [WorkOrderController::class, 'edit']);
$router->post('/work-orders/{id}/delete', [WorkOrderController::class, 'delete']);
$router->post('/work-orders/{id}/status', [WorkOrderController::class, 'updateStatus']);
$router->get('/work-orders/search', [WorkOrderController::class, 'search']);
$router->get('/work-orders/counts', [WorkOrderController::class, 'getWorkOrderCounts']);
$router->get('/work-orders/overdue', [WorkOrderController::class, 'getOverdueWorkOrders']);
$router->get('/work-orders/upcoming', [WorkOrderController::class, 'getUpcomingWorkOrders']);

// Work Order Comments Routes
$router->post('/work-orders/{id}/comments', [WorkOrderController::class, 'addComment']);
$router->delete('/work-orders/{id}/comments/{commentId}', [WorkOrderController::class, 'deleteComment']);

// Maintenance Request Portal Routes
$router->get('/maintenance-requests', [MaintenanceRequestController::class, 'index']);
$router->get('/maintenance-requests/create', [MaintenanceRequestController::class, 'create']);
$router->post('/maintenance-requests/create', [MaintenanceRequestController::class, 'store']);
$router->get('/maintenance-requests/{id}', [MaintenanceRequestController::class, 'show']);
$router->get('/maintenance-requests/{id}/edit', [MaintenanceRequestController::class, 'edit']);
$router->post('/maintenance-requests/{id}/edit', [MaintenanceRequestController::class, 'update']);
$router->post('/maintenance-requests/{id}/delete', [MaintenanceRequestController::class, 'delete']);
$router->post('/maintenance-requests/{id}/status', [MaintenanceRequestController::class, 'updateStatus']);
$router->post('/maintenance-requests/{id}/approve', [MaintenanceRequestController::class, 'approve']);
$router->post('/maintenance-requests/{id}/reject', [MaintenanceRequestController::class, 'reject']);
$router->post('/maintenance-requests/{id}/convert', [MaintenanceRequestController::class, 'convertToWorkOrder']);
$router->get('/maintenance-requests/search', [MaintenanceRequestController::class, 'search']);
$router->get('/maintenance-requests/my-requests', [MaintenanceRequestController::class, 'getUserRequests']);
$router->get('/maintenance-requests/dashboard', [MaintenanceRequestController::class, 'dashboard']);
$router->post('/maintenance-requests/{id}/feedback', [MaintenanceRequestController::class, 'submitFeedback']);

// SLA Management Routes
$router->get('/sla', [SLAController::class, 'index']);
$router->get('/sla/create', [SLAController::class, 'create']);
$router->post('/sla/create', [SLAController::class, 'store']);
$router->get('/sla/{id}', [SLAController::class, 'show']);
$router->get('/sla/{id}/edit', [SLAController::class, 'edit']);
$router->post('/sla/{id}/edit', [SLAController::class, 'update']);
$router->post('/sla/{id}/delete', [SLAController::class, 'delete']);
$router->get('/sla/search', [SLAController::class, 'search']);
$router->get('/sla/performance', [SLAController::class, 'getPerformanceMetrics']);
$router->get('/sla/violations', [SLAController::class, 'getViolations']);
$router->get('/sla/reports', [SLAController::class, 'generateReports']);
$router->post('/sla/{id}/priority', [SLAController::class, 'updatePriority']);
$router->get('/sla/categories', [SLAController::class, 'getCategories']);
$router->post('/sla/categories', [SLAController::class, 'createCategory']);

// Warranty Management Routes
$router->get('/warranties', [WarrantyController::class, 'index']);
$router->get('/warranties/create', [WarrantyController::class, 'create']);
$router->post('/warranties/create', [WarrantyController::class, 'store']);
$router->get('/warranties/{id}', [WarrantyController::class, 'show']);
$router->get('/warranties/{id}/edit', [WarrantyController::class, 'edit']);
$router->post('/warranties/{id}/edit', [WarrantyController::class, 'update']);
$router->post('/warranties/{id}/delete', [WarrantyController::class, 'delete']);
$router->get('/warranties/search', [WarrantyController::class, 'search']);
$router->get('/warranties/expiring', [WarrantyController::class, 'getExpiringWarranties']);
$router->get('/warranties/expired', [WarrantyController::class, 'getExpiredWarranties']);
$router->post('/warranties/{id}/claim', [WarrantyController::class, 'createClaim']);
$router->get('/warranties/{id}/claims', [WarrantyController::class, 'getClaims']);
$router->post('/warranties/{id}/renew', [WarrantyController::class, 'renewWarranty']);
$router->get('/warranties/assets/{assetId}', [WarrantyController::class, 'getAssetWarranties']);
$router->get('/warranties/dashboard', [WarrantyController::class, 'dashboard']);

// Preventive Maintenance Routes
$router->get('/preventive-maintenance', [PreventiveMaintenanceController::class, 'index']);
$router->get('/preventive-maintenance/create', [PreventiveMaintenanceController::class, 'create']);
$router->post('/preventive-maintenance/create', [PreventiveMaintenanceController::class, 'create']);
$router->get('/preventive-maintenance/{id}', [PreventiveMaintenanceController::class, 'show']);
$router->get('/preventive-maintenance/{id}/edit', [PreventiveMaintenanceController::class, 'edit']);
$router->post('/preventive-maintenance/{id}/edit', [PreventiveMaintenanceController::class, 'edit']);
$router->post('/preventive-maintenance/{id}/delete', [PreventiveMaintenanceController::class, 'delete']);
$router->post('/preventive-maintenance/{id}/complete', [PreventiveMaintenanceController::class, 'markAsCompleted']);
$router->get('/preventive-maintenance/upcoming', [PreventiveMaintenanceController::class, 'getUpcomingTasks']);
$router->get('/preventive-maintenance/overdue', [PreventiveMaintenanceController::class, 'getOverdueTasks']);

// Equipment Calibration Routes
$router->get('/calibration', [CalibrationController::class, 'index']);
$router->get('/calibration/create', [CalibrationController::class, 'create']);
$router->post('/calibration/create', [CalibrationController::class, 'store']);
$router->get('/calibration/{id}', [CalibrationController::class, 'show']);
$router->get('/calibration/{id}/edit', [CalibrationController::class, 'edit']);
$router->post('/calibration/{id}/edit', [CalibrationController::class, 'update']);
$router->post('/calibration/{id}/delete', [CalibrationController::class, 'delete']);
$router->post('/calibration/{id}/start', [CalibrationController::class, 'startCalibration']);
$router->post('/calibration/{id}/complete', [CalibrationController::class, 'completeCalibration']);
$router->get('/calibration/search', [CalibrationController::class, 'search']);
$router->get('/calibration/export', [CalibrationController::class, 'export']);
$router->get('/calibration/{id}/export', [CalibrationController::class, 'exportSingle']);
$router->get('/calibration/overdue', [CalibrationController::class, 'getOverdue']);
$router->get('/calibration/upcoming', [CalibrationController::class, 'getUpcoming']);
$router->get('/calibration/statistics', [CalibrationController::class, 'getStatistics']);

// Inventory Management Routes
$router->get('/inventory', [InventoryController::class, 'index']);
$router->get('/inventory/create', [InventoryController::class, 'create']);
$router->post('/inventory/create', [InventoryController::class, 'create']);
$router->get('/inventory/{id}', [InventoryController::class, 'show']);
$router->get('/inventory/{id}/edit', [InventoryController::class, 'edit']);
$router->post('/inventory/{id}/edit', [InventoryController::class, 'edit']);
$router->post('/inventory/{id}/delete', [InventoryController::class, 'delete']);
$router->post('/inventory/{id}/quantity', [InventoryController::class, 'updateQuantity']);
$router->get('/inventory/search', [InventoryController::class, 'search']);
$router->get('/inventory/low-stock', [InventoryController::class, 'getLowStockItems']);

// Supplier Management Routes
$router->get('/suppliers', [SupplierController::class, 'index']);
$router->get('/suppliers/create', [SupplierController::class, 'create']);
$router->post('/suppliers/create', [SupplierController::class, 'create']);
$router->get('/suppliers/{id}', [SupplierController::class, 'show']);
$router->get('/suppliers/{id}/edit', [SupplierController::class, 'edit']);
$router->post('/suppliers/{id}/edit', [SupplierController::class, 'edit']);
$router->post('/suppliers/{id}/delete', [SupplierController::class, 'delete']);
$router->get('/suppliers/search', [SupplierController::class, 'search']);

// Purchase Order Management Routes
$router->get('/purchase-orders', [PurchaseOrderController::class, 'index']);
$router->get('/purchase-orders/create', [PurchaseOrderController::class, 'create']);
$router->post('/purchase-orders/create', [PurchaseOrderController::class, 'create']);
$router->get('/purchase-orders/{id}', [PurchaseOrderController::class, 'show']);
$router->get('/purchase-orders/{id}/edit', [PurchaseOrderController::class, 'edit']);
$router->post('/purchase-orders/{id}/edit', [PurchaseOrderController::class, 'edit']);
$router->post('/purchase-orders/{id}/delete', [PurchaseOrderController::class, 'delete']);
$router->post('/purchase-orders/{id}/status', [PurchaseOrderController::class, 'updateStatus']);
$router->get('/purchase-orders/search', [PurchaseOrderController::class, 'search']);

// Facility Booking Management Routes
$router->get('/facility-bookings', [FacilityBookingController::class, 'index']);
$router->get('/facility-bookings/create', [FacilityBookingController::class, 'create']);
$router->post('/facility-bookings/create', [FacilityBookingController::class, 'create']);
$router->get('/facility-bookings/{id}', [FacilityBookingController::class, 'show']);
$router->get('/facility-bookings/{id}/edit', [FacilityBookingController::class, 'edit']);
$router->post('/facility-bookings/{id}/edit', [FacilityBookingController::class, 'edit']);
$router->post('/facility-bookings/{id}/delete', [FacilityBookingController::class, 'delete']);
$router->post('/facility-bookings/{id}/status', [FacilityBookingController::class, 'updateStatus']);
$router->get('/facility-bookings/search', [FacilityBookingController::class, 'search']);
$router->get('/facility-bookings/check-availability', [FacilityBookingController::class, 'checkAvailability']);

// Location Management Routes
$router->get('/locations', [LocationController::class, 'index']);
$router->get('/locations/create', [LocationController::class, 'create']);
$router->post('/locations/create', [LocationController::class, 'create']);
$router->get('/locations/{id}', [LocationController::class, 'show']);
$router->get('/locations/{id}/edit', [LocationController::class, 'edit']);
$router->post('/locations/{id}/edit', [LocationController::class, 'edit']);
$router->post('/locations/{id}/delete', [LocationController::class, 'delete']);
$router->get('/locations/search', [LocationController::class, 'search']);
$router->get('/locations/floors', [LocationController::class, 'getFloors']);
$router->get('/locations/rooms', [LocationController::class, 'getRooms']);

// Building Management Routes
$router->get('/buildings', [BuildingController::class, 'index']);
$router->get('/buildings/create', [BuildingController::class, 'create']);
$router->post('/buildings/create', [BuildingController::class, 'create']);
$router->get('/buildings/{id}', [BuildingController::class, 'show']);
$router->get('/buildings/{id}/edit', [BuildingController::class, 'edit']);
$router->post('/buildings/{id}/edit', [BuildingController::class, 'edit']);
$router->post('/buildings/{id}/delete', [BuildingController::class, 'delete']);
$router->get('/buildings/search', [BuildingController::class, 'search']);

// Floor Management Routes
$router->get('/floors', [FloorController::class, 'index']);
$router->get('/floors/create', [FloorController::class, 'create']);
$router->post('/floors/create', [FloorController::class, 'create']);
$router->get('/floors/{id}', [FloorController::class, 'show']);
$router->get('/floors/{id}/edit', [FloorController::class, 'edit']);
$router->post('/floors/{id}/edit', [FloorController::class, 'edit']);
$router->post('/floors/{id}/delete', [FloorController::class, 'delete']);
$router->get('/floors/search', [FloorController::class, 'search']);
$router->get('/floors/by-building', [FloorController::class, 'getFloorsByBuilding']);

// Room Management Routes
$router->get('/rooms', [RoomController::class, 'index']);
$router->get('/rooms/create', [RoomController::class, 'create']);
$router->post('/rooms/create', [RoomController::class, 'create']);
$router->get('/rooms/{id}', [RoomController::class, 'show']);
$router->get('/rooms/{id}/edit', [RoomController::class, 'edit']);
$router->post('/rooms/{id}/edit', [RoomController::class, 'edit']);
$router->post('/rooms/{id}/delete', [RoomController::class, 'delete']);
$router->get('/rooms/search', [RoomController::class, 'search']);
$router->get('/rooms/by-floor', [RoomController::class, 'getRoomsByFloor']);

// Authentication Routes
$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);

// Dashboard Route
$router->get('/', [DashboardController::class, 'index']);
$router->get('/dashboard', [DashboardController::class, 'index']); 