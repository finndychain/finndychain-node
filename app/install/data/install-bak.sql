-- phpMyAdmin SQL Dump
-- version 4.6.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2017-12-26 11:16:21
-- 服务器版本： 5.7.15
-- PHP Version: 7.0.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- --------------------------------------------------------

-- ----------------------------
-- Table structure for cloud_sys_conf
-- ----------------------------
DROP TABLE IF EXISTS `cloud_sys_conf`;

CREATE TABLE `cloud_sys_conf` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '配置ID',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '配置名称',
  `title` varchar(50) NOT NULL COMMENT '配置说明',
  `value` text NOT NULL COMMENT '配置值',
  `options` varchar(255) NOT NULL COMMENT '配置额外值',
  `function` varchar(60) NOT NULL COMMENT '关联函数',
  `group` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '配置分组',
  `sub_group` tinyint(3) DEFAULT '0' COMMENT '子分组，子分组需要自己定义',
  `type` varchar(16) NOT NULL DEFAULT '0' COMMENT '配置类型',
  `remark` varchar(500) NOT NULL COMMENT '配置说明',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `sort` smallint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统配置表';

INSERT INTO `cloud_sys_conf` (`id`, `name`, `title`, `value`, `options`, `function`, `group`, `sub_group`, `type`, `remark`, `create_time`, `update_time`, `sort`, `status`) VALUES
(1, 'toggle_web_site', '站点开关', '1', '0:关闭\r\n1:开启', '', 1, 0, 'select', '站点关闭后将提示网站已关闭，不能正常访问', 1378898976, '2018-02-28 06:41:45', 1, 1),
(2, 'web_site_title', '网站标题', '发源地-开源云采集引擎', '', '', 6, 0, 'text', '网站标题前台显示标题', 1378898976, '2018-03-02 05:43:53', 2, 1),
(4, 'web_site_logo', '网站LOGO', '250', '', '', 6, 0, 'picture', '网站LOGO', 1407003397, '2018-02-28 06:41:45', 4, 1),
(5, 'web_site_description', 'SEO描述', 'cloud是基于ThinkPHP5开发的一套轻量级云采集框架，追求高效，简单，灵活。', '', '', 6, 1, 'textarea', '网站搜索引擎描述', 1378898976, '2018-03-02 05:37:11', 6, 1),
(6, 'web_site_keyword', 'SEO关键字', '云采集开源框架,ThinkPHP', '', '', 6, 1, 'textarea', '网站搜索引擎关键字', 1378898976, '2018-02-28 06:41:46', 4, 1),
(7, 'web_site_copyright', '版权信息', '版权信息', '', '', 1, 0, 'text', '设置在网站底部显示的版权信息', 1406991855, '2018-02-28 06:41:45', 7, 1),
(8, 'web_site_icp', '网站备案号', '网站备案号', '', '', 6, 0, 'text', '设置在网站底部显示的备案号，如“苏ICP备1502009-2号\"', 1378900335, '2018-02-28 06:41:45', 8, 1),
(9, 'web_site_statistics', '站点统计', '站点统计', '', '', 1, 0, 'textarea', '支持百度、Google、cnzz等所有Javascript的统计代码', 1378900335, '2018-02-28 06:41:45', 9, 1),
(10, 'index_url', '首页地址', 'http://cloud.abc.com', '', '', 2, 0, 'text', '可以通过配置此项自定义系统首页的地址，比如：http://www.xxx.com', 1471579753, '2018-02-28 06:41:46', 0, 1),
(11, 'save_method', '存储方式', '0', '0:关闭\r\n1:开启', '', 1, 0, 'select', '0=云端存储，1=开启本地存储', 1378898976, '2018-02-28 06:41:45', 10, 1),
(12, 'admin_page_rows', '分页数量', '20', '', '', 2, 0, 'number', '分页时每页的记录数', 1434019462, '2018-02-28 06:41:45', 4, 1),
(13, 'admin_theme', '后台主题', 'default', 'default:默认主题\r\nblue:蓝色理想\r\ngreen:绿色生活', '', 2, 0, 'select', '后台界面主题', 1436678171, '2018-02-28 06:41:45', 5, 1),
(14, 'develop_mode', '开发模式', '1', '1:开启\r\n0:关闭', '', 3, 0, 'select', '开发模式下会显示菜单管理、配置管理、数据字典等开发者工具', 1432393583, '2018-02-28 06:41:45', 1, 1),
(15, 'app_trace', '是否显示页面Trace', '1', '1:开启\r\n0:关闭', '', 3, 0, 'select', '是否显示页面Trace信息', 1387165685, '2018-02-28 06:41:45', 2, 1),
(16, 'auth_key', '系统加密KEY', 'vzxI=vf[=xV)?a^XihbLKx?pYPw$;Mi^R*<mV;yJh$wy(~~E?<.JA&ANdIZ#QhPq', '', '', 3, 0, 'textarea', '轻易不要修改此项，否则容易造成用户无法登录；如要修改，务必备份原key', 1438647773, '2018-02-28 06:41:45', 3, 1),
(17, 'only_auth_rule', '权限仅验证规则表', '1', '1:开启\n0:关闭', '', 4, 0, 'radio', '开启此项，则后台验证授权只验证规则表存在的规则', 1473437355, '2018-02-28 06:41:45', 0, 1),
(18, 'static_domain', '静态文件独立域名', '', '', '', 3, 0, 'text', '静态文件独立域名一般用于在用户无感知的情况下平和的将网站图片自动存储到腾讯万象优图、又拍云等第三方服务。', 1438564784, '2018-02-28 06:41:45', 3, 1),
(19, 'config_group_list', '配置分组', '1:基本\r\n2:系统\r\n3:开发\r\n4:安全\r\n5:授权\r\n6:网站信息\r\n7:用户\r\n8:邮箱', '', '', 3, 0, 'array', '配置分组的键值对不要轻易改变', 1379228036, '2018-02-28 06:41:45', 5, 1),
(20, 'app_key', 'appid', '', '', '', 3, 0, 'text', '', 1378898976, '2018-02-28 06:58:23', 2, 1),
(21, 'app_secret', '系统加密KEY', '', '', '', 3, 0, 'text', '', 1378898976, '2018-02-28 06:58:23', 3, 1);

