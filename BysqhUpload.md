# Bysqh - Upload文件上传类
博客：http://www.bysqh.cn/
> BysqhUpload文件上传类
> 
> 支持设置上传路径，允许的文件类型，文件大小限制，随机文件名，文件名前缀
>
> PHP7.0+
>
~~~
//使用示例
require 'BysqhUpload.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadPath = 'Public/uploads/'; // 设置上传路径
    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0777, true);
    }
    // 自定义上传路径和允许的文件类型
    $allowedFileTypes = ['zip', 'php', 'jpg']; // 添加允许的文件扩展名
    $maxFileSizeKB = 10240; // 10 MB 文件大小限制，以 KB 为单位
    $useRandomFileName = false; // 是否启用随机文件名
    $useFilePrefix = true; // 是否启用文件名前缀
    $filePrefix = 'Bysqh_'; // 设置文件名前缀
    $BysqhUpload = new BysqhUpload($uploadPath, $allowedFileTypes, $maxFileSizeKB, $useRandomFileName, $useFilePrefix, $filePrefix);
    $result = $BysqhUpload->uploadFile();
    // 将 JSON 响应返回给客户端
    header('Content-Type: application/json');
    exit(json_encode($result));
}
~~~

> 完整示例请下载运行index.php
