-- Migration: Add remember_tokens table for "Remember Me" functionality
-- Run this SQL to add the remember_tokens table to your database

CREATE TABLE IF NOT EXISTS `remember_tokens` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `selector` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hashed_validator` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `selector_unique` (`selector`),
  KEY `user_id_idx` (`user_id`),
  KEY `expires_at_idx` (`expires_at`),
  CONSTRAINT `fk_remember_tokens_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Optional: Add index to clean up expired tokens periodically
-- You can run this query periodically to clean up expired tokens:
-- DELETE FROM remember_tokens WHERE expires_at < NOW();
