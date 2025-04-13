<?php

namespace App\Services;

use getID3;
use Illuminate\Support\Facades\Storage;

class AudioService
{
    private $getID3;

    public function __construct()
    {
        $this->getID3 = new getID3();
        $this->getID3->setOptionMD5Data(true);
        $this->getID3->setOptionMD5DataSource(true);
    }

    public function getDuration($filePath)
    {
        try {
            // Lấy đường dẫn đầy đủ của file
            $fullPath = Storage::disk('public')->path($filePath);

            // Phân tích file audio
            $fileInfo = $this->getID3->analyze($fullPath);

            // Lấy thời lượng từ thông tin file
            if (isset($fileInfo['playtime_seconds'])) {
                return $fileInfo['playtime_seconds'];
            }

            return 0;
        } catch (\Exception $e) {
            \Log::error('Error getting audio duration: ' . $e->getMessage());
            return 0;
        }
    }
}
