<?php
// Run migration to add remember_tokens table
require_once __DIR__ . '/../../app/Models/Database.php';

$conn = dbConnect();

$sql = "CREATE TABLE IF NOT EXISTS `remember_tokens` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if (mysqli_query($conn, $sql)) {
    echo "SUCCESS: remember_tokens table created or already exists.\n";
} else {
    echo "ERROR: " . mysqli_error($conn) . "\n";
}

mysqli_close($conn);
