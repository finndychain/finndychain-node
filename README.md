# 发源地云采集终端
![输入图片说明](https://gitee.com/uploads/images/2018/0123/120318_87910cb7_1601883.jpeg "cloud_frame.jpg")

# 私有云API 
#### 请求链接 (示例链接只有必填参数，其他参数请自行添加) 

> *** 
> www.finndy.com/api/robot/api.php?appkey=APPKEY&sign=SIGN&time=1516247129&op=valuesubmit    
>*** 

#### 参数设置
|  参数  | 必填  | 类型 | 说明 |    
|:----: |:----:| :-----:|:----:|
| appkey   | 是 | string | 系统分配 |
| sign   | 是   |  string| 参数签名|
| op | 是  | string |  操作名称 |  
| time |   是    | int |  时间戳 |  
| robotid |   否   | int |  数据源ID，需要用到时带上 |
| page |   否   | int |  分页查询参数 |
| other... |   否   | mix | 根据需求自己补充 |    
   
##  数据规则接口
- `valuesubmit`  添加,编辑规则接口(需要私有云同步robotid和数据源名称)
- *删除接口* (预留)       
- `getcategory` 获取分类
- `getrobotrule` 获取规则  **必填参数: (int)robotid**
- `copyrobotrule` 复制规则 **必填参数: (int)robotid**
- `importcopyrobotrule` 导入规则 **必填参数: (int)robotid**
- `exportrule` 导出规则 **必填参数: (int)robotid**
- `startrun` 运行(需要私有云同步更新状态) **必填参数: (int)robotid**
- `stoprun` 停止(需要私有云同步更新状态) **必填参数: (int)robotid**
- `getrobotslist` 获取数据规则列表  **必填参数: (int)uid**
- *调试接口* （分布，整体）
- `progress` 进度接口 **必填参数: (Array)robotids**
- `getrobotextfield` 获取字段别名 **必填参数: (int)robotid**
- `getuserinfo` 获取用户信息,包含用户等级与等级对应的权限 **必填参数: (int)uid**
- `getversiontolv` 获取等级对应的版本号 参数为版本字符串 如：verf/verp/veru/vere 返回`LV1/LV2/LV3/LV4` **必填参数: (string)version**

#### 其他说明
> 获取规则，复制规则，导出规则，返回的数据中都带有发布规则。以数组形式输出，如`$thevalue['thepostvalue']`中的内容为发布规则
    
    
## 采集数据
- 主动推送接口(推送全部字段，需要重新设计推送部分)
- 接收数据接口

## 其他
- 配置链接验证接口(请求私有云，返回如：`ajaxreturn(0,'ok');`)
- `checkappkey` 私有云账号验证接口
- `checkappstatus` 私有云账号状态查询接口
- `changeappstatus` 私有云账号状态接口 带上appkey请求即可。状态会自动变化）
- `changerobotstatus` 私有云规则状态接口 （带上appkey请求即可。状态会自动变化）

