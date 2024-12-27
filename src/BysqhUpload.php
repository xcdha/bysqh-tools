<?php
// +--------------------------------------------+
// | Name: BysqhUpload 文件上传类
// +--------------------------------------------+
// | User: Bysqh
// +--------------------------------------------+
// | Author: bysqh <www.bysqh.cn>
// +--------------------------------------------+
// | Date: 2024-04-19 15:02
// +--------------------------------------------+
namespace bysqh;

class BysqhUpload
{
    private $uploadPath;
    private $allowedFileTypes;
    private $maxFileSizeKB;
    private $useRandomFileName;
    private $useFilePrefix;
    private $filePrefix;

    public function __construct($uploadPath, $allowedFileTypes, $maxFileSizeKB, $useRandomFileName = false, $useFilePrefix = true, $filePrefix = '')
    {
        $this->uploadPath = $uploadPath;
        $this->allowedFileTypes = $allowedFileTypes;
        $this->maxFileSizeKB = $maxFileSizeKB;
        $this->useRandomFileName = $useRandomFileName;
        $this->useFilePrefix = $useFilePrefix;
        $this->filePrefix = $filePrefix;
    }

    public function uploadFile()
    {
        if (!is_dir($this->uploadPath)) {
            return ['status' => '0', 'info' => '上传路径不存在'];
        }

        $file = $_FILES['file'];
        $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_error = $file['error'];
        $file_type = strtolower(pathinfo($file_name, PATHINFO_EXTENSION)); // 使用 strtolower 进行一致的比较

        if (!in_array($file_type, $this->allowedFileTypes)) {
            return ['status' => '0', 'info' => '文件类型错误！'];
        }

        if ($file_error === 0) {
            // 检查文件大小是否超过限制
            if (($file['size'] / 1024) > $this->maxFileSizeKB) {
                return ['status' => '0', 'info' => '文件大小超过限制！'];
            }

            // 生成文件名
            $unique_filename = $this->useRandomFileName ? $this->generateRandomFileName($file_type) : $file_name;
            $file_path = $this->uploadPath . ($this->useFilePrefix ? ($this->filePrefix . $unique_filename) : $unique_filename);

            if (move_uploaded_file($file_tmp, $file_path)) {
                $protocol = $_SERVER['REQUEST_SCHEME']; // 获取协议头（http或https）
                $data = [
                    'status' => '1',
                    'info' => '上传成功',
                    'location' => $protocol . '://' . $_SERVER['HTTP_HOST'] . '/' . $file_path, // 包含协议头的完整 URL
                    'file_name' => $unique_filename // 返回文件名
                ];
            } else {
                $data = [
                    'status' => '0',
                    'info' => '上传失败！',
                    'location' => '文件不符合要求'
                ];
            }
        } else {
            $data = [
                'status' => '0',
                'info' => '上传失败！',
                'location' => '文件不符合要求'
            ];
        }

        return $data;
    }

    private function generateRandomFileName($file_type)
    {
        return date('YmdHi') . '_' . uniqid() . '.' . $file_type;
    }
}