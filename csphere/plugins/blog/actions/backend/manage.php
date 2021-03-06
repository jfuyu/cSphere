<?php

/**
 * List action
 *
 * PHP Version 5
 *
 * @category  Plugins
 * @package   Blog
 * @author    Daniel Schalla <contact@csphere.eu>
 * @copyright 2013 cSphere Team
 * @license   http://opensource.org/licenses/bsd-license Simplified BSD License
 * @link      http://www.csphere.eu
 **/

// Add breadcrumb navigation
$bread = new \csphere\core\template\Breadcrumb('admin');
$bread->add('content');
$bread->plugin('blog', 'manage');
$bread->trace();

// Get RAD class for this action
$rad = new \csphere\core\rad\Listed('blog');

$rad->map('manage', 'manage');

// Define order columns
$order = ['blog_title', 'blog_date', 'blog_publish'];

// Define closure to execute before data is send to template
$data = function ($data) {

    for ($i = 0; $i < count($data); ++$i) {

        // Get record option for blog
        $dm_options = new \csphere\core\datamapper\Options('blog');
        $options    = $dm_options->load();

        $data[$i]['blog_title'] = \csphere\core\strings\Format::doStraightShorten(
            $data[$i]['blog_title'], $options['title_length_manage']
        );

        $data[$i]['blog_tags']
            = \csphere\plugins\tags\classes\Tags::usedTagsNamesAsString(
                'blog', $data[$i]['blog_id']
            );

    }

    return $data;
};

$rad->callData($data);

// Define closure to execute before finder fetches results
$search = function ($object) {

    \csphere\plugins\tags\classes\Tags::joinTags($object, "blog");
    $object->groupBy('blog_id');

    return $object;
};

$rad->callFinder($search);

$rad->search(['blog_title', 'tag_name'], true, true);

$rad->delegate('blog_id DESC', $order);
