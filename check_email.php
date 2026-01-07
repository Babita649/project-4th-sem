<?php
include 'db.php';

if (isset($_POST['email'])) {
    $email = trim($_POST['email']);

<<<<<<< HEAD
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
=======
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
>>>>>>> c8f70b60a7d0644d49d956c63298ba01b5cbde0c
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    echo ($stmt->num_rows > 0) ? "exists" : "available";

    $stmt->close();
<<<<<<< HEAD
}?>
=======
}
?>

>>>>>>> c8f70b60a7d0644d49d956c63298ba01b5cbde0c
