<?php
/**
 * Quick verification script to check if intl extension is loaded
 * Access this file in your browser: http://localhost/CHAKANOKS/check_intl.php
 */

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>intl Extension Check</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .success { color: #28a745; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
        .info { background: #e9ecef; padding: 10px; border-radius: 4px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>PHP intl Extension Check</h1>
        
        <?php
        $intlLoaded = extension_loaded('intl');
        $intlVersion = $intlLoaded ? INTL_ICU_VERSION : 'N/A';
        $phpVersion = PHP_VERSION;
        
        if ($intlLoaded) {
            echo '<p class="success">✓ intl extension is LOADED and working!</p>';
            echo '<div class="info">';
            echo '<strong>PHP Version:</strong> ' . htmlspecialchars($phpVersion) . '<br>';
            echo '<strong>intl ICU Version:</strong> ' . htmlspecialchars($intlVersion) . '<br>';
            echo '<strong>Status:</strong> Ready to use with CodeIgniter 4';
            echo '</div>';
        } else {
            echo '<p class="error">✗ intl extension is NOT loaded!</p>';
            echo '<div class="info">';
            echo 'Please enable the intl extension in your php.ini file.<br>';
            echo '<strong>Location:</strong> C:\\xampp\\php\\php.ini<br>';
            echo '<strong>Line to uncomment:</strong> extension=intl';
            echo '</div>';
        }
        ?>
        
        <hr>
        <p><small>You can delete this file after verification.</small></p>
    </div>
</body>
</html>

