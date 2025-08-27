<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification Test - LiftWaste</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h4>Notification System Test</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6>Current Status:</h6>
                            <div id="status-display" class="alert alert-info">
                                Loading...
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <h6>Test Controls:</h6>
                            <button class="btn btn-primary me-2" onclick="testNotification()">Test Notification Sound</button>
                            <button class="btn btn-success me-2" onclick="checkEmails()">Check Emails Now</button>
                            <button class="btn btn-warning" onclick="resetSession()">Reset Session</button>
                        </div>
                        
                        <div class="mb-3">
                            <h6>Debug Information:</h6>
                            <pre id="debug-info" class="bg-light p-2 rounded">No debug info yet...</pre>
                        </div>
                        
                        <div class="mb-3">
                            <h6>Notification Bell (for testing):</h6>
                            <div class="d-flex align-items-center">
                                <a class="nav-link position-relative" href="#" id="notification-bell">
                                    <i class="fas fa-bell"></i>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none;">0</span>
                                </a>
                                <div class="notification-dropdown shadow" id="notification-dropdown" style="display: none;">
                                    <div class="notification-header fw-bold px-3 py-2 border-bottom">Notifications</div>
                                    <div class="notification-list" id="notification-list">
                                        <div class="notification-item px-3 py-2 text-center text-muted">
                                            <div>Loading notifications...</div>
                                        </div>
                                    </div>
                                    <div class="notification-footer text-center py-2">
                                        <a href="parts/index/pending_email.php" class="small">View all pending emails</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    
    <script>
        // Test functions
        function testNotification() {
            const audio = new Audio('assets/notification.mp3');
            audio.play().then(() => {
                updateStatus('Notification sound played successfully!', 'success');
            }).catch(error => {
                updateStatus('Error playing notification sound: ' + error.message, 'danger');
            });
        }
        
        function checkEmails() {
            updateStatus('Checking emails...', 'info');
            checkNewEmails();
        }
        
        function resetSession() {
            fetch('query/reset_session.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateStatus('Session reset successfully!', 'success');
                        setTimeout(() => {
                            checkNewEmails();
                        }, 1000);
                    } else {
                        updateStatus('Error resetting session: ' + data.message, 'danger');
                    }
                })
                .catch(error => {
                    updateStatus('Error: ' + error.message, 'danger');
                });
        }
        
        function updateStatus(message, type) {
            const statusDisplay = document.getElementById('status-display');
            statusDisplay.className = `alert alert-${type}`;
            statusDisplay.textContent = message;
        }
        
        // Override console.log to show in debug info
        const originalLog = console.log;
        console.log = function(...args) {
            originalLog.apply(console, args);
            const debugInfo = document.getElementById('debug-info');
            const timestamp = new Date().toLocaleTimeString();
            const message = args.map(arg => typeof arg === 'object' ? JSON.stringify(arg, null, 2) : arg).join(' ');
            debugInfo.textContent += `\n[${timestamp}] ${message}`;
            debugInfo.scrollTop = debugInfo.scrollHeight;
        };
    </script>
</body>
</html>
