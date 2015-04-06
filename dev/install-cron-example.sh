#!/bin/bash

ssh root@127.0.0.1 <<'ENDSSH'
sudo crontab -l > backup-cron
echo "01 02 01 * * php -f /opt/wikipedia-download/src/cron/GenerateListOfFilesForDownload.php" >> backup-cron
echo "10 02 01 * * cd /opt/wikipedia-download/temp/ && sh /opt/wikipedia-download/temp/files-for-download.sh" >> backup-cron
echo "01 02 02 * * php -f /opt/wikipedia-download/src/cron/InsertDataFromFilesToDatabase.php" >> backup-cron
echo "*/6 * * * * php -f /opt/wikipedia-download/src/cron/save-missing-csfd-in-inbox.php" >> backup-cron
sudo crontab backup-cron
rm backup-cron
ENDSSH
