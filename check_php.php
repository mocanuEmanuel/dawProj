<?php
echo "PHP Version: " . phpversion() . "\n";
echo "PDO Installed: " . (extension_loaded('pdo') ? 'Yes' : 'No') . "\n";
echo "PDO SQLite Installed: " . (extension_loaded('pdo_sqlite') ? 'Yes' : 'No') . "\n";
echo "SQLite3 Installed: " . (extension_loaded('sqlite3') ? 'Yes' : 'No') . "\n";
echo "Curl Installed: " . (extension_loaded('curl') ? 'Yes' : 'No') . "\n";
echo "INI File: " . php_ini_loaded_file() . "\n";
?>
