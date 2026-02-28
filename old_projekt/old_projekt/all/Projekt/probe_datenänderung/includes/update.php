<?php
// --------------------
// PROFIL DATEN ÄNDERN
// --------------------
if (isset($_POST['speichern'])) {

    $name  = trim($_POST['name']);
    $email_neu = trim($_POST['email']);

    if ($email_neu === '') {
        $email_neu = $_SESSION['user'];
    }

    if ($name !== '' && filter_var($email_neu, FILTER_VALIDATE_EMAIL)) {

        $stmt = $mysqli->prepare(
            "UPDATE USERS SET name = ?, email = ? WHERE email = ?"
        );
        $stmt->bind_param("sss", $name, $email_neu, $_SESSION['user']);

        if ($stmt->execute()) {
            $_SESSION['user'] = $email_neu;
            $msg = "Daten erfolgreich gespeichert!";
        } else {
            $err = "Fehler beim Speichern.";
        }
        $stmt->close();
    } else {
        $err = "Bitte gültigen Namen und E-Mail eingeben.";
    }
}

// --------------------
// PASSWORT ÄNDERN
// --------------------
if (isset($_POST['passwort'])) {

    $alt  = $_POST['alt']  ?? '';
    $neu1 = $_POST['neu1'] ?? '';
    $neu2 = $_POST['neu2'] ?? '';

    $stmt = $mysqli->prepare(
        "SELECT password FROM USERS WHERE email = ?"
    );
    $stmt->bind_param("s", $_SESSION['user']);
    $stmt->execute();
    $stmt->bind_result($db_hash);
    $stmt->fetch();
    $stmt->close();

    if (
        password_verify($alt, $db_hash) &&
        $neu1 === $neu2 &&
        strlen($neu1) >= 6
    ) {
        $neu_hash = password_hash($neu1, PASSWORD_DEFAULT);

        $stmt = $mysqli->prepare(
            "UPDATE USERS SET password = ? WHERE email = ?"
        );
        $stmt->bind_param("ss", $neu_hash, $_SESSION['user']);

        if ($stmt->execute()) {
            $msg = "Passwort erfolgreich geändert!";
        } else {
            $err = "Fehler beim Ändern.";
        }
        $stmt->close();
    } else {
        $err = "Passwortdaten ungültig.";
    }
}

