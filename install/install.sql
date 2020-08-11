SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
CREATE TABLE `ti0s_challenge` (
  `id` int(11) NOT NULL,
  `level` tinyint(4) NOT NULL,
  `create_time` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `score` int(11) NOT NULL DEFAULT '100',
  `title` varchar(100) NOT NULL,
  `content` varchar(100) NOT NULL,
  `url` varchar(100) NOT NULL,
  `flag` varchar(100) NOT NULL,
  `is_hide` tinyint(1) NOT NULL,
  `is_delete` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `ti0s_configs` (
  `id` int(11) NOT NULL,
  `name` varchar(10000) NOT NULL,
  `value` varchar(10000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `ti0s_configs` (`id`, `name`, `value`) VALUES
(1, 'title', 'Ti0sCTF-X OJ实训平台'),
(2, 'tz_page', '10'),
(3, 'ph_page', '15'),
(4, 'notice', '公告：2020/07/31 平台测试版上线\n如无特殊说明，答案格式：flag{xxx}'),
(5, 'links', '[国家互联网应急中心](http://www.cert.org.cn/)\n[中国互联网络信息中心](http://www.cnnic.net.cn/)\n[工业和信息化部](http://www.miitbeian.gov.cn/)'),
(6, 'head_flag', 'ti0s'),
(7, 'super_pass', 'ti0sctf-oj');
CREATE TABLE `ti0s_submit` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ques_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `sub_time` int(11) NOT NULL,
  `sub_ip` int(11) UNSIGNED NOT NULL,
  `flag` varchar(100) NOT NULL,
  `is_pass` tinyint(1) NOT NULL,
  `is_delete` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `ti0s_types` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `num` int(11) NOT NULL,
  `is_delete` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `ti0s_types` (`id`, `name`, `num`, `is_delete`) VALUES
(1, '基础知识 Basic', 1, 0),
(2, '网页攻防 Web', 2, 0),
(3, '逆向工程 Reverse', 3, 0),
(4, '二进制漏洞 Pwn', 4, 0),
(5, '密码学 Crypto', 5, 0),
(6, '安全杂项 Misc', 6, 0);
CREATE TABLE `ti0s_users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT 'unknown',
  `password` char(32) NOT NULL DEFAULT '',
  `contact` varchar(30) NOT NULL,
  `key` char(32) NOT NULL DEFAULT '00000000000000000000000000000000',
  `reg_time` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `reg_ip` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `logged_time` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `logged_ip` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `is_hide` tinyint(1) NOT NULL DEFAULT '0',
  `is_ban` tinyint(1) NOT NULL DEFAULT '0',
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `ti0s_users` (`id`, `name`, `password`, `contact`, `key`, `reg_time`, `reg_ip`, `logged_time`, `logged_ip`, `is_hide`, `is_ban`, `is_admin`, `is_delete`) VALUES
(1, 'admin', 'b1f383b1195f5ea5265282ade325405d', '', '17159f51275f0144ca26ec471929eacc', 0, 0, 0, 0, 0, 0, 1, 0);
ALTER TABLE `ti0s_challenge`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `ti0s_configs`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `ti0s_submit`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `ti0s_types`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `ti0s_users`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `ti0s_challenge`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
ALTER TABLE `ti0s_configs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
ALTER TABLE `ti0s_submit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
ALTER TABLE `ti0s_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
ALTER TABLE `ti0s_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;