-- ----------------------------
-- Table structure for cloud_users
-- ----------------------------
DROP TABLE IF EXISTS `cloud_users`;
CREATE TABLE `cloud_users` (
  `uid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` char(32) NOT NULL DEFAULT '' COMMENT '登录密码',
  `salt` varchar(30) NOT NULL DEFAULT '' COMMENT '加密key',
  `nickname` varchar(60) NOT NULL DEFAULT '' COMMENT '用户昵称',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '登录邮箱',
  `mobile` varchar(20) DEFAULT '' COMMENT '手机号',
  `avatar` varchar(150) DEFAULT '' COMMENT '用户头像目录',
  `sex` smallint(1) unsigned DEFAULT '0' COMMENT '性别；0：保密，1：男；2：女',
  `birthday` date DEFAULT '1970-01-01' COMMENT '生日',
  `description` varchar(200) DEFAULT '' COMMENT '个人描述',
  `register_ip` varchar(16) DEFAULT '' COMMENT '注册IP',
  `last_login_ip` varchar(16) DEFAULT '' COMMENT '最后登录ip',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `active_auth_sign` varchar(60) DEFAULT '' COMMENT '激活码',
  `url` varchar(100) DEFAULT '' COMMENT '用户个性URL',
  `score` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户积分',
  `money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '金额',
  `freeze_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '冻结金额，和金币相同换算',
  `pay_pwd` char(32) DEFAULT '' COMMENT '支付密码',
  `reg_from` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '注册来源：1=PC端，2=WAP端，3=微信端，4=APP端，5=后台添加',
  `reg_method` varchar(30) NOT NULL DEFAULT '' COMMENT '注册方式。wechat,sina,等',
  `level` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '等级',
  `p_uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '推荐人会员ID',
  `user_type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '用户类型：1=普通用户;2=普通管理员;3=超级管理员',
  `allow_admin` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '允许后台：0=不允许，1=允许',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '2' COMMENT '用户状态： 0=禁用； 1=正常 ；2=待验证',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`uid`),
  KEY `idx_username` (`username`),
  KEY `idx_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户表';


-- ----------------------------
-- Table structure for cloud_finndy_data
-- ----------------------------
DROP TABLE IF EXISTS `cloud_finndy_data`;
CREATE TABLE `cloud_finndy_data` (
  `itemid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `robotid` int(11) DEFAULT NULL,
  `subject` mediumtext,
  `message` mediumtext,
  `extfield1` tinytext,
  `extfield2` tinytext,
  `extfield3` tinytext,
  `extfield4` tinytext,
  `extfield5` tinytext,
  `extfield6` tinytext,
  `extfield7` tinytext,
  `extfield8` tinytext,
  `extfield9` tinytext,
  `extfield10` tinytext,
  `extfield11` tinytext,
  `extfield12` tinytext,
  `extfield13` tinytext,
  `extfield14` tinytext,
  `extfield15` tinytext,
  `extfield16` tinytext,
  `extfield17` tinytext,
  `extfield18` tinytext,
  `extfield19` tinytext,
  `extfield20` tinytext,
  `extfield21` tinytext,
  `extfield22` tinytext,
  `extfield23` tinytext,
  `extfield24` tinytext,
  `extfield25` tinytext,
  `extfield26` tinytext,
  `extfield27` tinytext,
  `extfield28` tinytext,
  `extfield29` tinytext,
  `extfield30` tinytext,
  `extfield31` tinytext,
  `extfield32` tinytext,
  `extfield33` tinytext,
  `extfield34` tinytext,
  `extfield35` tinytext,
  `extfield36` tinytext,
  `extfield37` tinytext,
  `extfield38` tinytext,
  `extfield39` tinytext,
  `extfield40` tinytext,
  `extfield41` text,
  `extfield42` text,
  `extfield43` text,
  `extfield44` text,
  `extfield45` text,
  `extfield46` text,
  `extfield47` text,
  `extfield48` text,
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`itemid`),
  KEY `robotid` (`robotid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cloud_stat_robot`;
CREATE TABLE `cloud_stat_robot` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` int(10) NOT NULL COMMENT '用户id',
  `count` int(10) NOT NULL DEFAULT '0' COMMENT '创建次数',
  `dateline` int(10) NOT NULL COMMENT '日期',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='数据源统计表';

DROP TABLE IF EXISTS `cloud_user_robot`;
CREATE TABLE `cloud_user_robot` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` int(10) NOT NULL COMMENT '用户id',
  `robotid` int(10) NOT NULL DEFAULT '0' COMMENT '数据源id',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='数据源用户关联表';


DROP TABLE IF EXISTS `cloud_auth_group`;
CREATE TABLE `cloud_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` varchar(500) NOT NULL,
  `remark` varchar(100) NOT NULL COMMENT '描述',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cloud_auth_group_access`;
CREATE TABLE `cloud_auth_group_access` (
  `uid` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `cloud_auth_group_access` VALUES ('1', '1', '1521088299', '2018-03-27 19:20:57');
INSERT INTO `cloud_auth_group` VALUES ('7', '普通用户', '1', '115,116,159,140,141,142,157,153,154,155,158,139,138,127,125,126,137,135,136,100,101,151', '', '1520931383', '2018-03-27 18:28:46');
INSERT INTO `cloud_auth_group` VALUES ('1', '超级管理员', '1', '115,116,159,140,141,142,157,153,154,155,158,139,138,127,125,126,137,135,136,100,143,145,101,151,102,119,104,156,105,148,149,132,106,146,133', '拥有网站最高管理员权限！', '1520931383', '2018-03-27 18:28:46');


DROP TABLE IF EXISTS `cloud_auth_rule`;
CREATE TABLE `cloud_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(80) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `title` char(20) NOT NULL DEFAULT '',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '如果type为1， condition字段就可以定义规则表达式',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `condition` char(100) NOT NULL DEFAULT '',
  `pid` int(10) NOT NULL DEFAULT '0',
  `icon` char(20) NOT NULL COMMENT '导航菜单图标',
  `is_display` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否在导航菜单显示,1:显示 0:不显示',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `level` int(10) NOT NULL DEFAULT '0',
  `create_time` int(10) NOT NULL,
  `update_time` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `cloud_auth_rule` VALUES ('100', 'user/shownav', '用户管理', '1', '1', '', '0', 'user', '1', '3', '0', '1520851347', '1520851347');
INSERT INTO `cloud_auth_rule` VALUES ('101', 'user/profile', '我的面板', '1', '1', '', '100', '', '1', '0', '1', '1520851354', '1520851347');
INSERT INTO `cloud_auth_rule` VALUES ('102', 'user/index', '会员列表', '1', '1', '', '100', '', '1', '0', '1', '1520851743', '1520851347');
INSERT INTO `cloud_auth_rule` VALUES ('104', 'sysconf/index', '系统设置', '1', '1', '', '0', 'th', '1', '4', '0', '1520851347', '1520851347');
INSERT INTO `cloud_auth_rule` VALUES ('105', 'authrule/index', '权限设置', '1', '1', '', '104', '', '1', '0', '1', '1520851347', '1520851347');
INSERT INTO `cloud_auth_rule` VALUES ('106', 'authgroup/index', '用户组设置', '1', '1', '', '104', '', '1', '0', '1', '1520851347', '1520851347');
INSERT INTO `cloud_auth_rule` VALUES ('138', 'robot/export', '导出规则', '1', '1', '', '116', '', '0', '0', '1', '1521539020', '1520851347');
INSERT INTO `cloud_auth_rule` VALUES ('116', 'robot/shownav', '数据管理', '1', '1', '', '0', 'cog', '1', '1', '0', '1521078157', '1520851347');
INSERT INTO `cloud_auth_rule` VALUES ('115', 'index/index', '首页', '1', '1', '', '0', 'tachometer', '1', '0', '0', '1521078076', '1520851347');
INSERT INTO `cloud_auth_rule` VALUES ('119', 'user/add', '增加会员', '1', '1', '', '100', '', '0', '0', '1', '1521081526', '1520851347');
INSERT INTO `cloud_auth_rule` VALUES ('127', 'robot/index', '数据源列表', '1', '1', '', '116', '', '1', '0', '1', '1521093668', '1520851347');
INSERT INTO `cloud_auth_rule` VALUES ('125', 'robot/add', '新增数据源', '1', '1', '', '116', '', '1', '0', '1', '1521088281', '1520851347');
INSERT INTO `cloud_auth_rule` VALUES ('126', 'robot/edit', '编辑数据源', '1', '1', '', '116', '', '0', '0', '1', '1521088299', '1520851347');
INSERT INTO `cloud_auth_rule` VALUES ('137', 'robot/copy', '复制数据源', '1', '1', '', '116', '', '0', '0', '1', '1521538994', '1520851347');
INSERT INTO `cloud_auth_rule` VALUES ('135', 'robot/startrun', '开始采集', '1', '1', '', '116', '', '0', '0', '1', '1521530818', '1520851347');
INSERT INTO `cloud_auth_rule` VALUES ('136', 'robot/debugrobot', '测试采集', '1', '1', '', '116', '', '0', '0', '1', '1521538956', '1520851347');
INSERT INTO `cloud_auth_rule` VALUES ('132', 'authrule/add', '增加权限', '1', '1', '', '105', '', '0', '0', '2', '1521447405', '1520851347');
INSERT INTO `cloud_auth_rule` VALUES ('133', 'authgroup/add', '增加用户组', '1', '1', '', '106', '', '0', '0', '2', '1521447446', '1520851347');
INSERT INTO `cloud_auth_rule` VALUES ('139', 'robot/detail', '查看数据源', '1', '1', '', '116', '', '0', '0', '1', '1521539096', '1520851347');
INSERT INTO `cloud_auth_rule` VALUES ('140', 'robot/cleardata', '删除数据', '1', '1', '', '116', '', '0', '0', '1', '1521539185', '1520851347');
INSERT INTO `cloud_auth_rule` VALUES ('141', 'robot/export_csv', '导出数据csv格式', '1', '1', '', '116', '', '0', '0', '1', '1521539217', '1520851347');
INSERT INTO `cloud_auth_rule` VALUES ('142', 'robot/export_json', '导出数据JSON格式', '1', '1', '', '116', '', '0', '0', '1', '1521539245', '1520851347');
INSERT INTO `cloud_auth_rule` VALUES ('143', 'user/del', '删除用户', '1', '1', '', '100', '', '0', '0', '1', '1521540413', '1520851347');
INSERT INTO `cloud_auth_rule` VALUES ('157', 'robot/stoprun', '停止采集', '1', '1', '', '116', '', '0', '0', '1', '1522142758', '1520851347');
INSERT INTO `cloud_auth_rule` VALUES ('145', 'user/edit', '编辑用户', '1', '1', '', '100', '', '0', '0', '1', '1521540504', '1520851347');
INSERT INTO `cloud_auth_rule` VALUES ('146', 'authgroup/edit', '修改用户组', '1', '1', '', '106', '', '0', '0', '2', '1521540753', '1520851347');
INSERT INTO `cloud_auth_rule` VALUES ('148', 'authrule/edit', '编辑权限', '1', '1', '', '105', '', '0', '0', '2', '1521541002', '1520851347');
INSERT INTO `cloud_auth_rule` VALUES ('149', 'authrule/del', '删除权限', '1', '1', '', '105', '', '0', '0', '2', '1521541013', '1520851347');
INSERT INTO `cloud_auth_rule` VALUES ('151', 'user/resetpwd', '修改密码', '1', '1', '', '101', '', '0', '0', '2', '1522056235', '1520851347');
INSERT INTO `cloud_auth_rule` VALUES ('153', 'robot/progress', '采集进度', '1', '1', '', '116', '', '0', '0', '1', '1522058193', '1520851347');
INSERT INTO `cloud_auth_rule` VALUES ('154', 'robot/import', '导入规则', '1', '1', '', '116', '', '0', '0', '1', '1522058354', '1520851347');
INSERT INTO `cloud_auth_rule` VALUES ('155', 'robot/importcopy', '导入规则importcopy', '1', '1', '', '116', '', '0', '0', '1', '1522058372', '1520851347');
INSERT INTO `cloud_auth_rule` VALUES ('156', 'sysconf/sysset', '站点设置', '1', '1', '', '104', '', '1', '0', '1', '1522115434', '1520851347');
INSERT INTO `cloud_auth_rule` VALUES ('158', 'robot/getjsonp', '获取本地数据', '1', '1', '', '116', '', '0', '0', '1', '1522142895', '1520851347');
INSERT INTO `cloud_auth_rule` VALUES ('159', 'robot/loadingpostcat', '发布数据', '1', '1', '', '116', '', '0', '0', '1', '1522143157', '1520851347');