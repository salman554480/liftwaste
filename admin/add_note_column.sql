-- Add note column to email table if it doesn't exist
ALTER TABLE email ADD COLUMN IF NOT EXISTS note TEXT;

-- If the above doesn't work (MySQL version < 8.0), use this instead:
-- ALTER TABLE email ADD COLUMN note TEXT;
