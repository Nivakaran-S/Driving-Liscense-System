<?php

class LicenseController extends BaseController {
    private $licenseModel;
    private $applicationModel;
    
    public function __construct() {
        $this->licenseModel = new License();
        $this->applicationModel = new Application();
    }
    
    public function viewLicense($licenseId) {
        $this->requireAuth();
        
        $license = $this->licenseModel->getById($licenseId);
        
        if (!$license) {
            $this->setFlash('error', 'License not found');
            $this->redirect('dashboard/' . Auth::getRole());
            return;
        }
        
        $user = $this->getCurrentUser();
        $role = Auth::getRole();
        
        if ($role === 'driver' && $license['user_id'] != $user['user_id']) {
            $this->setFlash('error', 'Access denied');
            $this->redirect('dashboard/driver');
            return;
        }
        
        $this->view('license/view', ['license' => $license]);
    }
    
    public function download($licenseId) {
        $this->requireAuth();
        
        $license = $this->licenseModel->getById($licenseId);
        
        if (!$license) {
            $this->setFlash('error', 'License not found');
            $this->redirect('dashboard/' . Auth::getRole());
            return;
        }
        
        $user = $this->getCurrentUser();
        $role = Auth::getRole();
        
        if ($role === 'driver' && $license['user_id'] != $user['user_id']) {
            $this->setFlash('error', 'Access denied');
            $this->redirect('dashboard/driver');
            return;
        }
        
        $this->generateLicenseHTML($license);
    }
    
