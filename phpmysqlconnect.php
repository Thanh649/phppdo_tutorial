<?php

require_once 'dbconfig.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    echo "Connected to $dbname at $host successfully.";
	echo "<br>";
	
	$sql = 'SELECT * FROM employees';
	$q = $pdo->prepare($sql);
	$q->execute();
	$q->setFetchMode(PDO::FETCH_ASSOC);
	
	// while ($r = $q->fetch()){
		// echo sprintf('%s <br/>', $r['FirstName']);
	// }
    //$pdo = null;
} catch (PDOException $pe) {
    die("Could not connect to the database $dbname :" . $pe->getMessage());
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>PHP MySQL Query Data Demo</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
    </head>
    <body>
        <div id="container">
            <h1>Employees</h1>
            <table class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $q->fetch()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['STT']) ?></td>
                            <td><?php echo htmlspecialchars($row['FirstName']); ?></td>
                            <td><?php echo htmlspecialchars($row['LastName']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
    </body>
</div>
</html>





