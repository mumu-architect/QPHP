/*
 Navicat Premium Data Transfer

 Source Server         : 127.0.0.1
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : localhost:3306
 Source Schema         : qphp

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 21/05/2022 04:23:13
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for mm_address
-- ----------------------------
DROP TABLE IF EXISTS `mm_address`;
CREATE TABLE `mm_address`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL DEFAULT 0,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `address_info` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `is_default` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of mm_address
-- ----------------------------
INSERT INTO `mm_address` VALUES (1, 1, 'qmumu', '中国陕西西安莲湖区', 1);

-- ----------------------------
-- Table structure for mm_user
-- ----------------------------
DROP TABLE IF EXISTS `mm_user`;
CREATE TABLE `mm_user`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `age` tinyint(4) NULL DEFAULT 0,
  `address` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `pwd` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 9 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of mm_user
-- ----------------------------
INSERT INTO `mm_user` VALUES (1, 'QPHP', 1, '西安', '123123');
INSERT INTO `mm_user` VALUES (2, 'mumu', 2, '上海', '12312312');
INSERT INTO `mm_user` VALUES (3, 'uer', 1, '北京', '12312');
INSERT INTO `mm_user` VALUES (4, 'vrvr', 34, '电商', '12312312');
INSERT INTO `mm_user` VALUES (8, 'mumu', 0, '', '123456');

-- ----------------------------
-- Table structure for mm_user_info
-- ----------------------------
DROP TABLE IF EXISTS `mm_user_info`;
CREATE TABLE `mm_user_info`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `birthday` int(10) NOT NULL DEFAULT 0,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `info` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '简介',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of mm_user_info
-- ----------------------------
INSERT INTO `mm_user_info` VALUES (1, 1, 599068800, '委屈委屈', '手动阀手动阀手动阀手动阀撒旦');

SET FOREIGN_KEY_CHECKS = 1;
