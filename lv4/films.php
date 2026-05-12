<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

include("includes/db.php");

$result = $conn->query("SELECT * FROM films");

echo "<table>";
while($row = $result->fetch_assoc()) {
    echo "<tr>
        <td>{$row['naslov']}</td>
        <td>{$row['godina']}</td>
        <td>{$row['zanr']}</td>
        <td>
            <form method='POST' action='add_to_cart.php'>
                <input type='hidden' name='film_id' value='{$row['id']}'>
                <button>Dodaj</button>
            </form>
        </td>
    </tr>";
}
echo "</table>";