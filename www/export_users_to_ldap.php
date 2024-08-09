<?php
// Fetch users from MySQL database
$servername = getenv('MYSQL_HOST');
$username = getenv('MYSQL_USER');
$password = getenv('MYSQL_PASSWORD');
$dbname = getenv('MYSQL_DATABASE');

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT uid, first_name, last_name, email, department, company FROM users";
$result = $conn->query($sql);

$users = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
} else {
    echo "0 results";
}
$conn->close();

// Function to create LDIF entry
function createLdifEntry($user) {
    $companyDn = "ou={$user['company']},dc=example,dc=org";
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
    return $ldif;
}

// Function to create company and department LDIF entries
function createCompanyAndDepartmentEntries($companiesAndDepartments) {
    $ldif = "";
    foreach ($companiesAndDepartments as $company => $departments) {
        $companyDn = "ou={$company},dc=example,dc=org";
        $ldif .= "dn: {$companyDn}\n";
        $ldif .= "objectClass: organizationalUnit\n";
        $ldif .= "ou: {$company}\n";
        $ldif .= "\n";

        foreach ($departments as $department) {
            $departmentDn = "ou={$department},{$companyDn}";
            $ldif .= "dn: {$departmentDn}\n";
            $ldif .= "objectClass: organizationalUnit\n";
            $ldif .= "ou: {$department}\n";
            $ldif .= "\n";
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

// Save LDIF data to file
file_put_contents('/var/www/html/users.ldif', $ldifData);
?>
