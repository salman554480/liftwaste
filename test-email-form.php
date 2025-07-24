<!DOCTYPE html>
<html>
<head>
    <title>Test Email Submission</title>
</head>
<body>
    <h2>Send Test Email to PHP Script</h2>
    <form action="parse-email.php" method="POST" enctype="multipart/form-data">
        <label>From:</label><br>
        <input type="email" name="from" required><br><br>

        <label>To:</label><br>
        <input type="email" name="to" required><br><br>

        <label>Subject:</label><br>
        <input type="text" name="subject" required><br><br>

        <label>Plain Text Body:</label><br>
        <textarea name="text" rows="4" cols="50"></textarea><br><br>

        <label>HTML Body:</label><br>
        <textarea name="html" rows="4" cols="50"></textarea><br><br>

        <label>Headers (Optional):</label><br>
        <textarea name="headers" rows="3" cols="50"></textarea><br><br>

        <label>Attachment (Optional):</label><br>
        <input type="file" name="attachment"><br><br>

        <button type="submit">Submit Test Email</button>
    </form>
</body>
</html>
