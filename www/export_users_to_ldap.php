<?php
// Fetch users from MySQL database
$servername = getenv('MYSQL_HOST');
$username = getenv('MYSQL_USER');
$password = getenv('MYSQL_PASSWORD');
$dbname = getenv('MYSQL_DATABASE');

try {
    $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connecting to the database...\n";
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$sql = "SELECT uid, uid_number, first_name, last_name, email, department, company, group_id FROM users";
$stmt = $pdo->query($sql);

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pdo = null; // Close the connection

echo "Connection to the database closed WE GOT THE USERS...\n";
/* user import template
    dn: cn=Kundai Mazina,ou=users,dc=mycompany,dc=com
    cn: Kundai Mazina
    gidnumber: 500
    givenname: Kundai
    homedirectory: /home/users/kmazina
    objectclass: inetOrgPerson
    objectclass: posixAccount
    objectclass: top
    sn: Mazina 
    uid: kmazina
    uidnumber: 1000
    userpassword: start
*/
// Function to create LDIF entry
function createLdifEntry($user) {
    $cn = "{$user['first_name']} {$user['last_name']}";
    $companyDn = "dc=mycompany,dc=com";
    $dn = "cn={$cn},ou=users,{$companyDn}";
    $ldif = "dn: {$dn}\n";
    $ldif .= "cn: {$cn}\n";
    $ldif .= "gidnumber: {$user['group_id']}\n";
    $ldif .= "givenname: {$user['first_name']}\n";
    $ldif .= "homedirectory: /home/users/{$user['uid']}\n";    
    $ldif .= "objectClass: inetOrgPerson\n";
    $ldif .= "objectclass: posixAccount\n";
    $ldif .= " objectclass: top\n";
    $ldif .= "sn: {$user['last_name']}\n";
    $ldif .= "uid: {$user['uid']}\n";
    $ldif .= "uidnumber: {$user['uid_number']}\n";
    $ldif .= "userPassword: password\n"; // Set a default password
    $ldif .= "\n";
    echo "LDIF USER ENTRY created...\n";
    return $ldif;
}

// Create LDIF data
$ldifData = "";

foreach ($users as $user) {
    $ldifData .= createLdifEntry($user);
}

echo "SAVING LDIF DATA TO FILE ...\n";
// Save LDIF data to file
if (file_put_contents('/var/www/html/users.ldif', $ldifData)){
 echo "FILE SAVED ...\n";
} else {
 echo "FAILED TO SAVE THE FILE !!\n";
}
?>
