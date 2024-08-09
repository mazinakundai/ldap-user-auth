<?php
class LDAPAuth {
    private $ldap_host;
    private $ldap_dn;

    public function __construct() {
        $this->ldap_host = getenv('LDAP_HOST');
        $this->ldap_dn = getenv('LDAP_DN');
    }

    public function authenticate($username, $password) {
        $ldap = ldap_connect($this->ldap_host);
        ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

        if ($bind = @ldap_bind($ldap, "$username@$this->ldap_dn", $password)) {
            $filter = "(sAMAccountName=$username)";
            $result = ldap_search($ldap, $this->ldap_dn, $filter);
            $entries = ldap_get_entries($ldap, $result);
            
            if ($entries['count'] > 0) {
                return true;
            }
        }
        return false;
    }
}
?>
