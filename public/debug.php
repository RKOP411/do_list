<?php
// Turn on all error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'C:/xampp/htdocs/do_list/writable/logs/php_error.log');

echo "<h1>Debugging Do List Application</h1>";

// Test 1: Check PHP version
echo "<h3>Test 1: PHP Version</h3>";
echo "PHP Version: " . phpversion() . "<br>";
echo (version_compare(phpversion(), '8.2.0', '>=')) ? "✅ PHP 8.2+ OK<br>" : "❌ PHP 8.2+ Required<br>";

// Test 2: Check required extensions
echo "<h3>Test 2: Required Extensions</h3>";
$extensions = ['mysqli', 'pdo_mysql', 'json', 'mbstring', 'curl', 'intl'];
foreach ($extensions as $ext) {
    echo extension_loaded($ext) ? "✅ $ext loaded<br>" : "❌ $ext NOT loaded<br>";
}

// Test 3: Check paths
echo "<h3>Test 3: Paths</h3>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Script Path: " . __FILE__ . "<br>";
echo "Framework Path: " . realpath(__DIR__ . '/../') . "<br>";

// Test 4: Try to bootstrap CodeIgniter
echo "<h3>Test 4: Bootstrap CodeIgniter</h3>";
try {
    // Define path constants if not defined
    define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
    define('ROOTPATH', realpath(__DIR__ . '/../') . DIRECTORY_SEPARATOR);
    define('APPPATH', ROOTPATH . 'app' . DIRECTORY_SEPARATOR);
    define('SYSTEMPATH', ROOTPATH . 'vendor/codeigniter4/framework/system' . DIRECTORY_SEPARATOR);
    define('WRITEPATH', ROOTPATH . 'writable' . DIRECTORY_SEPARATOR);
    
    echo "✅ Paths defined<br>";
    
    // Load the framework
    require_once SYSTEMPATH . 'bootstrap.php';
    echo "✅ Framework loaded<br>";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . "<br>";
    echo "Line: " . $e->getLine() . "<br>";
}

// Test 5: Database connection
echo "<h3>Test 5: Database Connection</h3>";
try {
    $conn = new mysqli('localhost', 'root', '', 'do_list_db');
    if ($conn->connect_error) {
        throw new Exception($conn->connect_error);
    }
    echo "✅ Database connected successfully<br>";
    
    $result = $conn->query("SHOW TABLES");
    echo "✅ Query executed<br>";
    
    if ($result->num_rows > 0) {
        echo "Tables found: ";
        while($row = $result->fetch_array()) {
            echo $row[0] . " ";
        }
        echo "<br>";
    } else {
        echo "No tables found. Creating tasks table...<br>";
        $sql = "CREATE TABLE IF NOT EXISTS tasks (
            id INT AUTO_INCREMENT PRIMARY KEY,
            queue_num INT NOT NULL,
            description TEXT NOT NULL,
            date DATE NOT NULL,
            status ENUM('Done', 'Not done') DEFAULT 'Not done',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        if ($conn->query($sql)) {
            echo "✅ Tasks table created<br>";
        }
    }
    $conn->close();
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "<br>";
}

// Test 6: Check writable permissions
echo "<h3>Test 6: Writable Directory Permissions</h3>";
$writable_path = ROOTPATH . 'writable';
echo "Writable path: $writable_path<br>";
echo is_writable($writable_path) ? "✅ Directory is writable<br>" : "❌ Directory is NOT writable<br>";
echo is_writable($writable_path . '/cache') ? "✅ Cache writable<br>" : "❌ Cache not writable<br>";
echo is_writable($writable_path . '/logs') ? "✅ Logs writable<br>" : "❌ Logs not writable<br>";
?>