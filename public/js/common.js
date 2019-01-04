// 接口部分
//基本的ajax访问后端接口类
function ajaxRequest(url, param, method, callBack) {
    console.log("url:" + url + " method:" + method + " param:" + JSON.stringify(param));
    $.ajax({
        type: method,  //提交方式
        url: url,//路径
        data: param,//数据，这里使用的是Json格式进行传输
        contentType: "application/json", //必须有
        dataType: "json",
        success: function (ret) {//返回数据根据结果进行相应的处理
            consoledebug.log("ret:" + JSON.stringify(ret));
            callBack(ret)
        },
        error: function (err) {
            console.log(JSON.stringify(err));
            console.log("responseText:" + err.responseText);
            callBack(err)
        }
    });
}

//是否输出打印信息的开关，为true 时输出打印信息
var DEBUG = true;

var consoledebug = (DEBUG) ? console : new nodebug();

function nodebug() {
}

nodebug.prototype.log = function (str) {
}
nodebug.prototype.warn = function (str) {
}

/*
 * 点击返回
 *
 * By TerryQi
 *
 * 2018-06-18
 */
function clickBack() {
    window.history.go(-1);
}

/*
 * 跳转到首页
 *
 * By TerryQi
 *
 * 2018-06-19
 */
function naToIndex() {
    toast_loading("返回首页...")
    window.location.href = "{{URL::asset('/doctor/h5/index')}}";
}

//新建toast变量
var toast = new auiToast({})

//加载中
function toast_loading(msg) {
    if (judgeIsNullStr(msg)) {
        msg = "加载中";
    }
    toast.loading({
        title: msg,
        duration: 2000
    }, function (ret) {
        console.log(ret);
        setTimeout(function () {
            toast.hide();
        }, 3000)
    });
}

//成功
function toast_success(msg) {
    if (judgeIsNullStr(msg)) {
        msg = "提交成功";
    }
    toast.success({
        title: msg,
        duration: 2000
    });
}

//失败
function toast_fail(msg) {
    if (judgeIsNullStr(msg)) {
        msg = "提交成功";
    }
    toast.fail({
        title: msg,
        duration: 2000
    });
}

//隐藏
function toast_hide() {
    toast.hide();
}

//提示对话框
var dialog = new auiDialog();

function dialog_show(param, callback) {
    var dialog_param = {
        title: "提示信息",
        msg: "确认执行操作？",
        buttons: ['取消', '确定'],
        input: false
    }
    if (!judgeIsNullStr(param.title)) {
        dialog_param.title = param.title;
    }
    if (!judgeIsNullStr(param.title)) {
        dialog_param.title = param.title;
    }
    if (!judgeIsNullStr(param.msg)) {
        dialog_param.msg = param.msg;
    }
    if (!judgeIsNullStr(param.buttons)) {
        dialog_param.buttons = param.buttons;
    }
    if (!judgeIsNullStr(param.input)) {
        dialog_param.input = param.input;
    }
    dialog.alert(dialog_param, function (ret) {
        if (typeof callback === "function") {
            callback(ret)
        }
    })
}
function dialog_alert(param, callback) {
    var dialog_param = {
        title: "提示信息",
        msg: "确认执行操作？",
        buttons: [ '取消','确定'],
        input: false
    }
    if (!judgeIsNullStr(param.title)) {
        dialog_param.title = param.title;
    }
    if (!judgeIsNullStr(param.title)) {
        dialog_param.title = param.title;
    }
    if (!judgeIsNullStr(param.msg)) {
        dialog_param.msg = param.msg;
    }
    if (!judgeIsNullStr(param.buttons)) {
        dialog_param.buttons = param.buttons;
    }
    if (!judgeIsNullStr(param.input)) {
        dialog_param.input = param.input;
    }
    dialog.alert(dialog_param, function (ret) {
        if (typeof callback === "function") {
            callback(ret)
        }
    })
}

/////////////////////////////////////////////////

/*
 * 校验手机号js
 *
 * By TerryQi
 */

function isPoneAvailable(phone_num) {
    var myreg = /^[1][3,4,5,7,8][0-9]{9}$/;
    if (!myreg.test(phone_num)) {
        return false;
    } else {
        return true;
    }
}

// 判断参数是否为空
function judgeIsNullStr(val) {
    if (val == null || val == "" || val == undefined || val == "未设置" || val == NaN) {
        return true
    }
    return false
}

// 判断参数是否为空
function judgeIsAnyNullStr() {
    if (arguments.length > 0) {
        for (var i = 0; i < arguments.length; i++) {
            if (!isArray(arguments[i])) {
                if (arguments[i] == null || arguments[i] == "" || arguments[i] == undefined || arguments[i] == "未设置" || arguments[i] == "undefined") {
                    return true
                }
            }
        }
    }
    return false
}

// 判断数组时候为空, 服务于 judgeIsAnyNullStr 方法
function isArray(object) {
    return Object.prototype.toString.call(object) == '[object Array]';
}


