<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 *
 * @Author: Oshri R <oshri.rozmarin@gmail.com>
 *
 */

// If to use utf-8 or not in my sql query
$utf8 =  ($GLOBALS['disable_utf8_flag']) ? array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET sql_mode = \'\'') : array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\', sql_mode = \'\'');

// Sets default factory using the default database
$factories = array(
    'Zend\Db\Adapter\Adapter' => function ($serviceManager) {
        $adapterFactory = new Zend\Db\Adapter\AdapterServiceFactory();
        $adapter = $adapterFactory->createService($serviceManager);
        \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::setStaticAdapter($adapter);
        return $adapter;
    }
);

// This settings can be change in the global settings under security tab
if($GLOBALS['allow_multiple_databases']){

    // Open pdo connection
    $dbh = new PDO('mysql:dbname=' . $GLOBALS['dbase'] . ';host=' . $GLOBALS['host'], $GLOBALS['login'], $GLOBALS['pass']);
    $adapters = array();
    $res = $dbh->prepare('SELECT * FROM multiple_db');
    if($res->execute()){
        foreach ($res->fetchAll() as $row) {

            // Create new adapters using data from database
            $adapters[$row['namespace']] = array(
                'driver' => 'Pdo',
                'dsn' => 'mysql:dbname=' . $row['dbname'] . ';host=' . $row['host'] . '',
                'driver_options' => $utf8,
                'port' => $row['port'],
                'username' => $row['username'],
                'password' => my_decrypt($row['password']),
            );

            // Create new factories using data from custom database
            $factories[$row['namespace']] = function ($serviceManager) use ($row) {
                $adapterAbstractServiceFactory = new Zend\Db\Adapter\AdapterAbstractServiceFactory();
                $adapter = $adapterAbstractServiceFactory->createServiceWithName($serviceManager,'',$row['namespace']);
                return $adapter;
            };
        }
    }
    $dbh = null; // Close pdo connection

}


return array(
    'db' => array(
        'driver'         => 'Pdo',
        'dsn'            => 'mysql:dbname='.$GLOBALS['dbase'].';host='.$GLOBALS['host'],
        'username'       => $GLOBALS['login'],
        'password'       => $GLOBALS['pass'],
        'port'           => $GLOBALS['port'],
        'driver_options' => $utf8,
        'adapters' => $adapters

    ),
    'service_manager' => array(
        'factories' => $factories
    ),
);



/**
 * Encrypts the string
 * @param $value
 * @return bool|string
 */
function my_encrypt($data) {
    // Remove the base64 encoding from our key
    $encryption_key = base64_decode($GLOBALS['safe_key_database']);
    // Generate an initialization vector
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    // Encrypt the data using AES 256 encryption in CBC mode using our encryption key and initialization vector.
    $encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryption_key, 0, $iv);
    // The $iv is just as important as the key for decrypting, so save it with our encrypted data using a unique separator (::)
    return base64_encode($encrypted . '::' . $iv);
}

/**
 * Decrypts the string
 * @param $value
 * @return bool|string
 */

function my_decrypt($data) {
    // Remove the base64 encoding from our key
    $encryption_key = base64_decode($GLOBALS['safe_key_database']);
    // To decrypt, split the encrypted data from our IV - our unique separator used was "::"
    list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
    return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
}