<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $conn = getConnection();

    // Get the card ID to delete
    $cardId = $_POST['id'];

    // Prepare and execute the delete query
    $stmt = $conn->prepare("DELETE FROM cards WHERE id = ?");
    $stmt->bind_param('i', $cardId);

    if ($stmt->execute()) {
        // Redirect back to the show cards page with a success message
        header("Location: show_cards.php?message=Card deleted successfully.");
        exit;
    } else {
        // Redirect back with an error message
        header("Location: show_cards.php?error=Error deleting card: " . $stmt->error);
        exit;
    }
} else {
    // Redirect if accessed without proper POST request
    header("Location: show_cards.php");
    exit;
}
?>