// 七牛云图片裁剪
function qiniuUrlTool(img_url, type) {
    //如果不是七牛的头像，则直接返回图片
    //consoledebug.log("img_url:" + img_url + " indexOf('isart.me'):" + img_url.indexOf('isart.me'));
    if (img_url.indexOf('7xku37.com') < 0 && img_url.indexOf('isart.me') < 0) {
        return img_url;
    }
    //七牛链接
    var qn_img_url;
    const size_w_500_h_200 = '?imageView2/2/w/500/h/200/interlace/1/q/75|imageslim'
    const size_w_200_h_200 = '?imageView2/2/w/200/h/200/interlace/1/q/75|imageslim'
    const size_w_500_h_300 = '?imageView2/2/w/500/h/300/interlace/1/q/75|imageslim'
    const size_w_500_h_250 = '?imageView2/2/w/500/h/250/interlace/1/q/75|imageslim'

    const size_w_500 = '?imageView1/1/w/500/interlace/1/q/75'

    //除去参数
    if (img_url.indexOf("?") >= 0) {
        img_url = img_url.split('?')[0]
    }
    //封装七牛链接
    switch (type) {
        case "ad":  //广告图片
            qn_img_url = img_url + size_w_500_h_300
            break
        case "folder_list":  //作品列表图片样式
            qn_img_url = img_url + size_w_500_h_200
            break
        case  'head_icon':      //头像信息
            qn_img_url = img_url + size_w_200_h_200
            break
        case  'work_detail':      //作品详情的图片信息
            qn_img_url = img_url + size_w_500
            break
        default:
            qn_img_url = img_url
            break
    }
    return qn_img_url
}


// 文字转html，主要是进行换行转换
function Text2Html(str) {
    if (str == null) {
        return "";
    } else if (str.length == 0) {
        return "";
    }
    str = str.replace(/\r\n/g, "<br>")
    str = str.replace(/\n/g, "<br>");
    return str;
}

//null变为空str
function nullToEmptyStr(str) {
    if (judgeIsNullStr(str)) {
        str = "";
    }
    return str;
}


/*
 * 用于对象克隆
 *
 * obj 对象，返回克隆对象
 *
 */
function clone(obj) {
    // Handle the 3 simple types, and null or undefined
    if (null == obj || "object" != typeof obj) return obj;

    // Handle Date
    if (obj instanceof Date) {
        var copy = new Date();
        copy.setTime(obj.getTime());
        return copy;
    }

    // Handle Array
    if (obj instanceof Array) {
        var copy = [];
        for (var i = 0, len = obj.length; i < len; ++i) {
            copy[i] = clone(obj[i]);
        }
        return copy;
    }

    // Handle Object
    if (obj instanceof Object) {
        var copy = {};
        for (var attr in obj) {
            if (obj.hasOwnProperty(attr)) copy[attr] = clone(obj[attr]);
        }
        return copy;
    }

    throw new Error("Unable to copy obj! Its type isn't supported.");
}


/*
 * 获取url中get的参数
 *
 * By TerryQi
 *
 * 2017-12-23
 *
 */
function getQueryString(name) {
    var reg = new RegExp('(^|&)' + name + '=([^&]*)(&|$)', 'i');
    var r = window.location.search.substr(1).match(reg);
    if (r != null) {
        return unescape(r[2]);
    }
    return null;
}


//获取时间基线类型的字符串
function getBtimeTypeStr(btime_type) {
    switch (btime_type) {
        case "0":
            return "手术后";
        case "1":
            return "首次弯腿后";
        case "2":
            return "指定日期";
    }
    return "";
}

//获取康复计划状态字符串
function getJHStatus(status) {
    switch (status) {
        case "0":
            return "计划执行";
        case "1":
            return "执行中";
        case "2":
            return "已执行";
    }
}

//获取时间基线单位
function getTimeUnitStr(unit) {
    switch (unit) {
        case "0":
            return "天";
        case "1":
            return "周";
        case "2":
            return "月";
    }
}

//获取当前日期
function getNowFormatDate() {
    var date = new Date();
    var seperator1 = "-";
    var year = date.getFullYear();
    var month = date.getMonth() + 1;
    var strDate = date.getDate();
    if (month >= 1 && month <= 9) {
        month = "0" + month;
    }
    if (strDate >= 0 && strDate <= 9) {
        strDate = "0" + strDate;
    }
    var currentdate = year + seperator1 + month + seperator1 + strDate;
    return currentdate;
}

function getNowFormatTime() {
    var date = new Date();
    var seperator1 = ":";
    var hour = date.getHours();
    if (hour < 10)
        hour = "0" + hour;
    var minute = date.getMinutes();
    if (minute < 10)
        minute = "0" + minute;

    var currentdate = hour + seperator1 + minute;
    return currentdate;
}

function getBeforeDate(date, n) {
    var n = n;
    var d = new Date(date);
    var year = d.getFullYear();
    var mon = d.getMonth() + 1;
    var day = d.getDate();
    if (day <= n) {
        if (mon > 1) {
            mon = mon - 1;
        }
        else {
            year = year - 1;
            mon = 12;
        }
    }
    d.setDate(d.getDate() - n);
    year = d.getFullYear();
    mon = d.getMonth() + 1;
    day = d.getDate();
    s = year + "-" + (mon < 10 ? ('0' + mon) : mon) + "-" + (day < 10 ? ('0' + day) : day);
    return s;
}

function getNextDate(date_str) {
    var date = new Date(date_str);
    var nextDate = new Date(date.getTime() + 24 * 60 * 60 * 1000); //后一天
    var nextDateStr = getFormatDate(nextDate)
    console.log("next:", nextDate, nextDateStr);
    return nextDateStr;
}

function getNextDaysDate(date_str, n) {
    var date = new Date(date_str);
    for (var i = 0; i < n; i++)
        date = new Date(date.getTime() + 24 * 60 * 60 * 1000); //后一天

    return getFormatDate(date);
}

function getFormatDate(date) {
    var y = date.getUTCFullYear(),
        m = date.getUTCMonth() + 1,
        d = date.getUTCDate(),
        h = date.getUTCHours(),
        i = date.getUTCMinutes(),
        s = date.getUTCSeconds(),
        l = date.getUTCMilliseconds();

    function z(i) {
        return (i <= 9 ? '0' + i : i);
    }

    return "" + y + '-' + z(m) + '-' + z(d);
}

//提示错误信息
// 要求错误div必须交error_msg
function error(text) {
    $("#error_msg").html("*" + text);
}