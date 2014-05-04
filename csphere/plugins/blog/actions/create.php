<?php

/**
 * Create action
 *
 * PHP Version 5
 *
 * @category  Plugins
 * @package   Blog
 * @author    Daniel Schalla <schalla@csphere.eu>
 * @copyright 2013 cSphere Team
 * @license   http://opensource.org/licenses/bsd-license Simplified BSD License
 * @link      http://www.csphere.eu
 **/

 // Get RAD class for this action
$rad = new \csphere\core\rad\Create('blog');

$t = new stdClass();
$t->tags = '';

// Define closure to execute before record is send to database
$record = function ($data) use ($t) {

    $t->tags = $data['blog_tags'];
    unset($data['blog_tags']);

    return $data;
};

// Define closure to execute before record is send to database
$afterRecord = function ($data) use ($t) {

    \csphere\plugins\tags\classes\Tags::parseInputTags(
        $t->tags, 'blog', $data['blog_id']
    );

    $data['blog_date']=time();

    return $data;
};

$rad->callRecord($record);

$rad->callAfterRecord($afterRecord);

// Define closure to execute before data is send to template
$data = function ($data) {

    \csphere\plugins\tags\classes\Tags::initTagInput();
    $data['blog_tags'] = '';

    return $data;
};

$rad->callData($data);

// Delegate action
$rad->delegate();