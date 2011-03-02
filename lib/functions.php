<?php
/**
 * short notation for htmlspecialchars
 *
 * @param string $data data to escape
 * @return string the escaped data
 */
function esc($data)
{
    return htmlspecialchars($data);
}

/**
 * short notation for html_entity_decode
 *
 * @param string $data data to unescape
 * @return string the unescape data
 */
function raw($data)
{
    return html_entity_decode($data, ENT_QUOTES, 'UTF-8');
}


//add your global functions here