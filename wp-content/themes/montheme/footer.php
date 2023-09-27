<footer>
    <?php wp_nav_menu([
        "theme_location" => "menu-footer",
        "container" => "nav",
        "container_class" => "navbar navbar-expand-lg navbar-light",
        "menu_class" => "navbar-nav mr-auto",
        "menu_id" => " ",
        "walker" => new Simple_menu()
    ]);?>
</footer>
<?php wp_footer(); ?>
</body>
</html>