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
    'application/view/:id' => 'ApplicationController@view',
    'application/list' => 'ApplicationController@list',
    'application/book-medical/:id' => 'ApplicationController@bookMedicalSlot',
    'application/book-driving/:id' => 'ApplicationController@bookDrivingSlot',
    
    
    'medical/evaluate/:id' => 'MedicalController@evaluate',
    'medical/view/:id' => 'MedicalController@view',
    'medical/list' => 'MedicalController@list',
    
    
    'driving/evaluate/:id' => 'DrivingTestController@evaluate',
    'driving/view/:id' => 'DrivingTestController@view',
    'driving/list' => 'DrivingTestController@list',
    
    
    'license/view/:id' => 'LicenseController@view',
    'license/download/:id' => 'LicenseController@download',
    'license/list' => 'LicenseController@list',
    'license/verify' => 'LicenseController@verify',
    
    'slot/medical' => 'SlotController@medical',
    'slot/medical/create' => 'SlotController@createMedical',
    'slot/medical/delete/:id' => 'SlotController@deleteMedical',
    'slot/medical/toggle/:id' => 'SlotController@toggleMedical',
    'slot/driving' => 'SlotController@driving',
    'slot/driving/create' => 'SlotController@createDriving',
    'slot/driving/delete/:id' => 'SlotController@deleteDriving',
    'slot/driving/toggle/:id' => 'SlotController@toggleDriving',
];

return $routes;
?>