<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <?php wp_head(); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
          integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri() . "/style.css"; ?>">
    <title>ElectroGame</title>
</head>
<body>
<header>

    <a href="<?php echo get_bloginfo('wpurl')?>">
        <h2><?php echo get_bloginfo('name') ?></h2></a>
    <em><?php echo get_bloginfo('description') ?></em>

    <!--Menu-->
    <?php wp_nav_menu([
        "theme_location" => "menu-sup",
        "container" => "nav",
        "container_class" => "navbar navbar-expand-lg navbar-light",
        "menu_class" => "navbar-nav mr-auto",
        "menu_id" => " ",
        "walker" => new Simple_menu()
    ]);?>
</header>