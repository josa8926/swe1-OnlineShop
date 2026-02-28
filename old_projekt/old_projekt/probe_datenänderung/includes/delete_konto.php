<?php
if (isset($_POST['loeschen'])) {

    if (trim($_POST['confirm']) === 'LÖSCHEN') {

        $stmt = $mysqli->prepare(
            "DELETE FROM USERS WHERE email = ?"
        );
        $stmt->bind_param("s", $_SESSION['user']);

        if ($stmt->execute()) {
            session_destroy();
            header("Location: ../index.php");
            exit();
        } else {
            $err = "Fehler beim Löschen.";
        }
        $stmt->close();

    } else {
        $err = "Bitte genau 'LÖSCHEN' eingeben.";
    }
}

