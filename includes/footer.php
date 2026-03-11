<?php
/**
 * Global Footer
 * 
 * Includes JavaScript files and a simple notification poller.
 */
?>
    <!-- Bootstrap JS bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS modules -->
    <script src="/medtrack/assets/js/theme.js"></script>
    <script src="/medtrack/assets/js/ajax.js"></script>
    <script src="/medtrack/assets/js/charts.js"></script>
    <script src="/medtrack/assets/js/export.js"></script>
    <script src="/medtrack/assets/js/script.js"></script>
    
    <!-- Notification poller (every 30 seconds) -->
    <?php if (isLoggedIn()): ?>
    <script>
    function fetchNotificationCount() {
        fetch('/medtrack/api/notifications.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const badge = document.getElementById('notif-badge');
                    if (badge) {
                        badge.textContent = data.count;
                        badge.style.display = data.count > 0 ? 'inline-block' : 'none';
                    }
                }
            })
            .catch(err => console.error('Notification error:', err));
    }
    // Poll every 30 seconds
    setInterval(fetchNotificationCount, 30000);
    // Initial fetch
    fetchNotificationCount();
    </script>
    <?php endif; ?>
</body>
</html>