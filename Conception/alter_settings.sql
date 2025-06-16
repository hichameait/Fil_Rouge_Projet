-- Add dentist profile fields to settings table (safe add)
ALTER TABLE settings
	ADD COLUMN IF NOT EXISTS presentation TEXT DEFAULT NULL,
	ADD COLUMN IF NOT EXISTS certifications JSON DEFAULT NULL,
	ADD COLUMN IF NOT EXISTS experience JSON DEFAULT NULL,
	ADD COLUMN IF NOT EXISTS languages_spoken JSON DEFAULT NULL;

-- Add automation_settings if not exists (for user-specific notification preferences)
ALTER TABLE settings
	ADD COLUMN IF NOT EXISTS automation_settings JSON DEFAULT NULL COMMENT 'Only contains user-specific automation preferences';

-- Ensure SMTP and SMS settings columns exist in settings
ALTER TABLE settings
	ADD COLUMN IF NOT EXISTS smtp_settings TEXT DEFAULT NULL,
	ADD COLUMN IF NOT EXISTS sms_provider_settings JSON DEFAULT NULL;

-- Remove global_settings table if exists (cleanup)
DROP TABLE IF EXISTS global_settings;
