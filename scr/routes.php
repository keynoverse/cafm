// Energy Management Routes
$router->group(['prefix' => 'energy', 'middleware' => ['auth']], function($router) {
    // Energy Consumption Monitoring
    $router->get('/', 'EnergyController@index');
    $router->get('/consumption', 'EnergyController@consumptionIndex');
    $router->get('/consumption/create', 'EnergyController@createConsumption');
    $router->post('/consumption/create', 'EnergyController@storeConsumption');
    $router->get('/consumption/{id}', 'EnergyController@showConsumption');
    $router->get('/consumption/{id}/edit', 'EnergyController@editConsumption');
    $router->post('/consumption/{id}/edit', 'EnergyController@updateConsumption');
    $router->post('/consumption/{id}/delete', 'EnergyController@deleteConsumption');

    // Utility Bill Management
    $router->get('/bills', 'UtilityBillController@index');
    $router->get('/bills/create', 'UtilityBillController@create');
    $router->post('/bills/create', 'UtilityBillController@store');
    $router->get('/bills/{id}', 'UtilityBillController@show');
    $router->get('/bills/{id}/edit', 'UtilityBillController@edit');
    $router->post('/bills/{id}/edit', 'UtilityBillController@update');
    $router->post('/bills/{id}/delete', 'UtilityBillController@delete');
    $router->post('/bills/{id}/mark-paid', 'UtilityBillController@markAsPaid');

    // Carbon Footprint Tracking
    $router->get('/carbon', 'CarbonFootprintController@index');
    $router->get('/carbon/create', 'CarbonFootprintController@create');
    $router->post('/carbon/create', 'CarbonFootprintController@store');
    $router->get('/carbon/{id}', 'CarbonFootprintController@show');
    $router->get('/carbon/{id}/edit', 'CarbonFootprintController@edit');
    $router->post('/carbon/{id}/edit', 'CarbonFootprintController@update');
    $router->post('/carbon/{id}/delete', 'CarbonFootprintController@delete');

    // Energy Efficiency Projects
    $router->get('/projects', 'EnergyProjectController@index');
    $router->get('/projects/create', 'EnergyProjectController@create');
    $router->post('/projects/create', 'EnergyProjectController@store');
    $router->get('/projects/{id}', 'EnergyProjectController@show');
    $router->get('/projects/{id}/edit', 'EnergyProjectController@edit');
    $router->post('/projects/{id}/edit', 'EnergyProjectController@update');
    $router->post('/projects/{id}/delete', 'EnergyProjectController@delete');
    $router->post('/projects/{id}/status', 'EnergyProjectController@updateStatus');

    // Smart Building Integration
    $router->get('/devices', 'SmartBuildingController@index');
    $router->get('/devices/create', 'SmartBuildingController@create');
    $router->post('/devices/create', 'SmartBuildingController@store');
    $router->get('/devices/{id}', 'SmartBuildingController@show');
    $router->get('/devices/{id}/edit', 'SmartBuildingController@edit');
    $router->post('/devices/{id}/edit', 'SmartBuildingController@update');
    $router->post('/devices/{id}/delete', 'SmartBuildingController@delete');
    $router->get('/devices/{id}/readings', 'SmartBuildingController@readings');
    $router->post('/devices/{id}/configure', 'SmartBuildingController@configure');

    // Sustainability Reporting
    $router->get('/reports', 'SustainabilityReportController@index');
    $router->get('/reports/create', 'SustainabilityReportController@create');
    $router->post('/reports/create', 'SustainabilityReportController@store');
    $router->get('/reports/{id}', 'SustainabilityReportController@show');
    $router->get('/reports/{id}/edit', 'SustainabilityReportController@edit');
    $router->post('/reports/{id}/edit', 'SustainabilityReportController@update');
    $router->post('/reports/{id}/delete', 'SustainabilityReportController@delete');
    $router->post('/reports/{id}/publish', 'SustainabilityReportController@publish');
    $router->get('/reports/{id}/download', 'SustainabilityReportController@download');

    // Analytics and Dashboard
    $router->get('/analytics', 'EnergyAnalyticsController@index');
    $router->get('/analytics/consumption', 'EnergyAnalyticsController@consumption');
    $router->get('/analytics/costs', 'EnergyAnalyticsController@costs');
    $router->get('/analytics/emissions', 'EnergyAnalyticsController@emissions');
    $router->get('/analytics/efficiency', 'EnergyAnalyticsController@efficiency');
    $router->get('/analytics/export', 'EnergyAnalyticsController@export');

    // API Endpoints for Charts and Real-time Data
    $router->get('/api/consumption-data', 'EnergyApiController@consumptionData');
    $router->get('/api/cost-analysis', 'EnergyApiController@costAnalysis');
    $router->get('/api/emissions-data', 'EnergyApiController@emissionsData');
    $router->get('/api/device-readings', 'EnergyApiController@deviceReadings');
}); 

