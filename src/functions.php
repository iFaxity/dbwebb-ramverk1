<?php

/**
 * Alias to htmlentities, escapes a string to prevent XSS injection
 * @return string escaped string.
 */
function esc($str) : string
{
    if (is_null($str)) {
        return "";
    }
    return htmlentities($str);
}
