// 接口部分
//基本的ajax访问后端接口类

// 管理后台相关////////////////////////////////////////////////////////////////////////////////////////////////

// 管理员相关///////////////

//发送验证码
function sendMassage(url, param, callBack) {
    ajaxRequest(url, param, "GET", callBack);
}
//设置管理员状态
function setAdminStatus(url, param, callBack) {
    ajaxRequest(url + "admin/admin/setStatus/" + param.id, param, "GET", callBack);
}
//设置设备状态
function setToolStatus(url, param, callBack) {
    ajaxRequest(url + "admin/tool/setStatus/" + param.id, param, "GET", callBack);
}
//设备选择商家
function chooseShop(url, param, callBack) {
    ajaxRequest(url + "admin/tool/chooseShopSave", param, "GET", callBack);
}
//设置商家状态
function setShopStatus(url, param, callBack) {
    ajaxRequest(url + "admin/shop/setStatus/" + param.id, param, "GET", callBack);
}
//商家选择设备
function chooseTool(url, param, callBack) {
    ajaxRequest(url + "admin/shop/chooseToolSave", param, "GET", callBack);
}
//文章删除
function article_delete(url, param, callBack) {
    ajaxRequest(url + "admin/article/del", param, "GET", callBack);
}
//文章分类删除所选文章
function articleascription_delete(url, param, callBack) {
    ajaxRequest(url + "admin/articleType/delArticle", param, "GET", callBack);
}
//文章分类删除
function articleType_delete(url, param, callBack) {
    ajaxRequest(url + "admin/articleType/del", param, "GET", callBack);
}
//文章分类选择文章
function chooseArticleSave(url, param, callBack) {
    ajaxRequest(url + "admin/articleType/chooseArticleSave", param, "GET", callBack);
}
//系统参数删除
function parameter_delete(url, param, callBack) {
    ajaxRequest(url + "admin/systemParameter/del", param, "GET", callBack);
}
//设置系统参数状态
function setParameterStatus(url, param, callBack) {
    ajaxRequest(url + "admin/systemParameter/setStatus/" + param.id, param, "GET", callBack);
}
//设置设备处理状态
function setToolDisposeStatus(url, param, callBack) {
    ajaxRequest(url + "admin/toolDispose/setStatus/" + param.id, param, "GET", callBack);
}
//设备移除
function removeTool(url, param, callBack) {
    ajaxRequest(url + "admin/shop/removeTool", param, "GET", callBack);
}


// //设置问卷状态
// function setWjStatus(url, param, callBack) {
//     ajaxRequest(url + "admin/xxjh/wj/setWj" ,param, "GET", callBack);
// }

// //根据一级分类获取二级分类信息
// function getLevel2ListByLevel1Id(url, param, callBack) {
//     ajaxRequest(url + "admin/level2/getListByLevel1Id", param, "GET", callBack);
// }

















//前端相关方法/////////////////////////////////////////////////////////
function h5_user_getById(url, param, callBack) {
    ajaxRequest(url + "user/h5/getById", param, "GET", callBack);
}
//前端相关方法/////////////////////////////////////////////////////////
function h5_doctor_getById(url, param, callBack) {
    ajaxRequest(url + "doctor/h5/getById", param, "GET", callBack);
}