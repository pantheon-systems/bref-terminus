<?php

if(file_exists('./env.php')) {
    include './env.php';

    foreach ($variables as $key => $value) {
        putenv("$key=$value");
    }
}
