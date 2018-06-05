<?php
// project's base domain, a valid url
define('BASE_DOMAIN', '');
// define('BASE_DOMAIN', 'http://www.pad.com');
// project's secondary dirtory, a string start without slash, e.g. '/project'
define('SECONDARY_DIR', 'page');
// project's static resource url, a cdn url is better
define('ASSETS_PATH', BASE_DOMAIN . 'http://obqo2833w.bkt.clouddn.com/');

// a dirtory stores project's page files
define('PAGE_PATH', __DIR__);

define('BUILD_PATH', '_demo/');

define('SECONDARY_DIR_LENGTH', strlen(SECONDARY_DIR));

// 获取访问路径
if (SECONDARY_DIR_LENGTH !== 0) {
    define('BASE_URL', BASE_DOMAIN . '/' . SECONDARY_DIR . '/');
    // $path = substr($path, SECONDARY_DIR_LENGTH);
} else {
    define('BASE_URL', BASE_DOMAIN . '/');
}

function alink($url) {
    echo $url . '.html';
}

function assets($res) {
    echo ASSETS_PATH . $res;
}

// 需要复制过去的文件
$fileOpt = new FileUtil();
$fileOpt->copyDir('../css',BUILD_PATH . 'css');
$fileOpt->copyDir('../js',BUILD_PATH . 'js');
$fileOpt->copyDir('../images',BUILD_PATH . 'images');
$fileOpt->copyDir('../image',BUILD_PATH . 'image');
$fileOpt->copyDir('../fonts',BUILD_PATH . 'fonts');
$fileArry = $fileOpt->getFile('view');

function createPage($page){
  $htmlpath = str_replace('php','html',basename($page));
  $htmlpath = BUILD_PATH .'page/'. $htmlpath;
  var_dump($htmlpath);
  ob_start();
  require PAGE_PATH ."/_layout/layout.php";
  require $page;
  echo "
</body>
</html>






<!--  ******  删除以下代码  ****** -->

<script>
$(function(){
  // 为a的href添加.html后缀
  $(document).on('click','a[href]',function(event) {
  	var url = $(this).attr('href');
    if(url !== '' && url.substr(0,1) !== '#' && url.substr(0,1) !== '.' && url.indexOf('javascript:') === -1){
    event.preventDefault();
    window.location.href = (this.href+'.html');
  	}
	});
});
</script>
";
  $out = ob_get_contents();
  ob_end_clean();
  $htmlfile = fopen($htmlpath,'w');
  fwrite($htmlfile,$out);
}

if(!file_exists(BUILD_PATH)){
  echo 'create build dir.';
  mkdir(BUILD_PATH,0755);
}

if(!file_exists(BUILD_PATH.'page')){
	echo "create page dir";
	mkdir(BUILD_PATH.'page',0755);
}
foreach($fileArry as $file){
  createPage($file);
}