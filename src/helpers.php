<?php

function print_arr($data)
{
    echo '<pre style="background-color: #1b1b1b; color: #ffffff; border-radius: 5px; padding: 25px;">',print_r($data,1),'</pre>';
}

// Removing the redundant HTML characters if any exist.
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}