/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : finndy_cloud

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-01-30 17:22:39
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for cloud_finndy_data
-- ----------------------------
DROP TABLE IF EXISTS `cloud_finndy_data`;
CREATE TABLE `cloud_finndy_data` (
  `itemid` int(10) unsigned NOT NULL,
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
  `dateline` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`itemid`),
  KEY `robotid` (`robotid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cloud_finndy_data
-- ----------------------------
INSERT INTO `cloud_finndy_data` VALUES ('1', '1', '1', '11111111', '1', '2', '3', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0');
INSERT INTO `cloud_finndy_data` VALUES ('2', '3212', '2', '2', '2', '2', '2', '2', '2', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0');
INSERT INTO `cloud_finndy_data` VALUES ('3', '1664', '1', '1', '1', '1', '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1517294311');
INSERT INTO `cloud_finndy_data` VALUES ('4', '1664', '1', '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1517294311');
INSERT INTO `cloud_finndy_data` VALUES ('5', '1664', '5', '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0');
INSERT INTO `cloud_finndy_data` VALUES ('6', '1664', '4', '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1517294311');
INSERT INTO `cloud_finndy_data` VALUES ('7', '1664', '6', '1', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '1517294311');

-- ----------------------------
-- Table structure for cloud_sys_conf
-- ----------------------------
DROP TABLE IF EXISTS `cloud_sys_conf`;
CREATE TABLE `cloud_sys_conf` (
  `id` int(10) unsigned NOT NULL COMMENT '配置ID',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '配置名称',
  `title` varchar(50) NOT NULL COMMENT '配置说明',
  `value` text NOT NULL COMMENT '配置值',
  `options` varchar(255) NOT NULL COMMENT '配置额外值',
  `function` varchar(60) NOT NULL COMMENT '关联函数',
  `group` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置分组',
  `sub_group` tinyint(3) DEFAULT '0' COMMENT '子分组，子分组需要自己定义',
  `type` varchar(16) NOT NULL DEFAULT '0' COMMENT '配置类型',
  `remark` varchar(500) NOT NULL COMMENT '配置说明',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cloud_sys_conf
-- ----------------------------
INSERT INTO `cloud_sys_conf` VALUES ('1', 'toggle_web_site', '站点开关', '1', '0:关闭\r\n1:开启', '', '1', '0', 'select', '站点关闭后将提示网站已关闭，不能正常访问', '1378898976', '1406992386', '1', '1');
INSERT INTO `cloud_sys_conf` VALUES ('2', 'web_site_title', '网站标题', 'EacooPHP', '', '', '6', '0', 'text', '网站标题前台显示标题', '1378898976', '1507036190', '2', '1');
INSERT INTO `cloud_sys_conf` VALUES ('4', 'web_site_logo', '网站LOGO', '250', '', '', '6', '0', 'picture', '网站LOGO', '1407003397', '1507036190', '4', '1');
INSERT INTO `cloud_sys_conf` VALUES ('5', 'web_site_description', 'SEO描述', 'EacooPHP框架基于统一核心的通用互联网+信息化服务解决方案，追求简单、高效、卓越。可轻松实现支持多终端的WEB产品快速搭建、部署、上线。系统功能采用模块化、组件化、插件化等开放化低耦合设计，应用商城拥有丰富的功能模块、插件、主题，便于用户灵活扩展和二次开发。', '', '', '6', '1', 'textarea', '网站搜索引擎描述', '1378898976', '1506257875', '6', '1');
INSERT INTO `cloud_sys_conf` VALUES ('6', 'web_site_keyword', 'SEO关键字', '开源框架 EacooPHP ThinkPHP', '', '', '6', '1', 'textarea', '网站搜索引擎关键字', '1378898976', '1506257874', '4', '1');
INSERT INTO `cloud_sys_conf` VALUES ('7', 'web_site_copyright', '版权信息', 'Copyright © ******有限公司 All rights reserved.', '', '', '1', '0', 'text', '设置在网站底部显示的版权信息', '1406991855', '1468493911', '7', '1');
INSERT INTO `cloud_sys_conf` VALUES ('8', 'web_site_icp', '网站备案号', '豫ICP备14003306号', '', '', '6', '0', 'text', '设置在网站底部显示的备案号，如“苏ICP备1502009-2号\"', '1378900335', '1507036190', '8', '1');
INSERT INTO `cloud_sys_conf` VALUES ('9', 'web_site_statistics', '站点统计', '', '', '', '1', '0', 'textarea', '支持百度、Google、cnzz等所有Javascript的统计代码', '1378900335', '1415983236', '9', '1');
INSERT INTO `cloud_sys_conf` VALUES ('10', 'index_url', '首页地址', 'http://www.eacoo123.com', '', '', '2', '0', 'text', '可以通过配置此项自定义系统首页的地址，比如：http://www.xxx.com', '1471579753', '1506099586', '0', '1');
INSERT INTO `cloud_sys_conf` VALUES ('13', 'admin_tags', '后台多标签', '0', '0:关闭\r\n1:开启', '', '2', '0', 'radio', '', '1453445526', '1506099586', '3', '1');
INSERT INTO `cloud_sys_conf` VALUES ('14', 'admin_page_rows', '分页数量', '20', '', '', '2', '0', 'number', '分页时每页的记录数', '1434019462', '1506099586', '4', '1');
INSERT INTO `cloud_sys_conf` VALUES ('15', 'admin_theme', '后台主题', 'default', 'default:默认主题\r\nblue:蓝色理想\r\ngreen:绿色生活', '', '2', '0', 'select', '后台界面主题', '1436678171', '1506099586', '5', '1');
INSERT INTO `cloud_sys_conf` VALUES ('16', 'develop_mode', '开发模式', '1', '1:开启\r\n0:关闭', '', '3', '0', 'select', '开发模式下会显示菜单管理、配置管理、数据字典等开发者工具', '1432393583', '1507724972', '1', '1');
INSERT INTO `cloud_sys_conf` VALUES ('17', 'app_trace', '是否显示页面Trace', '1', '1:开启\r\n0:关闭', '', '3', '0', 'select', '是否显示页面Trace信息', '1387165685', '1507724972', '2', '1');
INSERT INTO `cloud_sys_conf` VALUES ('18', 'auth_key', '系统加密KEY', 'vzxI=vf[=xV)?a^XihbLKx?pYPw$;Mi^R*<mV;yJh$wy(~~E?<.JA&ANdIZ#QhPq', '', '', '3', '0', 'textarea', '轻易不要修改此项，否则容易造成用户无法登录；如要修改，务必备份原key', '1438647773', '1507724972', '3', '1');
INSERT INTO `cloud_sys_conf` VALUES ('19', 'only_auth_rule', '权限仅验证规则表', '1', '1:开启\n0:关闭', '', '4', '0', 'radio', '开启此项，则后台验证授权只验证规则表存在的规则', '1473437355', '1473437355', '0', '1');
INSERT INTO `cloud_sys_conf` VALUES ('20', 'static_domain', '静态文件独立域名', '', '', '', '3', '0', 'text', '静态文件独立域名一般用于在用户无感知的情况下平和的将网站图片自动存储到腾讯万象优图、又拍云等第三方服务。', '1438564784', '1438564784', '3', '1');
INSERT INTO `cloud_sys_conf` VALUES ('21', 'config_group_list', '配置分组', '1:基本\r\n2:系统\r\n3:开发\r\n4:安全\r\n5:授权\r\n6:网站信息\r\n7:用户\r\n8:邮箱', '', '', '3', '0', 'array', '配置分组的键值对不要轻易改变', '1379228036', '1510758405', '5', '1');
INSERT INTO `cloud_sys_conf` VALUES ('22', 'app_key', 'appid', '41', '1:开启\r\n0:关闭', '', '3', '0', 'text', '', '2018', '2018', '2', '1');
INSERT INTO `cloud_sys_conf` VALUES ('23', 'app_secret', '系统加密KEY', '04bb466b178561fa49fccfcb18561916', '', '', '0', '0', '0', '', '0', '0', '0', '0');

-- ----------------------------
-- Table structure for cloud_users
-- ----------------------------
DROP TABLE IF EXISTS `cloud_users`;
CREATE TABLE `cloud_users` (
  `uid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` char(32) NOT NULL DEFAULT '' COMMENT '登录密码',
  `salt` varchar(30) NOT NULL COMMENT '加密key',
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of cloud_users
-- ----------------------------
INSERT INTO `cloud_users` VALUES ('1', 'admin', 'c56d0e9a7ccec67b4ea131655038d604', 'Xdef', '创始人', 'admin@admin.com', '', '/static/assets/img/default-avatar.svg', '0', '1970-01-01', '', '', '127.0.0.1', '1516342843', '9c9737cb6fc276bfca19885ef5403a8f82c335bf', '', '0', '0.00', '0.00', '', '0', '', '0', '0', '3', '1', '1509096620', '1', '1509096620', '2018-01-30 16:52:42');
INSERT INTO `cloud_users` VALUES ('2', 'admin01', '7b070c5a0617449a02ef44db6b03cf18', 'Xdef', '管理员', 'test@admin.com', '', '/static/assets/img/default-avatar.svg', '0', '1970-01-01', '', '', '127.0.0.1', '1515143641', '551b352f742b2826b17db66d262a5c97e83d0710', '', '0', '0.00', '0.00', '', '0', '', '0', '0', '2', '1', '1509096620', '1', '1509096620', '2018-01-20 03:09:30');
INSERT INTO `cloud_users` VALUES ('3', 'test2', '7b070c5a0617449a02ef44db6b03cf18', 'Xdef', '用户2', 'test2@admin.com', '', '/static/assets/img/default-avatar.svg', '0', '1970-01-01', '', '', '127.0.0.1', '1515143641', '551b352f742b2826b17db66d262a5c97e83d0710', '', '0', '0.00', '0.00', '', '0', '', '0', '0', '1', '0', '1509096620', '1', '1509096620', '2018-01-10 08:23:39');
INSERT INTO `cloud_users` VALUES ('4', 'test01', '7b070c5a0617449a02ef44db6b03cf18', 'Xdef', '用户01', 'test2@admin.com', '', '/static/assets/img/default-avatar.svg', '0', '1970-01-01', '', '', '127.0.0.1', '1515636781', '54617ace61981269a86463602145896a5c301442', '', '0', '0.00', '0.00', '', '0', '', '0', '0', '1', '0', '1509096620', '1', '1509096620', '2018-01-11 02:13:01');
INSERT INTO `cloud_users` VALUES ('5', 'zmzzmz3', 'c56d0e9a7ccec67b4ea131655038d604', '', '', '', '', '', '0', '1970-01-01', '', '', '', '0', '', '', '0', '0.00', '0.00', '', '0', '', '0', '0', '1', '0', '0', '2', '0', '2018-01-30 16:48:34');
INSERT INTO `cloud_users` VALUES ('6', 'zmzzmz345', 'c56d0e9a7ccec67b4ea131655038d604', '', '', '', '', '', '0', '1970-01-01', '', '', '', '0', '', '', '0', '0.00', '0.00', '', '0', '', '0', '0', '1', '0', '0', '2', '1517302214', '2018-01-30 16:51:28');
