<?php
/**
 * WPИ-XM Server Stack
 * Copyright © 2010 - 2017, Jens A. Koch <jakoch@web.de>
 * http://wpn-xm.org/
 *
 * This source file is subject to the terms of the MIT license.
 * For full copyright and license information, view the bundled LICENSE file.
 */

function getLabels()
{
    $opts = [
        'http' => [
            'method' => 'GET',
            'header' => [
                'User-Agent: PHP'
            ]
        ]
    ];
    $context = stream_context_create($opts);
    $content = file_get_contents("https://api.github.com/repos/wpn-xm/wpn-xm/labels", false, $context); 

    return json_decode($content, true); 
}

function getDescriptions()
{
    $content = file_get_contents('GithubIssueLabelDescriptions.json');
    return json_decode($content, true);
}

$labels       = getLabels();
$descriptions = getDescriptions();

function getDescription($descriptions, $labelName)
{
    foreach($descriptions as $desc) {
        if($desc['name'] === $labelName) {
            return $desc['desc'];
        }
    }  
}

$table_content = '';
#$description_array = [];
foreach($labels as $label)
{
    $url   = $label['url'];
    $name  = $label['name'];
    $color = $label['color'];
    $description = getDescription($descriptions, $label['name']);
    
    $style = 'padding: 5px; font-weight: bold; font-size: 14px; border-radius: 3px; color: #fff;';
    $style .= sprintf('background-color: #%s;', $color);    
    $link = sprintf('<a href="%s" style="%s">%s</a>', $url, $style, $name);

    $table_content .= '| +++ '.$link.' +++' . PHP_EOL;
    $table_content .= '|' . $description . PHP_EOL;;

    #$description_array[] = ['name' => $name, 'desc' => ''];
}

#file_put_contents('desc.json', json_encode($description_array, JSON_PRETTY_PRINT));

$table_header = '[options="header", cols="1,2", width="90"]' . PHP_EOL;
$table_header .= '|===' . PHP_EOL;
$table_header .= '| Label | Description' . PHP_EOL;

$table_footer = '|===' . PHP_EOL;

$content = $table_header . $table_content . $table_footer;

$intro = <<<EOT
== Overview of issue labels

Here is a full list of labels that we use in the 
https://github.com/wpn-xm/wpn-xm[GitHub issue tracker] and what they stand for.

EOT;

file_put_contents("labels.html", $intro . $content);
