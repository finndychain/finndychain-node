"use strict";
//https://github.com/joker-pper/WebUploaderSupport.git
/**
 * @param options key：support 自定义拓展属性（可以重写对应的属性,删除文件的逻辑可以重新进行实现.）, 其他配置参考webuploader配置,优先级理论大于support的配置
 */
function WebUploaderSupport(options) {
    var that = this;

    var fileStatus =  {
        inited: "inited",  //初始状态
        queued: "queued",  //已经进入队列, 等待上传
        progress: "progress",  //上传中
        complete: "complete",  //上传完成
        error: "error",  //上传出错，可重试
        interrupt: "interrupt",  //上传中断，可续传
        invalid: "invalid",  //文件不合格，不能重试上传。会自动从队列中移除
        cancelled: "cancelled"  //文件被移除
    };
    WebUploaderSupport.fileStatus = fileStatus;

    var $fns = {
        log: function (content) {
            if(support.log && console) {
                console.log(content);
            }
        },
        logInfo: function () {
            var support = that.support;
            if(!support) {
                this.log("WebUploader does not support the browser you are using.");
            } else {
                if(this.getUploader() == null) {
                    this.log("WebUploader has not inited, please use it after inited.");
                }
            }
        },
        getUploader: function () {
            var uploader = that.uploader;
            return uploader;
        },
        getFiles: function () {
            var result = null;
            var uploader = this.getUploader();
            if(uploader) {
                result = uploader.getFiles();
            }
            return result;
        },
        getFileSize: function (status) {
            var result = 0;
            var uploader = this.getUploader();
            if(uploader) {
                if(status != null) {
                    result = uploader.getFiles(status).length;
                } else {
                    result = uploader.getFiles().length;
                }

            }
            return result;
        },
        getInitedFileSize: function () { //获取inited状态的文件个数
            return this.getFileSize('inited');
        },
        retry: function (file) {
            var uploader = this.getUploader();
            if(uploader) {
                if(that.edit) {
                    if(file != null) {
                        uploader.retry(file);
                    } else {
                        uploader.retry();
                    }
                } else {
                    this.log("can't retry, because not in edit mode");
                }
            }
            this.logInfo();
        },
        upload: function () {
            var uploader = this.getUploader();
            if(uploader) {
                if(that.edit) {
                    uploader.upload();
                } else {
                    this.log("can't upload, because not in edit mode");
                }
            }
            this.logInfo();
        },
        removeFileWithItem: function (file) {
            var uploader = that.uploader;
            if(file) {
                support.removeFileItem(support.getItem(file));
                if(uploader) {
                    uploader.removeFile(file.id, true);
                }
            }

        }
    };
    that.$fns = $fns;

    options = options || {};


    var support = {
        $fns: {},  //公共函数
        $elements: {},  //区域jquery元素
        edit: true,
        uploader: ".uploader",  //上传区域容器选择器
        dndWrapper: ".wrapper",  //拖拽区域选择器
        chooseFileBtn: ".filePicker",  //选择文件的按钮选择器
        uploadFileBtn: ".uploadFile",  //上传文件的按钮选择器
        fileList: ".file-list",  //显示文件列表的区域选择器
        fileListHeight: 150,  //初始默认高度
        log: false,    //是否打印信息
        multiple: true,  //默认多选
        thumbnailWidth: 150,
        thumbnailHeight: 150,
        fileSize: -1,  //文件总个数, -1时无限制
        ratio: (function () {
            return window.devicePixelRatio || 1;  //优化retina, 在retina下这个值是2
        })(),
        getActualThumbnailWidth: function () {
            var that = this;
            var ratio = that.ratio;
            return that.thumbnailWidth * ratio;
        },
        getActualThumbnailHeight: function () {
            var that = this;
            var ratio = that.ratio;
            return that.thumbnailHeight * ratio;
        },
        showPreview: function (uploader, $item, $img, file) {   //显示文件中的预览效果
            var that = this;
            // 缩略图大小
            var thumbnailWidth = that.getActualThumbnailWidth(), thumbnailHeight = that.getActualThumbnailHeight();
            that.setItemStyle($item);  //设置item宽高

            uploader.makeThumb(file, function (error, src) {
                if (error) {
                    var $replace = $('<div class="preview"></div>').css({
                        height: that.thumbnailHeight,
                        width: that.thumbnailWidth
                    }).append($('<div class="preview-tips">不能预览</div>'));
                    $img.replaceWith($replace);
                    return;
                }
                $img.attr('src', src);
            }, thumbnailWidth, thumbnailHeight);
        },
        getItem: function (file) {  //获取$item
            return $("#" + file.id);
        },
        setItemStyle: function ($item) {  //设置缩略图所在容器的宽高,默认是均150px,用于加载文件预览时设置
            if($item) {
                var that = this;
                var thumbnailWidth = that.thumbnailWidth, thumbnailHeight = that.thumbnailHeight;
                $item.css({width: thumbnailWidth, height: thumbnailHeight});  //设置$item宽高
            }
        },
        loadUploadFileBtnStyle: function () {  //用于加载上传按钮的样式
            var $fns = this.$fns;
            var $uploadFileBtn = this.$elements.$uploadFileBtn;
            if($fns && $uploadFileBtn) {
                var initedSize = $fns.getInitedFileSize();
                if (initedSize === 0) {  //inited 文件个数
                    $uploadFileBtn.addClass("disabled"); //上传按钮禁用
                } else {
                    $uploadFileBtn.removeClass("disabled");  //移除上传按钮的禁用样式
                }
            }
        },
        retryFile: function ($item, file) {
            var $fns = this.$fns;
            $fns.retry(file);
        },
        fileQueued: function (uploader, file, $fileList, $uploadFileBtn, $chooseFileBtn, removeFileWithItem) {  //文件被添加进队列
            var that = this;
            var $item = $('<div id="' + file.id + '" class="file-item thumbnail">' +
                '<div class="file-info">' + file.name + '</div>' +
                '<img/>' +
                '<div class="file-delete">' + '<button type="button" class="btn btn-info">' + '删除</button></div>' +
                '<div class="file-retry">' + '<button type="button" class="btn btn-info">' + '重试</button></div>' +
                '<div class="state ready">等待上传...</div>' +
                '<div class="progress"><div class="progress-bar"></div></div>' +
                '</div>'
            );
            that.showPreview(uploader, $item, $item.find("img"), file);  //显示预览效果
            $fileList.append($item);  //显示在文件列表中
            that.loadUploadFileBtnStyle();  //加载上传按钮样式
            $item.on("click", '.btn', function () {
                var $this = $(this);
                if($this.parents(".file-retry")[0]) {
                    that.retryFile($item, file);
                } else if ($this.parents(".file-delete")[0]) {
                    that.deleteFile($item, file, that.deleteServerFileCallback, removeFileWithItem);
                }
            });
            that.loadChooseFileBtnStyle($chooseFileBtn, $uploadFileBtn);
        },
        fileDequeued: function (uploader, file, $fileList, $uploadFileBtn) {
            this.loadUploadFileBtnStyle();
        },
        uploadProgress: function (file, percentage) {  //文件上传过程中创建进度条
            var $item = this.getItem(file);
            $item.removeClass("retry");  //移除重试class
            var $percent = $item.find('.progress .progress-bar');
            $item.find('.file-delete, .preview-tips').addClass("uploading");  //隐藏删除按钮、提示文字
            $item.find('.progress').show();  //显示进度条
            $percent.css('width', percentage * 100 + '%');
        },
        uploadComplete: function (file) {  //完成上传时，无论成功或者失败
            var $item = this.getItem(file);
            $item.find('.progress').fadeOut();
            $item.find('.file-delete, .preview-tips').removeClass("uploading");  //显示删除按钮、提示文字

            var $uploadFileBtn = this.$elements.$uploadFileBtn;
            var $chooseFileBtn = this.$elements.$chooseFileBtn;


            this.loadChooseFileBtnStyle($chooseFileBtn, $uploadFileBtn);
            this.loadUploadFileBtnStyle();
        },
        uploadSuccess: function (file, data, uploadSuccessCallbck, $chooseFileBtn, $uploadFileBtn) {   // 文件上传完成后
            var $item = this.getItem(file),
                $state = $item.find('.state');
            $item.find('.progress').hide();
            if (data.status) {  //上传成功时
                if(uploadSuccessCallbck && typeof uploadSuccessCallbck === "function") {
                    uploadSuccessCallbck($item, data);  //用于标识为服务端文件
                }
                if (!$state.hasClass('success')) {
                    $state.attr("class", "state success");
                    $state.text('上传成功');
                }
                $item.removeClass("retry");
            } else {
                if (!$state.hasClass('error')) {
                    $state.attr("class", "state error");
                    $state.text('上传失败');
                }
                $item.addClass("retry");
            }
        },
        uploadSuccessCallbck: function ($item, data) {  //上传文件成功时的回调,用于标识为服务端文件
            if($item && data) {
                var attrs = data.attrs;
                for(var key in attrs) {
                    $item.attr(key, attrs[key]);
                }
            }

        },
        /***
         *
         * @param uploader
         * @param file
         * @param files
         * @param fileSize  --- 总个数
         * @param removeFileWithItem  --- 用于移除文件及其显示的内容
         * @returns {boolean} 为true时可以添加到webuploader中并进行显示
         */
        beforeFileQueued: function(uploader, file, files, fileSize, removeFileWithItem, $chooseFileBtn, $uploadFileBtn, beforeFileQueuedCallback) {
            if(fileSize < 1) {  //无限制个数
                return true;
            }
            var that = this;
            var currentFileSize = that.getCurrentFileSize();  //当前总个数
            var flag = false;
            var edit = that.edit;
            if(edit) {  //可编辑模式时
                if(currentFileSize < fileSize) {
                    flag = true;
                }
            }

            if(beforeFileQueuedCallback && typeof beforeFileQueuedCallback === "function") {  //执行beforeFileQueuedCallback回调函数
                beforeFileQueuedCallback(that, flag, edit, uploader, file, fileSize, currentFileSize);
            }

            return flag;
        },
        /**
         *
         * @param that  --- 当前对象,可以访问其他属性及方法
         * @param result  --- beforeFileQueued返回的结果值
         * @param result  --- 是否可编辑
         * @param uploader  --- 当前uploader实例
         * @param file  --- file对象
         * @param fileSize  --- 总文件个数
         * @param currentFileSize  --- 当前文件个数
         */
        beforeFileQueuedCallback: function (that, result, edit, uploader, file, fileSize, currentFileSize) {},  //回调函数
        uploadError: function (file) {  //文件上传失败后
            var $item = this.getItem(file),
                $state = $item.find('.state');
            if (!$state.hasClass('error')) {
                $state.attr("class", "state error");
                $state.text('上传失败');
            }
            $item.addClass("retry");
            this.loadUploadFileBtnStyle();
        },
        uploadFinished: function () {},  //文件上传完后触发
        serverFileAttrName: "data-server-file",  //服务端文件的属性名称
        getIsServerFile: function ($item) {  //判断文件是否是服务端文件
            var val = $item && $item.attr(this.serverFileAttrName);
            if(val && val === "true") {
                return true;
            }
            return false;
        },
        getServerFileSize: function () {  //获取服务端文件的个数
            var $fileList = this.$elements.$fileList;
            var size = 0;
            var serverFileAttrName = this.serverFileAttrName;
            if($fileList) {
                size = $fileList.find(".file-item["+serverFileAttrName+"='true']").size();
            }
            return size;
        },
        getItemSize: function () {  //获取当前item的文件个数
            var $fileList = this.$elements.$fileList;
            var size = 0;
            if($fileList) {
                size = $fileList.find(".file-item").size();
            }
            return size;
        },
        getCurrentFileSize: function () {  //获取当前uploader实例中文件的个数
            var fileStatus = WebUploaderSupport.fileStatus;
            var $fns = this.$fns;
            var initedSize = $fns.getFileSize(fileStatus.inited);  //初始状态个数
            var errorSize = $fns.getFileSize(fileStatus.error);  //上传失败个数
            var size = initedSize + errorSize + this.getServerFileSize();//最终加上服务端文件个数
            var itemSize = this.getItemSize();
            var result = itemSize > size ? itemSize : size;
            return result;
        },
        removeFileItem: function($item) {  //移除$item
            if($item && $item[0]) {
                $item.off().remove();
            }
        },
        deleteFile: function ($item, file, deleteServerFileCallback, removeFileWithItem) {  //删除文件的处理逻辑，包含服务端
            if(this.getIsServerFile($item)) {  //服务端时
                if(this.edit) {
                    this.deleteServerFile($item, deleteServerFileCallback);
                } else {
                    this.$fns.log("can't delete server file");
                }
            } else {
                if(removeFileWithItem && file) {
                    removeFileWithItem(file);
                }
                var $chooseFileBtn = this.$elements.$chooseFileBtn, $uploadFileBtn = this.$elements.$uploadFileBtn;
                this.loadChooseFileBtnStyle($chooseFileBtn, $uploadFileBtn);
            }
        },
        deleteServerFileAttrName: "data-delete-url",
        deleteServerFile: function ($item, deleteServerFileCallback) {  //删除服务器文件
            var that = this;
            var url = $item && $item.attr(that.deleteServerFileAttrName);
            if(url) {
                $.ajax({
                    dataType: "json",
                    type: "post",
                    url: url,
                    success: function (json) {
                        if(deleteServerFileCallback && typeof deleteServerFileCallback === "function") {
                            deleteServerFileCallback(that, $item, json);  //通过callback执行业务操作

                            var $chooseFileBtn = that.$elements.$chooseFileBtn, $uploadFileBtn = that.$elements.$uploadFileBtn;
                            that.loadChooseFileBtnStyle($chooseFileBtn, $uploadFileBtn);
                        }
                    }
                });
            }
        },  //当是服务端文件时执行,依赖于getIsServerFile的判断结果
        deleteServerFileCallback: function (that, $item, data) { //deleteServerFile 响应成功时的回调处理, that指当前对象
            if(data.status) {
                that.removeFileItem($item);
            } else {
                alert(data.content);
            }
        },
        serverFiles: [],  //加载服务端的数据,当前为 [{name:string, src: string, attrs: {}}]
        init: function (data, $fileList, $chooseFileBtn, $uploadFileBtn) { //初始化服务端数据,及加载样式

            var that = this;
            var edit = that.edit;
            var $files = null;

            var thumbnailHeight = that.thumbnailHeight;
            $fileList.css({"min-height": thumbnailHeight + 20});  //设置该区域最小高度为thumbnailHeight + 20px

            //加载服务端数据
            if(data && data.length > 0) {
                for(var i in data) {
                    var item = data[i];
                    var html = '<div class="file-item thumbnail">' +
                        '<div class="file-info">' + (item.name || "") + '</div>';


                    html += '<div class="file-delete">' + '<button type="button" class="btn btn-info">' + '删除</button></div>';

                    html += '<div class="progress"><div class="progress-bar"></div></div>' + '</div>';

                    var $preview;  //根据文件后缀进行展示预览结果

                    if(item.name != null && /(.jpg|.png|.gif|.bmp|.jpeg)$/.test(item.name.toLocaleLowerCase())) {
                        $preview = $('<img src="'+ item.src + '"/>');
                    } else {
                        var thumbnailWidth = that.thumbnailWidth, thumbnailHeight = that.thumbnailHeight;
                        $preview = $('<div class="preview"></div>').css({
                            height: thumbnailHeight,
                            width: thumbnailWidth
                        }).append($('<div class="preview-tips">不能预览</div>'));
                    }

                    var $item = $(html);
                    $item.append($preview);
                    if(!edit) {
                        $item.addClass("not-edit");
                    }

                    that.setItemStyle($item);  //以缩略图大小设置$item宽高

                    if($item && item) {
                        var attrs = item.attrs;
                        for(var key in attrs) {  //设置$item属性值
                            $item.attr(key, attrs[key]);
                        }
                    }
                    if(i === "0") {
                        $files = $item;
                    } else {
                        $files = $files.add($item);
                    }
                }
            }
            if($files) { //加载服务端数据
                $fileList.append($files);
                $files.on('click', '.file-delete .btn', function () {
                    var $item = $(this).parents(".file-item");
                    that.deleteFile($item, null, that.deleteServerFileCallback);
                });
            }

            that.loadChooseFileBtnStyle($chooseFileBtn, $uploadFileBtn);
        },
        editChange: function (edit) {  //用于根据edit改变时进行设置webuploader模式
            var that = this;
            that.edit = edit;
            var $chooseFileBtn = that.$elements.$chooseFileBtn, $uploadFileBtn = that.$elements.$uploadFileBtn;
            var $fileList = that.$elements.$fileList;
            if(edit) {
                $fileList.children().removeClass("not-edit");
            } else {
                $fileList.children().addClass("not-edit");
            }
            that.loadChooseFileBtnStyle($chooseFileBtn, $uploadFileBtn);
        },
        getChooseFileLabel: function ($chooseFileBtn) {  //获取当前上传文件按钮对应的label,该label用于触发选择文件
            var $label = null;
            if($chooseFileBtn) {
                if($chooseFileBtn.hasClass("webuploader-container")) {
                    $label = $chooseFileBtn.find(".webuploader-element-invisible").next("label");
                } else {
                    $label = $(".webuploader-container").not(this.chooseFileBtn).find(".webuploader-element-invisible").next("label");
                }
            }
            return $label;
        },
        loadChooseFileBtnStyle: function ($chooseFileBtn, $uploadFileBtn) {  //根据文件个数进行展示选择文件的按钮(用于上传完成时,删除文件时，添加到队列时， 初次加载服务端数据时)
            var that = this;
            var $fns = that.$fns;
            var fileSize = that.fileSize;
            var $actions = $chooseFileBtn.parents(".actions");
            var $actionsArea = $actions.parent(".actions-area");
            var $label = that.getChooseFileLabel($chooseFileBtn);

            if (that.edit) {  //可编辑时
                $actionsArea.css("height", "");
                $actions.show();
                var uploader = $fns.getUploader();
                if(uploader) {
                    uploader.refresh();  //解决label按钮点击无反应
                }
                if (fileSize > 0) {
                    var currentSize = that.getCurrentFileSize();
                    if (fileSize === currentSize) {
                        $label && $label.hide();
                        $chooseFileBtn.hide();
                        $uploadFileBtn.addClass("right");
                    } else {
                        $label && $label.show();
                        $chooseFileBtn.show();
                        $uploadFileBtn.removeClass("right");
                    }
                } else {
                    $label && $label.show();
                    $chooseFileBtn.show();
                    $uploadFileBtn.removeClass("right");
                }
            } else {  //不可编辑时

                $actions.hide();
                $actionsArea.css("height", 10);

                if ($label) {
                    $label.hide();
                }
            }
        }
    };

    support = $.extend(support, options.support);

    support.$fns = $fns;  //设置support方法

    that.supports = support;

    var multiple = support.multiple;

    delete options.support;  //删除额外的support属性

    var $uploader = $(support.uploader),
        $chooseFileBtn = $uploader.find(support.chooseFileBtn),  //选择文件的按钮选择器
        $fileList = $uploader.find(support.fileList),  //显示文件列表的区域
        $uploadFileBtn = $uploader.find(support.uploadFileBtn),  //上传文件的按钮
        $dndWrapper = $uploader.find(support.dndWrapper);  //支持拖拽到此区域

    var $elements = {
        $uploader: $uploader,
        $chooseFileBtn: $chooseFileBtn,
        $fileList: $fileList,
        $uploadFileBtn: $uploadFileBtn,
        $dndWrapper: $dndWrapper
    };

    support.$elements = $elements;

    var defaultOption = {
        accept: {
            title: 'Images',
            extensions: 'gif,jpg,jpeg,bmp,png',
            mimeTypes: 'image/!*'
        },
        pick: {
            id: $chooseFileBtn,
            multiple: multiple
        },
        disableGlobalDnd: true,
        dnd: $dndWrapper,  //支持拖拽到此区域
        resize: false,
        compress: false,  //不压缩图片,原图
        swf: 'Uploader.swf'  // swf文件路径
    };

    var currentOptions = $.extend(true, {}, defaultOption, options);  //当期webuploader的配置, options中的优先级最高

    if(document.all || window.ActiveXObject || "ActiveXObject" in window) {
        if(currentOptions.paste != null) {
            currentOptions.paste = null;
            $fns.log("ie is not support paste");
        }
    }

    that.edit = support.edit;
    that.support = WebUploader.Uploader.support(); //获取是否支持webuploader上传


    jQuery(function() {
        var $ = jQuery;
        $fileList.css({"min-height": support.fileListHeight + 20});

        var uploader;
        try {
            if(!that.support) {
                support.init(support.serverFiles, $fileList, $chooseFileBtn, $uploadFileBtn);
                $fns.log("WebUploader does not support the browser you are using.");
                return;
            } else {
                uploader = WebUploader.create(currentOptions);  //实例化webuploader
                support.init(support.serverFiles, $fileList, $chooseFileBtn, $uploadFileBtn);
            }

        }  catch (e) {
            if(console) {
                console.log(e);
            }
        }


        if(uploader) {

            that.uploader = uploader;

            if($uploadFileBtn && $uploadFileBtn[0]) {
                $uploadFileBtn.click(function () {
                    $fns.upload();
                });
            }

            //文件被添加进队列时
            uploader.on('fileQueued', function (file) {
                support.fileQueued && support.fileQueued(uploader, file, $fileList, $uploadFileBtn, $chooseFileBtn, $fns.removeFileWithItem);
            });

            //移除文件时
            uploader.on('fileDequeued', function (file) {
                support.fileDequeued && support.fileDequeued(uploader, file, $fileList, $uploadFileBtn);
            });

            uploader.on('uploadProgress', function (file, percentage) {
                support.uploadProgress && support.uploadProgress(file, percentage);
            });

            //完成上传时，无论成功或者失败
            uploader.on('uploadComplete', function (file) {
                support.uploadComplete && support.uploadComplete(file);
            });


            // 文件上传完成后，添加相应的样式(响应成功)
            uploader.on('uploadSuccess', function (file, data) {
                support.uploadSuccess && support.uploadSuccess(file, data, support.uploadSuccessCallbck, $chooseFileBtn, $uploadFileBtn);
            });

            // 文件上传失败，显示上传出错（上传失败出现错误状态码时）
            uploader.on('uploadError', function (file) {
                support.uploadError && support.uploadError(file);
            });
            // 当文件被加入队列之前触
            uploader.on('beforeFileQueued', function (file) {
                var files = uploader.getFiles();
                return support.beforeFileQueued && support.beforeFileQueued(uploader, file, files, support.fileSize, $fns.removeFileWithItem, $chooseFileBtn, $uploadFileBtn, support.beforeFileQueuedCallback);

            });

            //当前uploader实例文件上传完成后触发
            uploader.on("uploadFinished", function () {
                support.uploadFinished && support.uploadFinished();
            });

        }
    });


}

//上传该uploader实例的文件
WebUploaderSupport.prototype.upload = function () {
    this.$fns.upload();
}
//判断是否正在上传中
WebUploaderSupport.prototype.isInProgress = function () {
    var flag = false;
    var uploader = this.uploader;
    if(uploader) {
        flag = uploader.isInProgress();
    }
    this.$fns.logInfo();
    return flag;
}


WebUploaderSupport.prototype.retry = function () {
    this.$fns.retry();
}

WebUploaderSupport.prototype.getSupports = function () {
    var supports = this.supports;
    return supports;
}
//更换模式
WebUploaderSupport.prototype.editChange = function (edit) {
    if(typeof edit != "boolean") {
        throw new Error("the param type must be boolean");
    }
    var supports = this.supports;
    this.edit = edit;
    supports.editChange(edit);
}