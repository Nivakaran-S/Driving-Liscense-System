<?php

class LicenseController extends BaseController {
    private $licenseModel;
    private $applicationModel;
    
    public function __construct() {
        $this->licenseModel = new License();
        $this->applicationModel = new Application();
    }
    
  
    public function view($licenseId) {
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
        
  
        $this->generateLicensePDF($license);
    }
    
  
    private function generateLicensePDF($license) {
  
        if (!class_exists('TCPDF')) {
  
            $this->generateSimpleLicenseHTML($license);
            return;
        }
        
  
        $pdf = new TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);
        
  
        $pdf->SetCreator(APP_NAME);
        $pdf->SetAuthor(APP_NAME);
        $pdf->SetTitle('Temporary Driving License - ' . $license['license_number']);
  
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        
  
        $pdf->SetMargins(15, 15, 15);
  
        $pdf->AddPage();
        
  
        $pdf->SetFont('helvetica', '', 10);
        
  
        $html = $this->getLicenseHTML($license);
        
  
        $pdf->writeHTML($html, true, false, true, false, '');
        
  
        $filename = 'License_' . $license['license_number'] . '.pdf';
        $pdf->Output($filename, 'D');
        exit();
    }
    
  
    private function generateSimpleLicenseHTML($license) {
        header('Content-Type: text/html; charset=UTF-8');
        echo $this->getLicenseHTML($license);
        exit();
    }
    
  
    private function getLicenseHTML($license) {
        $licenseType = ucfirst(str_replace('_', ' ', $license['license_type']));
        $issueDate = date('F d, Y', strtotime($license['issue_date']));
        $expiryDate = date('F d, Y', strtotime($license['expiry_date']));
        $licenseStatus = $license['is_temporary'] ? 'TEMPORARY' : 'PERMANENT';
        
        $html = '
        <style>
            .license-card {
                border: 3px solid #003366;
                padding: 20px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                border-radius: 15px;
            }
            .license-header {
                text-align: center;
                margin-bottom: 20px;
                border-bottom: 2px solid white;
                padding-bottom: 10px;
            }
            .license-body {
                background: rgba(255,255,255,0.9);
                color: #333;
                padding: 20px;
                border-radius: 10px;
            }
            .field {
                margin: 10px 0;
                padding: 8px;
                border-bottom: 1px solid #ddd;
            }
            .field-label {
                font-weight: bold;
                color: #003366;
                display: inline-block;
                width: 180px;
            }
            .field-value {
                display: inline-block;
            }
            .watermark {
                text-align: center;
                font-size: 24px;
                font-weight: bold;
                color: #ff0000;
                margin: 15px 0;
                transform: rotate(-5deg);
            }
            .footer {
                margin-top: 20px;
                text-align: center;
                font-size: 10px;
                color: white;
            }
        </style>
        
        <div class="license-card">
            <div class="license-header">
                <h1 style="margin: 0; font-size: 28px;">' . APP_NAME . '</h1>
                <h2 style="margin: 5px 0; font-size: 20px;">DRIVING LICENSE</h2>
                <p style="margin: 5px 0; font-size: 14px;">Sri Lanka</p>
            </div>
            
            <div class="license-body">
                <div class="watermark">' . $licenseStatus . '</div>
                
                <div class="field">
                    <span class="field-label">License Number:</span>
                    <span class="field-value"><strong>' . $license['license_number'] . '</strong></span>
                </div>
                
                <div class="field">
                    <span class="field-label">Full Name:</span>
                    <span class="field-value">' . strtoupper($license['full_name']) . '</span>
                </div>
                
                <div class="field">
                    <span class="field-label">National ID:</span>
                    <span class="field-value">' . $license['national_id'] . '</span>
                </div>
                
                <div class="field">
                    <span class="field-label">Date of Birth:</span>
                    <span class="field-value">' . date('F d, Y', strtotime($license['date_of_birth'])) . '</span>
                </div>
                
                <div class="field">
                    <span class="field-label">Address:</span>
                    <span class="field-value">' . $license['address'] . '</span>
                </div>
                
                <div class="field">
                    <span class="field-label">License Type:</span>
                    <span class="field-value"><strong>' . $licenseType . '</strong></span>
                </div>
                
                <div class="field">
                    <span class="field-label">Issue Date:</span>
                    <span class="field-value">' . $issueDate . '</span>
                </div>
                
                <div class="field">
                    <span class="field-label">Expiry Date:</span>
                    <span class="field-value"><strong>' . $expiryDate . '</strong></span>
                </div>
                
                <div style="margin-top: 20px; padding: 10px; background: #fff3cd; border-left: 4px solid #ffc107;">
                    <strong>Important Notes:</strong><br>
                    • This is a temporary driving license valid for 6 months.<br>
                    • The permanent license will be posted to your registered address.<br>
                    • Always carry this license while driving.<br>
                    • Follow all traffic rules and regulations.
                </div>
            </div>
            
            <div class="footer">
                <p>This is a computer-generated document. No signature required.</p>
                <p>Reference ID: ' . $license['reference_id'] . '</p>
                <p>Generated on: ' . date('F d, Y H:i:s') . '</p>
            </div>
        </div>';
        
        return $html;
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
?>