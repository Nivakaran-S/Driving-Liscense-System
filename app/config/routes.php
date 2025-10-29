<?php

$routes = [
    '' => 'PublicController@index',
    'home' => 'PublicController@index',
    'check-status' => 'PublicController@checkStatus',
    'about' => 'PublicController@about',
    'contact' => 'PublicController@contact',
    
    'login' => 'AuthController@login',
    'register' => 'AuthController@register',
    'logout' => 'AuthController@logout',
    
    'dashboard' => 'DashboardController@index',
    'dashboard/admin' => 'DashboardController@admin',
    'dashboard/driver' => 'DashboardController@driver',
    'dashboard/medical' => 'DashboardController@medical',
    'dashboard/evaluator' => 'DashboardController@evaluator',
    
    'application/create' => 'ApplicationController@create',
    'application/viewApplication/:id' => 'ApplicationController@viewApplication',
    'application/list' => 'ApplicationController@list',
    'application/bookMedicalSlot/:id' => 'ApplicationController@bookMedicalSlot',
    'application/bookDrivingSlot/:id' => 'ApplicationController@bookDrivingSlot',
    
    'medical/evaluate/:id' => 'MedicalController@evaluate',
    'medical/viewEvaluation/:id' => 'MedicalController@viewEvaluation',
    'medical/list' => 'MedicalController@list',
    
    'driving/evaluate/:id' => 'DrivingTestController@evaluate',
    'driving/viewEvaluation/:id' => 'DrivingTestController@viewEvaluation',
    'driving/list' => 'DrivingTestController@list',
    
    'license/viewLicense/:id' => 'LicenseController@viewLicense',
    'license/download/:id' => 'LicenseController@download',
    'license/list' => 'LicenseController@list',
    'license/verify' => 'LicenseController@verify',
    
    'slot/medical' => 'SlotController@medical',
    'slot/createMedical' => 'SlotController@createMedical',
    'slot/deleteMedical/:id' => 'SlotController@deleteMedical',
    'slot/toggleMedical/:id' => 'SlotController@toggleMedical',
    'slot/driving' => 'SlotController@driving',
    'slot/createDriving' => 'SlotController@createDriving',
    'slot/deleteDriving/:id' => 'SlotController@deleteDriving',
    'slot/toggleDriving/:id' => 'SlotController@toggleDriving',
];

return $routes;