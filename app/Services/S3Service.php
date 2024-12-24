<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\UploadedFile;

class S3Service
{
    private $disk;

    public function __construct()
    {
        $this->disk = Storage::disk('s3');
    }

    public function uploadCsvFile($export, string $filename, ?string $prefix = null): string
    {
        try {
            $prefix = trim($prefix, '/');
            $path = $prefix ? env('AWS_FOLDER_PATH') . "/{$prefix}/{$filename}" : env('AWS_FOLDER_PATH') . "/{$filename}";
            
            Excel::store(
                $export,
                $path,
                's3',
                \Maatwebsite\Excel\Excel::CSV,
                [
                    'visibility' => 'public'
                ]
            );

            return $this->getFileUrl($path);
        } catch (\Exception $e) {
            throw new \Exception("Không thể upload file lên S3: " . $e->getMessage());
        }
    }

    public function deleteFile(string $path): bool
    {
        try {
            return $this->disk->delete($path);
        } catch (\Exception $e) {
            throw new \Exception("Không thể xóa file từ S3: " . $e->getMessage());
        }
    }

    public function getFileUrl(string $path): string
    {
        return $this->disk->url($path);
    }

    public function fileExists(string $path): bool
    {
        return $this->disk->exists($path);
    }

    public function upload(UploadedFile $file, string $folder = ''): array
    {
        try {
            $filename = date('YmdHis') . '_' . $file->getClientOriginalName();
            
            $awsFolderPath = env('AWS_FOLDER_PATH', '');
            $folder = trim($folder, '/');
            
            $path = $awsFolderPath ? "{$awsFolderPath}/{$folder}/{$filename}" : "{$folder}/{$filename}";
            
            $path = preg_replace('#/+#', '/', $path);
            $path = trim($path, '/');

            $this->disk->putFileAs(
                dirname($path),
                $file,
                basename($path),
                [
                    'visibility' => 'public'
                ]
            );

            return [
                'success' => true,
                'url' => $this->getFileUrl($path),
                'path' => $path
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
} 
