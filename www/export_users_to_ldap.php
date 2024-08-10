<?php
// Fetch users from MySQL database
$servername = getenv('MYSQL_HOST');
$username = getenv('MYSQL_USER');
$password = getenv('MYSQL_PASSWORD');
$dbname = getenv('MYSQL_DATABASE');

echo "Connecting to MySQL with the following parameters:\n";
echo "Host: $servername\n";
echo "Username: $username\n";
echo "Password: $password\n";
echo "Database: $dbname\n";

try {
    $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connecting to the database...\n";
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$sql = "SELECT uid, first_name, last_name, email, department, company FROM users";
$stmt = $pdo->query($sql);

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pdo = null; // Close the connection

echo "Connection to the database closed WE GOT THE USERS...\n";
// Function to create LDIF entry
function createLdifEntry($user) {
    $companyDn = "ou={$user['company']},dc=mycompany,dc=com";
    $departmentDn = "ou={$user['department']},{$companyDn}";
    $dn = "uid={$user['uid']},ou=Users,{$departmentDn}";

    $ldif = "dn: {$dn}\n";
    $ldif .= "objectClass: inetOrgPerson\n";
    $ldif .= "uid: {$user['uid']}\n";
    $ldif .= "sn: {$user['last_name']}\n";
    $ldif .= "cn: {$user['first_name']} {$user['last_name']}\n";
    $ldif .= "mail: {$user['email']}\n";
    $ldif .= "ou: {$user['department']}\n";
    $ldif .= "o: {$user['company']}\n";
    $ldif .= "userPassword: password\n"; // Set a default password
    $ldif .= "\n";
    echo "LDIF USER ENTRY created...\n";
    return $ldif;
}

// Function to create company and department LDIF entries
function createCompanyAndDepartmentEntries($companiesAndDepartments) {
    $ldif = "";
    foreach ($companiesAndDepartments as $company => $departments) {
        $companyDn = "ou={$company},dc=mycompany,dc=com";
        $ldif .= "dn: {$companyDn}\n";
        $ldif .= "objectClass: organizationalUnit\n";
        $ldif .= "ou: {$company}\n";
        $ldif .= "\n";
        echo "LDIF COMPANY ENTRY created...\n";

        foreach ($departments as $department) {
            $departmentDn = "ou={$department},{$companyDn}";
            $ldif .= "dn: {$departmentDn}\n";
            $ldif .= "objectClass: organizationalUnit\n";
            $ldif .= "ou: {$department}\n";
            $ldif .= "\n";
            echo "LDIF DEPT ENTRY created...\n";
        }
    }
    return $ldif;
}

// Gather companies and departments
$companiesAndDepartments = [];
foreach ($users as $user) {
    if (!isset($companiesAndDepartments[$user['company']])) {
        $companiesAndDepartments[$user['company']] = [];
    }
    if (!in_array($user['department'], $companiesAndDepartments[$user['company']])) {
        $companiesAndDepartments[$user['company']][] = $user['department'];
    }
}

// Create LDIF data
$ldifData = "";
$ldifData .= createCompanyAndDepartmentEntries($companiesAndDepartments);

foreach ($users as $user) {
    $ldifData .= createLdifEntry($user);
}

echo "SAVING LDIF DATA TO FILE ...\n";
// Save LDIF data to file
file_put_contents('/var/www/html/users.ldif', $ldifData);
?>
