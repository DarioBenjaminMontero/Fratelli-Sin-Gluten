<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FratelliSinGluten</title>
    <?php if (!empty($head)) include $head;?>
</head>
<body>
     <?php if (!empty($vistaHeader)) include $vistaHeader; ?>
     <main>
         <?php if (!empty($vista)) include $vista; ?>
</main>
<footer>
    <?php if (!empty($footer)) include $footer; ?>
</footer>
</body>
</html>