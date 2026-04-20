<?php
$plain = "lokka";
$hashed = password_hash($plain, PASSWORD_BCRYPT);

echo $hashed;
