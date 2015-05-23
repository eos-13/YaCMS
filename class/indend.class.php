<?php

$path=__DIR__.'/../lib/indenter/src/';
set_include_path(get_include_path()  . $path);

require_once(__DIR__."/../lib/indenter/vendor/autoload.php");


use \Gajus\Dindent\Indenter;

class indent {
    public
    $log = array(),
    $options = array(
            'indentation_character' => '    '
    ),
    $inline_elements = array('b', 'big', 'i', 'small', 'tt', 'abbr', 'acronym', 'cite', 'code', 'dfn', 'em', 'kbd', 'strong', 'samp', 'var', 'a', 'bdo', 'br', 'img', 'span', 'sub', 'sup'),
    $temporary_replacements_script = array(),
    $temporary_replacements_inline = array();

    const ELEMENT_TYPE_BLOCK = 0;
    const ELEMENT_TYPE_INLINE = 1;

    const MATCH_INDENT_NO = 0;
    const MATCH_INDENT_DECREASE = 1;
    const MATCH_INDENT_INCREASE = 2;
    const MATCH_DISCARD = 3;
    public function reindent($code)
    {
        return Indenter::indent($code);

    }

}