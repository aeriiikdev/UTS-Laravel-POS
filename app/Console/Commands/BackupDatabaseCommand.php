<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Transaction;


class BackupDatabaseCommand extends Command
{
    protected $signature = 'pos:backup';
    protected $description = 'Backup database to SQL file';

    public function handle()
    {
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');
        
        $filename = 'backup_' . date('Y-m-d_His') . '.sql';
        $path = storage_path('app/backups');
        
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
        
        $fullPath = $path . '/' . $filename;
        
        $command = "mysqldump -h {$host} -u {$username} ";
        if ($password) {
            $command .= "-p{$password} ";
        }
        $command .= "{$database} > {$fullPath}";
        
        $this->info("Creating backup...");
        exec($command, $output, $returnVar);
        
        if ($returnVar === 0) {
            $this->info("✅ Backup created successfully!");
            $this->line("File: {$fullPath}");
            $this->line("Size: " . $this->formatBytes(filesize($fullPath)));
        } else {
            $this->error("❌ Backup failed!");
        }
        
        return $returnVar;
    }
    
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
