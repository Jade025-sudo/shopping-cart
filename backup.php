<?php
// Set filename with timestamp
$backupFile = 'backup_' . date("Y-m-d_H-i-s") . '.sql';

// Run mysqldump command (adjust path if needed)
exec("C:/xampp/mysql/bin/mysqldump -u root shop_db > $backupFile", $output, $result);

if ($result === 0) {
    echo "✅ Backup successful! File saved as: <b>$backupFile</b>";
} else {
    echo "❌ Backup failed. Please check your server settings.";
}
?>
