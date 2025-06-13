-- Add dentist profile fields to settings table (safe add)
ALTER TABLE settings
	ADD COLUMN IF NOT EXISTS presentation TEXT DEFAULT NULL,
	ADD COLUMN IF NOT EXISTS certifications JSON DEFAULT NULL,
	ADD COLUMN IF NOT EXISTS experience JSON DEFAULT NULL,
	ADD COLUMN IF NOT EXISTS languages_spoken JSON DEFAULT NULL;

-- Add automation_settings if not exists (for user-specific notification preferences)
ALTER TABLE settings
	ADD COLUMN IF NOT EXISTS automation_settings JSON DEFAULT NULL COMMENT 'Only contains user-specific automation preferences';

-- Remove SMTP and SMS settings from individual settings if they exist
ALTER TABLE settings
	DROP COLUMN IF EXISTS smtp_settings,
	DROP COLUMN IF EXISTS sms_provider_settings;

-- Create global_settings table for admin-only SMTP/SMS config
CREATE TABLE IF NOT EXISTS global_settings (
	id INT PRIMARY KEY AUTO_INCREMENT,
	smtp_settings JSON DEFAULT NULL,
	sms_provider_settings JSON DEFAULT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