    private function generateLicenseHTML($license) {
        $licenseType = ucfirst(str_replace('_', ' ', $license['license_type']));
        $issueDate = date('F d, Y', strtotime($license['issue_date']));
        $expiryDate = date('F d, Y', strtotime($license['expiry_date']));
        $licenseStatus = $license['is_temporary'] ? 'TEMPORARY' : 'PERMANENT';
        
        // Set headers for download
        header('Content-Type: text/html; charset=UTF-8');
        header('Content-Disposition: attachment; filename="License_' . $license['license_number'] . '.html"');
        
        // Generate HTML
        echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driving License - ' . htmlspecialchars($license['license_number']) . '</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        .license-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .license-card {
            border: 4px solid #003366;
            padding: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .license-header {
            text-align: center;
            padding: 30px 20px;
            border-bottom: 3px solid white;
        }
        .license-header h1 {
            margin: 0;
            font-size: 36px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .license-header h2 {
            margin: 10px 0;
            font-size: 24px;
            font-weight: normal;
        }
        .license-header p {
            margin: 5px 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .license-body {
            background: rgba(255,255,255,0.95);
            color: #333;
            padding: 30px;
        }
        .watermark {
            text-align: center;
            font-size: 42px;
            font-weight: bold;
            color: #dc3545;
            margin: 20px 0;
            text-transform: uppercase;
            letter-spacing: 8px;
            transform: rotate(-5deg);
            border: 4px dashed #dc3545;
            padding: 15px;
            display: inline-block;
            width: 100%;
            box-sizing: border-box;
        }
        .license-number {
            background: #003366;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 4px;
            margin: 20px 0;
            border-radius: 8px;
            font-family: "Courier New", monospace;
        }
        .info-section {
            background: #f8f9fa;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            border-left: 5px solid #003366;
        }
        .info-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: bold;
            color: #003366;
            width: 200px;
            flex-shrink: 0;
        }
        .info-value {
            color: #333;
            flex-grow: 1;
        }
        .notice-box {
            background: #fff3cd;
            border-left: 6px solid #ffc107;
            padding: 20px;
            margin: 20px 0;
        }
        .notice-box h3 {
            margin: 0 0 15px 0;
            color: #856404;
            font-size: 18px;
        }
        .notice-box ul {
            margin: 10px 0;
            padding-left: 25px;
        }
        .notice-box li {
            margin: 8px 0;
            color: #856404;
            line-height: 1.6;
        }
        .footer {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 12px;
        }
        .footer p {
            margin: 5px 0;
        }
        .footer strong {
            font-size: 14px;
        }
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .license-container {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="license-container">
        <div class="license-card">
            <div class="license-header">
                <h1>' . htmlspecialchars(APP_NAME) . '</h1>
                <h2>DRIVING LICENSE</h2>
                <p>Democratic Socialist Republic of Sri Lanka</p>
            </div>
            
            <div class="license-body">
                <div class="watermark">' . $licenseStatus . '</div>
                
                <div class="license-number">' . htmlspecialchars($license['license_number']) . '</div>
                
                <div class="info-section">
                    <h3 style="margin-top:0; color: #003366; font-size: 20px;">Personal Information</h3>
                    <div class="info-row">
                        <div class="info-label">Full Name:</div>
                        <div class="info-value">' . strtoupper(htmlspecialchars($license['full_name'])) . '</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">National ID:</div>
                        <div class="info-value">' . htmlspecialchars($license['national_id']) . '</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Date of Birth:</div>
                        <div class="info-value">' . date('F d, Y', strtotime($license['date_of_birth'])) . '</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Address:</div>
                        <div class="info-value">' . htmlspecialchars($license['address']) . '</div>
                    </div>
                </div>
                
                <div class="info-section">
                    <h3 style="margin-top:0; color: #003366; font-size: 20px;">License Details</h3>
                    <div class="info-row">
                        <div class="info-label">License Type:</div>
                        <div class="info-value"><strong>' . htmlspecialchars($licenseType) . '</strong></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Issue Date:</div>
                        <div class="info-value">' . $issueDate . '</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Expiry Date:</div>
                        <div class="info-value"><strong>' . $expiryDate . '</strong></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Application Reference:</div>
                        <div class="info-value">' . htmlspecialchars($license['reference_id']) . '</div>
                    </div>
                </div>
                
                <div class="notice-box">
                    <h3>⚠ Important Notice</h3>
                    <ul>
                        <li>This is a <strong>TEMPORARY</strong> driving license valid for 6 months from the date of issue.</li>
                        <li>The permanent license card will be mailed to your registered address within 30 days.</li>
                        <li>You <strong>must carry</strong> this document while driving until you receive the permanent license.</li>
                        <li>This license is valid only for the specified vehicle category: <strong>' . htmlspecialchars($licenseType) . '</strong></li>
                        <li>Violation of traffic rules may result in license suspension or cancellation.</li>
                        <li>Report any changes to your address or personal details immediately.</li>
                        <li>In case of loss or damage, report immediately to the nearest licensing office.</li>
                    </ul>
                </div>
            </div>
            
            <div class="footer">
                <p><strong>This is a computer-generated document. No signature required.</strong></p>
                <p>Generated on: ' . date('F d, Y H:i:s') . ' | Verification Code: ' . htmlspecialchars($license['license_number']) . '</p>
                <p>For verification, visit: ' . htmlspecialchars(BASE_URL) . '/license/verify</p>
                <p style="margin-top: 15px; font-size: 10px;">© ' . date('Y') . ' ' . htmlspecialchars(APP_NAME) . '. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>';
        exit();
    }
    
    public function list() {
        $this->requireAuth();
        $this->requireRole('admin');
        
        $filters = [];
        if (isset($_GET['license_type'])) {
            $filters['license_type'] = $this->sanitize($_GET['license_type']);
        }
        if (isset($_GET['is_temporary'])) {
            $filters['is_temporary'] = $this->sanitize($_GET['is_temporary']);
        }
        if (isset($_GET['expired'])) {
            $filters['expired'] = $this->sanitize($_GET['expired']);
        }
        
        $licenses = $this->licenseModel->getAll($filters);
        
        $this->view('license/list', ['licenses' => $licenses, 'filters' => $filters]);
    }
    
    public function verify() {
        if ($this->isPost()) {
            $licenseNumber = $this->sanitize($_POST['license_number']);
            
            if (empty($licenseNumber)) {
                $this->setFlash('error', 'Please enter a license number');
                $this->view('license/verify');
                return;
            }
            
            $license = $this->licenseModel->getByLicenseNumber($licenseNumber);
            
            if ($license) {
                $isValid = $this->licenseModel->isValid($license['license_id']);
                $this->view('license/verify', [
                    'license' => $license,
                    'isValid' => $isValid
                ]);
            } else {
                $this->setFlash('error', 'License not found');
                $this->view('license/verify', ['license_number' => $licenseNumber]);
            }
        } else {
            $this->view('license/verify');
        }
    }
}