<?php

function chartMakerDB()
{
    if (!function_exists('wpFluent')) {
        include EVENTNINJA_DIR . 'includes/libs/wp-fluent/wp-fluent.php';
    }
    return wpFluent();
}
