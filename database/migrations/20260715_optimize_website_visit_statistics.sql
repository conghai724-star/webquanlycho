-- Visit statistics are stored as small aggregate tables instead of one row per visit.
CREATE TABLE IF NOT EXISTS `hicrm_website_visit_daily` (
  `visit_date` date NOT NULL,
  `visit_count` bigint(20) unsigned NOT NULL DEFAULT 0,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`visit_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `hicrm_website_visit_stats` (
  `stat_key` varchar(50) NOT NULL,
  `stat_value` bigint(20) unsigned NOT NULL DEFAULT 0,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`stat_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- This table contains only active sessions. Expired rows are cleaned by the application.
CREATE TABLE IF NOT EXISTS `hicrm_website_active_sessions` (
  `session_id` varchar(128) NOT NULL,
  `expires_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`session_id`),
  KEY `idx_expires_at` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- One-time, idempotent migration of the legacy session log. The old table is retained.
INSERT IGNORE INTO `hicrm_website_visit_daily` (`visit_date`, `visit_count`)
SELECT `visit_date`, COUNT(*)
FROM `hicrm_website_visits`
GROUP BY `visit_date`;

INSERT IGNORE INTO `hicrm_website_visit_stats` (`stat_key`, `stat_value`)
SELECT 'total_visits', COUNT(*)
FROM `hicrm_website_visits`;
