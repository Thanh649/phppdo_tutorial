<!DOCTYPE html>
<html>
    <head>
        <title>PHP MySQL Stored Procedure Demo 1</title>
        <link rel="stylesheet" href="css/table.css" type="text/css" />
    </head>
    <body>
        <?php
        require_once 'dbconfig.php';
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            // execute the stored procedure
            $sql = 'CALL GetEmployees()';
            // call the stored procedure
            $q = $pdo->query($sql);
            $q->setFetchMode(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error occurred:" . $e->getMessage());
        }
        ?>
        <table>
            <tr>
                <th>Role</th>
                <th>FirstName</th>
            </tr>
            <?php while ($r = $q->fetch()): ?>
                <tr>
                    <td><?php echo $r['Role'] ?></td>
                    <td><?php echo $r['FirstName']?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </body>
</html>    