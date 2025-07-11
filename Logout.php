<?php
session_start();
session_destroy();
echo "<script>
    localStorage.clear();
    alert('Logged out successfully!');
    window.location.href = 'Login.php';
</script>";
exit;
?>
