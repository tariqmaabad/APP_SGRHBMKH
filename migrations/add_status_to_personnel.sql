-- Add status column to personnel table
ALTER TABLE personnel ADD COLUMN status ENUM('TITULAIRE', 'CONTRACTUEL') DEFAULT 'TITULAIRE' AFTER date_prise_service;

-- Update existing records (setting all to TITULAIRE for now)
UPDATE personnel SET status = 'TITULAIRE' WHERE status IS NULL;