// Vendor Management Routes
$router->group(['prefix' => 'vendors', 'middleware' => ['auth']], function($router) {
    // Vendor Directory
    $router->get('/', 'VendorController@index');
    $router->get('/create', 'VendorController@create');
    $router->post('/', 'VendorController@store');
    $router->get('/{id}', 'VendorController@show');
    $router->get('/{id}/edit', 'VendorController@edit');
    $router->post('/{id}', 'VendorController@update');
    $router->post('/{id}/delete', 'VendorController@delete');
    $router->get('/export/excel', 'VendorController@exportExcel');
    $router->get('/export/pdf', 'VendorController@exportPdf');
    $router->get('/search', 'VendorController@search');
    
    // Vendor Categories
    $router->get('/categories', 'VendorCategoryController@index');
    $router->get('/categories/create', 'VendorCategoryController@create');
    $router->post('/categories', 'VendorCategoryController@store');
    $router->get('/categories/{id}/edit', 'VendorCategoryController@edit');
    $router->post('/categories/{id}', 'VendorCategoryController@update');
    $router->post('/categories/{id}/delete', 'VendorCategoryController@delete');
    
    // Vendor Services
    $router->get('/{id}/services', 'VendorServiceController@index');
    $router->post('/{id}/services', 'VendorServiceController@store');
    $router->get('/{id}/services/{serviceId}/edit', 'VendorServiceController@edit');
    $router->post('/{id}/services/{serviceId}', 'VendorServiceController@update');
    $router->post('/{id}/services/{serviceId}/delete', 'VendorServiceController@delete');
    
    // Vendor Documents
    $router->get('/{id}/documents', 'VendorDocumentController@index');
    $router->post('/{id}/documents', 'VendorDocumentController@store');
    $router->get('/{id}/documents/{documentId}', 'VendorDocumentController@show');
    $router->post('/{id}/documents/{documentId}/delete', 'VendorDocumentController@delete');
    $router->get('/{id}/documents/{documentId}/download', 'VendorDocumentController@download');
    
    // Vendor Contracts
    $router->get('/contracts', 'VendorContractController@index');
    $router->get('/contracts/create', 'VendorContractController@create');
    $router->post('/contracts', 'VendorContractController@store');
    $router->get('/contracts/{id}', 'VendorContractController@show');
    $router->get('/contracts/{id}/edit', 'VendorContractController@edit');
    $router->post('/contracts/{id}', 'VendorContractController@update');
    $router->post('/contracts/{id}/delete', 'VendorContractController@delete');
    $router->get('/contracts/{id}/renew', 'VendorContractController@renewForm');
    $router->post('/contracts/{id}/renew', 'VendorContractController@renew');
    $router->get('/contracts/{id}/history', 'VendorContractController@history');
    $router->get('/contracts/{id}/download', 'VendorContractController@download');
    
    // Vendor Performance
    $router->get('/{id}/performance', 'VendorPerformanceController@index');
    $router->get('/{id}/performance/metrics', 'VendorPerformanceController@metrics');
    $router->post('/{id}/performance/metrics', 'VendorPerformanceController@storeMetric');
    $router->get('/{id}/performance/evaluate', 'VendorPerformanceController@evaluateForm');
    $router->post('/{id}/performance/evaluate', 'VendorPerformanceController@evaluate');
    $router->get('/{id}/performance/history', 'VendorPerformanceController@history');
    
    // Vendor SLAs and Incidents
    $router->get('/{id}/sla', 'VendorSLAController@index');
    $router->post('/{id}/sla', 'VendorSLAController@store');
    $router->get('/{id}/incidents', 'VendorIncidentController@index');
    $router->get('/{id}/incidents/create', 'VendorIncidentController@create');
    $router->post('/{id}/incidents', 'VendorIncidentController@store');
    $router->get('/{id}/incidents/{incidentId}', 'VendorIncidentController@show');
    $router->post('/{id}/incidents/{incidentId}/resolve', 'VendorIncidentController@resolve');
    
    // Vendor Invoices and Payments
    $router->get('/{id}/invoices', 'VendorInvoiceController@index');
    $router->get('/{id}/invoices/create', 'VendorInvoiceController@create');
    $router->post('/{id}/invoices', 'VendorInvoiceController@store');
    $router->get('/{id}/invoices/{invoiceId}', 'VendorInvoiceController@show');
    $router->get('/{id}/invoices/{invoiceId}/edit', 'VendorInvoiceController@edit');
    $router->post('/{id}/invoices/{invoiceId}', 'VendorInvoiceController@update');
    $router->post('/{id}/invoices/{invoiceId}/approve', 'VendorInvoiceController@approve');
    $router->post('/{id}/invoices/{invoiceId}/reject', 'VendorInvoiceController@reject');
    $router->post('/{id}/invoices/{invoiceId}/pay', 'VendorInvoiceController@pay');
    $router->get('/{id}/invoices/{invoiceId}/download', 'VendorInvoiceController@download');
    
    // Vendor Portal Access
    $router->get('/{id}/portal-users', 'VendorPortalController@users');
    $router->post('/{id}/portal-users', 'VendorPortalController@storeUser');
    $router->post('/{id}/portal-users/{userId}/status', 'VendorPortalController@updateUserStatus');
    $router->get('/{id}/portal-activity', 'VendorPortalController@activity');
}); 