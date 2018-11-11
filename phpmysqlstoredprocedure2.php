<?php

require_once 'dbconfig.php';

/**
 * Get customer level
 * @param int $customerNumber
 * @return string
 */
function getCustomerLevel(int $customerNumber) {
	$host = 'localhost';
    $dbname = 'db_phpbasic';
    $username = 'root';
    $password = '';
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

        // calling stored procedure command
        $sql = 'CALL GetLevelTest(:id,@level)';

        // prepare for execution of the stored procedure
        $stmt = $pdo->prepare($sql);

        // pass value to the command
        $stmt->bindParam(':id', $customerNumber, PDO::PARAM_INT);

        // execute the stored procedure
        $stmt->execute();

        $stmt->closeCursor();

        // execute the second query to get customer's level
        $row = $pdo->query("SELECT @level AS level")->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return $row !== false ? $row['level'] : null;
        }
    } catch (PDOException $e) {
        die("Error occurred:" . $e->getMessage());
    }
    return null;
}

$customerNo = 4;
echo sprintf('Customer #%d is %s', $customerNo, getCustomerLevel($customerNo));
?>