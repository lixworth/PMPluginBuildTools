<?php
/**
 * Created by PhpStorm.
 *
 * @Package Name: PluginBuild
 * @Author: LixWorth<lixworth@outlook.com>
 * User: LixWorth
 * Date: 2020/12/27
 * Time: 9:36
 */
$args = getopt('d:b:');

if(isset($args['d'])){
    if(substr($args['d'], -1) == "/"){
        $dist = $args['d'];
    }else{
        $dist = $args['d']."/";
    }

}else{
    $dist = __DIR__ . '/dist/';
}
if(isset($args['b'])){
    if(substr($args['b'], -1) == "/"){
        $build = $args['b'];
    }else{
        $build = $args['b']."/";
    }
}else{
    $build = __DIR__ . '/plugins/';
}
echo "=============================== BUILD Plugin Slim =========m====================== \n";
echo "====== 程序版本: build-pocketmine-plugin-emojicraft 1.0-maomao \n";
echo "====== 运行系统: " . php_uname() . "\n";
echo "====== PHP版本: " . PHP_VERSION . "\n";
echo "====== PHP运行方式: " . php_sapi_name() . "\n";
echo "====== PHP路径: " . DEFAULT_INCLUDE_PATH . "\n";
echo "====== 设定插件源码目录为: " . $build . "\n";
echo "====== 设定打包插件目录: " . $dist . "\n";

$handle = scandir($build);
$plugins = array();
$plugins_list = null;
for ($i = 0; $i < count($handle); $i++) {
    if (is_file($build.$handle[$i]) || $handle[$i] == "." || $handle[$i] == ".." || $handle[$i] == "disk" || $handle[$i] == ".git" || $handle[$i] == ".idea" || $handle[$i] == ".vscode") {
        continue;
    } else {
        if (file_exists($build . $handle[$i] . "/plugin.yml")) {
            $plugins[] = $handle[$i];
        } else {
            continue;
        }
    }
}
for ($i = 0; $i < count($plugins); $i++) {
    if ($i == 0) {
        $plugins_list = $plugins[$i];
    } else {
        $plugins_list = $plugins_list . "," . $plugins[$i];
    }
}
echo "====== 需要打包插件: " . $plugins_list . " (共" . count($plugins) . "个)\n";
for ($i = 0; $i < count($plugins); $i++) {
    $pathname = $plugins[$i];
    echo "====== 队列: (" . ($i + 1) . "/" . count($plugins) . ") 插件：" . $pathname . " 开始打包.\n";
    $exts = ['php', 'yml', 'json'];            // 需要打包的文件后缀
    $dir = $build . $pathname;             // 需要打包的目录
    $file = $pathname . ".phar";       // 包的名称, 注意它不仅仅是一个文件名, 在stub中也会作为入口前缀
    if (file_exists($dist . $file)) {
        unlink($dist . $file);
        echo "====== 注意：插件 " . $pathname . " 已存在，进行删除处理。\n";
    }
    $phar = new Phar($dist . $file, FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::KEY_AS_FILENAME, $file);
    $phar->startBuffering();
// 将后缀名相关的文件打包
/*    foreach ($exts as $ext) {
        $phar->buildFromDirectory($dir, '/\.' . $ext . '$/');
    }*/
    $phar->buildFromDirectory($dir);
    $phar->stopBuffering();
// 打包完成
    echo "====== 插件：" . $pathname . " 打包成功.\n";
}
echo "=============================== BUILD MAO MAO SoftWare ===============================";