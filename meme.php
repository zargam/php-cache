<?php
header("Content-Type:application/json");

try {

    $db_name     = 'test_db';

    $db_user     = 'test_db_user';

    $db_password = 'EXAMPLE_PASSWORD';

    $db_host     = 'localhost';

    $memcache = new Memcache();

    $memcache->addServer("127.0.0.1", 11211);

    $sql = 'SELECT

            product_id,

            product_name,

            retail_price

            FROM products

           ';

    $key = md5($sql);

    $cached_data = $memcache->get($key);

    $response = [];

    if ($cached_data != null) {

        $response['Memcache Data'] = $cached_data;

    } else {

        $pdo = new PDO("mysql:host=" . $db_host  . ";dbname=" . $db_name, $db_user, $db_password);

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        $stmt = $pdo->prepare($sql);

        $stmt->execute();

        $products = [];

        while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {

            $products[] = $row;

        }
        $memcache->set($key, $products, false, 5);

        $response['MySQL Data'] =  $products;

    }



    echo json_encode($response, JSON_PRETTY_PRINT) . "\n";



} catch(PDOException $e) {

    $error = [];

    $error['message'] = $e->getMessage();

    echo json_encode($error, JSON_PRETTY_PRINT) . "\n";

}
?>