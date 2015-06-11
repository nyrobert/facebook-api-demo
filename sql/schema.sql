CREATE TABLE `user` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `user_facebook` (
  `user_id` INT(10) UNSIGNED NOT NULL,
  `access_token` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `facebook_user_id` VARCHAR(45) COLLATE utf8_unicode_ci NOT NULL,
  `profile_picture` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `idx_facebook_user_id` (`facebook_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
