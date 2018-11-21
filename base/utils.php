<?php 

define('ROOTPATH', __DIR__);

echo ROOTPATH;
function createMenu(){

  return file_get_contents(ROOTPATH.'/base/layouts/menu.html');
}

function createNavbar(){
  return file_get_contents(ROOTPATH.'/base/layouts/navbar.html');
}
?>