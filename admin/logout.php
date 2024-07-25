<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <script>
        function confirmLogout() {
            var logoutConfirmed = confirm("Apakah Anda yakin ingin logout?");
            if (logoutConfirmed) {
                // Redirect to a page that destroys the session
                window.location.href = '../login.php';
            } else {
                // If the user cancels logout, do nothing and remain on the same page
                window.history.back();
            }
        }
    </script>
</head>
<body onload="confirmLogout()">
</body>
</html>
