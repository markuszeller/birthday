<?php
function pluralize(string $singular, string $plural, int $count): string
{
    return $count != 1 ? $plural : $singular;
}
