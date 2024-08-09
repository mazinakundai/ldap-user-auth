<?php
session_start();
require_once 'classes/Database.php';
require_once 'classes/User.php';

// Ensure the user is authenticated
if (!isset($_SESSION['user'])) {
    header("Location: ldap_auth.php");
    exit();
}

// Create a database connection and fetch grouped users
$db = new Database();
$user = new User($db->getPdo());
$usersGrouped = $user->fetchUsersGrouped();
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ldap_auth.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User List</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="user-list">
        <h1>User List</h1>
        <?php if (!empty($usersGrouped)): ?>
            <?php foreach ($usersGrouped as $company => $departments): ?>
                <h2><?php echo htmlspecialchars($company); ?></h2>
                <?php foreach ($departments as $department => $users): ?>
                    <h3><?php echo htmlspecialchars($department); ?></h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['uid']); ?></td>
                                    <td><?php echo htmlspecialchars($user['first_name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endforeach; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No users found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
