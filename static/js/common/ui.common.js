$(function () {
/*
* 设置页面标题
**/
document.title = $('.page-wrap').data('page-tit');
var navActive = $('.page-wrap').attr('data-navActive');
$('#Nav_'+navActive).addClass('active')

})