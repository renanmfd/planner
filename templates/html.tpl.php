<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?php echo $title ?></title>
    <?php foreach ($styles as $style): ?>
        <?php echo $style; ?>
    <?php endforeach; ?>
</head>
<body<?php if($body_classes) echo " class=\"$body_classes\""; ?>>
    <?php echo $page; ?>
    <?php echo $modal; ?>
    <?php foreach ($scripts as $script): ?>
        <?php echo $script; ?>
    <?php endforeach; ?>
</body>
</html>
