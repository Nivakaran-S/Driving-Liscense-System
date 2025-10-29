<?php

class PDF {
    
    public static function generateLicense($license, $download = true) {
        // Check if TCPDF is available
        if (class_exists('TCPDF')) {
            return self::generateWithTCPDF($license, $download);
        } else {
            return self::generateSimpleHTML($license, $download);
        }
    }
    
    private static function generateWithTCPDF($license, $download) {
        $pdf = new TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);
        
        $pdf->SetCreator(APP_NAME);
        $pdf->SetAuthor(APP_NAME);
        $pdf->SetTitle('Driving License - ' . $license['license_number']);
        
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        
        $pdf->SetMargins(15, 15, 15);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);
        
        $html = self::getLicenseHTML($license);
        $pdf->writeHTML($html, true, false, true, false, '');
        
        $filename = 'License_' . $license['license_number'] . '.pdf';
        
        if ($download) {
            $pdf->Output($filename, 'D');
        } else {
            $filepath = LICENSE_UPLOAD_PATH . $filename;
            $pdf->Output($filepath, 'F');
            return $filename;
        }
        
        exit();
    }
    
    private static function generateSimpleHTML($license, $download) {
        $html = self::getLicenseHTML($license);
        
        if ($download) {
            header('Content-Type: text/html; charset=UTF-8');
            header('Content-Disposition: attachment; filename="License_' . $license['license_number'] . '.html"');
            echo $html;
            exit();
        }
        
        return $html;
    }

    private static function getLicenseHTML($license) {
        $licenseType = ucfirst(str_replace('_', ' ', $license['license_type']));
        $issueDate = date('F d, Y', strtotime($license['issue_date']));
        $expiryDate = date('F d, Y', strtotime($license['expiry_date']));
        $licenseStatus = $license['is_temporary'] ? 'TEMPORARY' : 'PERMANENT';
        $currentDate = date('F d, Y H:i:s');
        
        $html = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; }
        .license-container {
            width: 800px;
            margin: 20px auto;
            border: 4px solid #003366;
            padding: 0;
        }
        .license-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
            padding: 20px;
        }
        .license-header h1 { margin: 0; font-size: 32px; }
        .license-header h2 { margin: 5px 0; font-size: 24px; }
        .license-header p { margin: 5px 0; }
        .license-body {
            padding: 30px;
            background: #f8f9fa;
        }
        .watermark {
            text-align: center;
            font-size: 36px;
            font-weight: bold;
            color: #dc3545;
            margin: 20px 0;
            text-transform: uppercase;
            letter-spacing: 5px;
            transform: rotate(-3deg);
            border: 3px dashed #dc3545;
            padding: 10px;
            display: inline-block;
            width: 100%;
        }
        .info-section {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .info-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .info-row:last-child { border-bottom: none; }
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
        .license-number {
            background: #003366;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 3px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .notice-box {
            background: #fff3cd;
            border-left: 5px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
        }
        .notice-box h3 {
            margin: 0 0 10px 0;
            color: #856404;
        }
        .notice-box ul {
            margin: 5px 0;
            padding-left: 20px;
        }
        .notice-box li {
            margin: 5px 0;
            color: #856404;
        }
        .footer {
            background: #343a40;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 11px;
        }
        .qr-placeholder {
            width: 100px;
            height: 100px;
            background: #ddd;
            display: inline-block;
            text-align: center;
            line-height: 100px;
            margin: 10px;
        }
    </style>
</head>
<body>
    <div class="license-container">
        <div class="license-header">
            <h1>{$_ENV['APP_NAME'] ?? 'Driving License System'}</h1>
            <h2>DRIVING LICENSE</h2>
            <p>Democratic Socialist Republic of Sri Lanka</p>
        </div>
        
        <div class="license-body">
            <div class="watermark">{$licenseStatus}</div>
            
            <div class="license-number">{$license['license_number']}</div>
            
            <div class="info-section">
                <h3 style="margin-top:0; color: #003366;">Personal Information</h3>
                <div class="info-row">
                    <div class="info-label">Full Name:</div>
                    <div class="info-value">{$license['full_name']}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">National ID:</div>
                    <div class="info-value">{$license['national_id']}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Date of Birth:</div>
                    <div class="info-value">{$license['date_of_birth']}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Address:</div>
                    <div class="info-value">{$license['address']}</div>
                </div>
            </div>
            
            <div class="info-section">
                <h3 style="margin-top:0; color: #003366;">License Details</h3>
                <div class="info-row">
                    <div class="info-label">License Type:</div>
                    <div class="info-value"><strong>{$licenseType}</strong></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Issue Date:</div>
                    <div class="info-value">{$issueDate}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Expiry Date:</div>
                    <div class="info-value"><strong>{$expiryDate}</strong></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Application Reference:</div>
                    <div class="info-value">{$license['reference_id']}</div>
                </div>
            </div>
            
            <div class="notice-box">
                <h3>⚠️ Important Notice</h3>
                <ul>
                    <li>This is a TEMPORARY driving license valid for 6 months from the date of issue.</li>
                    <li>The permanent license card will be mailed to your registered address within 30 days.</li>
                    <li>You must carry this document while driving until you receive the permanent license.</li>
                    <li>This license is valid only for the specified vehicle category: <strong>{$licenseType}</strong></li>
                    <li>Violation of traffic rules may result in license suspension or cancellation.</li>
                    <li>Report any changes to your address or personal details immediately.</li>
                </ul>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>This is a computer-generated document. No signature required.</strong></p>
            <p>Generated on: {$currentDate} | Verification Code: {$license['license_number']}</p>
            <p>For verification, visit: {$_ENV['BASE_URL'] ?? 'http://localhost'}/license/verify</p>
        </div>
    </div>
</body>
</html>
HTML;
        
        return $html;
    }
    
    public static function generateReceipt($data) {
    }
}
?>