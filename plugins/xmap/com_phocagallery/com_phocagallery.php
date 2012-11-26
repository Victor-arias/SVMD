<?php
/**
 * @author Dario Pintaric, http://www.dblaze.eu
 * @email dario.pintaric@gmail.com
 * @version $Id: com_phocagallery.php
 * @package Xmap
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @description Xmap plugin for Phoca Gallery
 */
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

class xmap_com_phocagallery {

    function getTree(&$xmap, &$parent, &$params) {
        $link_query = parse_url($parent->link);
        parse_str(html_entity_decode($link_query['query']), $link_vars);
        $catid = JArrayHelper::getValue($link_vars, 'id', 0);
        $expand_categories = JArrayHelper::getValue($params, 'expand_categories', 1);
        $params['expand_categories'] = ( $expand_categories == 1
                || ( $expand_categories == 2 && $xmap->view == 'xml')
                || ( $expand_categories == 3 && $xmap->view == 'html')
                || $xmap->view == 'navigator');
        $show_sub_categories = JArrayHelper::getValue($params, 'show_sub_categories', 1);
        $params['show_sub_categories'] = ( $show_sub_categories == 1
                || ( $show_sub_categories == 2 && $xmap->view == 'xml')
                || ( $show_sub_categories == 3 && $xmap->view == 'html')
                || $xmap->view == 'navigator');
        $priority = JArrayHelper::getValue($params, 'cat_priority', $parent->priority, '');
        $changefreq = JArrayHelper::getValue($params, 'cat_changefreq', $parent->changefreq, '');
        if ($priority == '-1')
            $priority = $parent->priority;
        if ($changefreq == '-1')
            $changefreq = $parent->changefreq;
        $params['cat_priority'] = $priority;
        $params['cat_changefreq'] = $changefreq;
        $priority = JArrayHelper::getValue($params, 'file_priority', $parent->priority, '');
        $changefreq = JArrayHelper::getValue($params, 'file_changefreq', $parent->changefreq, '');
        if ($priority == '-1')
            $priority = $parent->priority;
        if ($changefreq == '-1')
            $changefreq = $parent->changefreq;
        $params['file_priority'] = $priority;
        $params['file_changefreq'] = $changefreq;
        if ($catid > 0) {
            $cat = xmap_com_phocagallery::getDBCurCateg($catid);
            $root = false;
        } else {
            $cat = new stdClass();
            $cat->id = 0;
            $root = true;
        }
        xmap_com_phocagallery::getPhocaCategoriesTree($xmap, $parent, $params, $cat, $root);
    }

    function getPhocaCategoriesTree(&$xmap, &$parent, &$params, $cat, $root) {
        if ($cat->id > 0) {
            $xmap->changeLevel(1);
            $node = new stdclass;
            $node->id = $parent->id;
            $node->uid = $parent->id . 'c' . $cat->id;
            $node->pid = $cat->parent_id;
            $node->name = $cat->title;
            $node->priority = $params['cat_priority'];
            $node->changefreq = $params['cat_changefreq'];
            $node->link = 'index.php?option=com_phocagallery&amp;view=category&amp;id=' . $cat->id . ':' . $cat->alias;
            $node->tree = array();
            if (($xmap->printNode($node) !== FALSE) && $params['expand_categories']) {
                $childs = xmap_com_phocagallery::getDBCatChilds($cat->id);
                $parent_alias = $cat->alias;
                $xmap->changeLevel(1);
                foreach ($childs as $row) {
                    $node = new stdclass;
                    $node->id = $parent->id;
                    $node->uid = $parent->id . 'a' . $row->id;
                    $node->pid = $row->parent_id;
                    $node->name = $row->title;
                    $node->priority = $params['cat_priority'];
                    $node->changefreq = $params['cat_changefreq'];
                    $node->link = 'index.php?option=com_phocagallery&amp;Itemid=' . $parent->id . '&amp;catid=' . $cat->id . ':' . $parent_alias . '&amp;id=' . $row->id . ':' . $row->alias . '&amp;view=detail';
                    $node->tree = array();
                    $xmap->printNode($node);
                }
                $xmap->changeLevel(-1);
            }
            if ($root || $params['show_sub_categories']) {
                $cats_ch = xmap_com_phocagallery::getDBCategories($cat->id);
                foreach ($cats_ch as $cat_ch) {
                    xmap_com_phocagallery::getPhocaCategoriesTree($xmap, $parent, $params, $cat_ch, $root);
                }
            }
            $xmap->changeLevel(-1);
        } else {
            $cats_children = xmap_com_phocagallery::getDBCategories($cat->id);
            foreach ($cats_children as $cat_child) {
                xmap_com_phocagallery::getPhocaCategoriesTree($xmap, $parent, $params, $cat_child, $root);
            }
        }
    }

    function getDBCategories($catid) {
        $db = &JFactory::getDBO();
        $query = 'SELECT id, title, name, alias, parent_id FROM #__phocagallery_categories WHERE parent_id = ' . $catid . ' AND published=1 ORDER BY ordering';
        $db->setQuery($query);
        $lists = $db->loadObjectList();
        return $lists;
    }

    function getDBCatChilds($catid) {
        $db = &JFactory::getDBO();
        $query = 'SELECT id, title, \'\' as name, alias, ' . $catid . ' as parent_id FROM #__phocagallery WHERE catid = ' . $catid . ' AND published=1 ORDER BY ordering';
        $db->setQuery($query);
        $lists = $db->loadObjectList();
        return $lists;
    }

    function getDBCurCateg($catid) {
        $db = &JFactory::getDBO();
        $query = 'SELECT id, title, parent_id, alias FROM #__phocagallery_categories WHERE id = ' . $catid;
        $db->setQuery($query);
        $cat = $db->loadObject();
        return $cat;
    }
}
?>