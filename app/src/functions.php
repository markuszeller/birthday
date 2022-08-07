<?php
function pluralize($singular, $plural, $count)
{
    return $count != 1 ? $plural : $singular;
}
