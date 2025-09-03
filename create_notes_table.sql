-- Create email_notes table for tracking all note updates
CREATE TABLE IF NOT EXISTS email_notes (
    note_id INT PRIMARY KEY AUTO_INCREMENT,
    admin_id INT NOT NULL,
    email_id INT NOT NULL,
    note_text TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (admin_id) REFERENCES admins(admin_id),
    FOREIGN KEY (email_id) REFERENCES email(id) ON DELETE CASCADE,
    INDEX idx_email_id (email_id),
    INDEX idx_admin_id (admin_id),
    INDEX idx_created_at (created_at)
);

-- Note: The created_at column will use the server's timezone setting
-- Make sure to set timezone to 'America/New_York' in your PHP scripts

-- Optional: Add some sample data for testing
-- INSERT INTO email_notes (admin_id, email_id, note_text) VALUES (1, 1, 'Initial note for testing');
