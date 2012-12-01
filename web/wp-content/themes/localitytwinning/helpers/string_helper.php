<?php

/**
 * Word Limiter
 *
 * Limits a string to X number of words.
 *
 * @param  string
 * @param  integer
 * @param  string $end_char The end character. Usually an ellipsis
 * @return string
 */
function word_limiter($str, $limit = 100, $end_char = '&#8230;')
{
    if (trim($str) == '') {
        return $str;
    }

    preg_match('/^\s*+(?:\S++\s*+){1,' . (int)$limit . '}/', $str, $matches);

    if (strlen($str) == strlen($matches[0])) {
        $end_char = '';
    }

    return rtrim($matches[0]) . $end_char;
}

/**
 * Character Limiter
 *
 * Limits the string based on the character count.  Preserves complete words
 * so the character count may not be exactly as specified.
 *
 * @param  string
 * @param  integer
 * @param  string $end_char The end character. Usually an ellipsis
 * @return string
 */
function character_limiter($str, $n = 500, $end_char = '&#8230;')
{
    $str = apply_filters('the_title', $str);
    if (strlen($str) < $n) {
        return $str;
    }

    $str = preg_replace("/\s+/", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $str));

    if (strlen($str) <= $n) {
        return $str;
    }

    $out = "";
    foreach (explode(' ', trim($str)) as $val) {
        $out .= $val . ' ';

        if (strlen($out) >= $n) {
            $out = trim($out);
            return (strlen($out) == strlen($str)) ? $out : $out . $end_char;
        }
    }
}

/**
 * Create URL Title
 *
 * Takes a "title" string as input and creates a
 * human-friendly URL string with either a dash
 * or an underscore as the word separator.
 *
 * @param  string $str the string
 * @param  string $separator the separator: dash, or underscore
 * @param  bool $lowercase whether it should be lowercase or not
 * @return string
 */
function url_title($str, $separator = 'dash', $lowercase = true)
{
    if ($separator == 'dash') {
        $search  = '_';
        $replace = '-';
    } else {
        $search  = '-';
        $replace = '_';
    }

    $trans = array(
        '&\#\d+?;'                => '',
        '&\S+?;'                  => '',
        '\s+'                     => $replace,
        '[^a-z0-9\-\._]'          => '',
        $replace . '+'            => $replace,
        $replace . '$'            => $replace,
        '^' . $replace            => $replace,
        '\.+$'                    => ''
    );

    $str = strip_tags($str);

    foreach ($trans as $key => $val) {
        $str = preg_replace("#" . $key . "#i", $val, $str);
    }

    if ($lowercase === TRUE) {
        $str = strtolower($str);
    }

    return trim(stripslashes($str));
}
