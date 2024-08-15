ALTER TABLE `general_settings` DROP `code`;
ALTER TABLE `currencies` DROP `slug`;
ALTER TABLE `currencies` ADD `sign` VARCHAR(255) NULL DEFAULT NULL AFTER `name`;
ALTER TABLE `users` ADD `metamask_wallet_address` TEXT NULL DEFAULT NULL AFTER `remember_token`, ADD `metamask_nonce` TEXT NULL DEFAULT NULL AFTER `metamask_wallet_address`;
ALTER TABLE `general_settings` ADD `metamask_login` TINYINT(0) NOT NULL DEFAULT '1' AFTER `allow_decimal_after_number`;
ALTER TABLE `general_settings` ADD `transfer_charge` DECIMAL(5,2) NOT NULL DEFAULT '0' AFTER `metamask_login`;

INSERT INTO `notification_templates` (`act`, `name`, `subj`, `email_body`, `sms_body`, `shortcodes`, `email_status`, `sms_status`, `created_at`, `updated_at`) VALUES
('RECEIVED_MONEY', 'Received Money', 'Received Money Successfully', 'Received {{amount}} {{currency}} from&nbsp; {{from_username}} <br><div><font size=\"5\"><span style=\"font-weight: bolder;\"><br></span></font></div><div><br></div><div><br style=\"font-family: Montserrat, sans-serif;\"></div>', 'Received {{amount}} {{currency}} from  {{from_username}}', '{\"trx\":\"Transaction number for the transfer\",\"amount\":\"Transfer amount\",\"charge\":\"Transfer charge\",\"currency\" :\"transfer currency or wallet currency\",\"from_username\" :\"From Username\"}', 1, 1, '2021-11-03 12:00:00', '2023-09-24 04:14:37'),
('TRANSFER_MONEY', 'Transfer Money', 'Transfer Completed Successfully', 'Sent  {{amount}} {{currency}} to {{to_username}}<br><div><font size=\"5\"><span style=\"font-weight: bolder;\"><br></span></font></div><div><br></div><div><br style=\"font-family: Montserrat, sans-serif;\"></div>', 'Sent {{amount}} {{currency}} to {{to_username}}', '{\"trx\":\"Transaction number for the transfer\",\"amount\":\"Transfer amount\",\"charge\":\"Transfer charge\",\"currency\" :\"transfer currency or wallet currency\",\"to_username\" :\"To username\"}', 1, 1, '2021-11-03 12:00:00', '2023-09-24 04:15:50');

INSERT INTO `pages` (`id`, `name`, `slug`, `tempname`, `secs`, `is_default`, `created_at`, `updated_at`) VALUES
(23, 'About', 'about-us', 'templates.basic.', '[\"product\",\"crypto_currency\",\"how_to_invest\",\"choose_us\",\"subscribe\"]', 0, '2023-09-25 01:01:03', '2023-09-25 01:07:41');

ALTER TABLE `wallets` DROP `in_order`;

ALTER TABLE `frontends` ADD `tempname` VARCHAR(40) NULL DEFAULT NULL AFTER `data_values`;

UPDATE `frontends` SET `tempname`="basic";