<?php
class DomXml4 {
    function open_file($file) {
        return domxml_open_file($file);
    }
    function get_elements_by_tagname($node, $tagname) {
        return $node->get_elements_by_tagname($tagname);
    }
    function get_attribute($node, $name) {
        return $node->get_attribute($name);
    }
    function has_attribute($node, $name) {
        return $node->has_attribute($name);
    }
    function get_content($node) {
        return $node->get_content();
    }
    function first_child($node) {
        return $node->first_child();
    }
    function next_sibling($node) {
        return $node->next_sibling();
    }
    function is_text_node($node) {
        return $node->node_type() == XML_TEXT_NODE || $node->node_type() == XML_CDATA_SECTION_NODE;
    }
    function tag_name($node) {
        return $node->node_name();
    }
    
}
class DomXml extends DomXml4 {
    var $version; 
    function DomXml() {
        if (function_exists("domxml_open_file") ) {
            $this->version = 4;
        } else {
            $this->version = 5;
        }
    }
    function open_file($file) {
        if ($this->version < 5) {
            return DomXml4::open_file($file);
        } else {
            $doc = new DOMDocument('1.0', 'iso-8859-2');
            $doc->Load($file);
            return $doc;
        }
    }
    function get_elements_by_tagname($node, $tagname) {
        if ($this->version < 5) {
            return DomXml4::get_elements_by_tagname($node, $tagname);
        } else {
            $nodelist = $node->getElementsByTagName($tagname);
            $na = array();
            for ($i=0;$i<$nodelist->length;$i++) {
                $na[] = $nodelist->item($i);
            }
            return $na;
        }
    }
    function get_attribute($node, $name) {
        if ($this->version < 5) {
            return DomXml4::get_attribute($node, $name);
        } else {
            return $node->getAttribute($name);
        }
    }
    function has_attribute($node, $name) {
        if ($this->version < 5) {
            return DomXml4::has_attribute($node, $name);
        } else {
            return $node->hasAttribute($name);
        }
    }
    function get_content($node) {
        if ($this->version < 5) {
            return DomXml4::get_content($node);
        } else {
            return $node->textContent;
        }
    }
    function first_child($node) {
        if ($this->version < 5) {
            return DomXml4::first_child($node);
        } else {
            return $node->firstChild;
        }
    }
    function next_sibling($node) {
        if ($this->version < 5) {
            return DomXml4::next_sibling($node);
        } else {
            return $node->nextSibling;
        }
    }
    function is_text_node($node) {
        if ($this->version < 5) {
            return DomXml4::is_text_node($node);
        } else {
            return $node->nodeType == XML_TEXT_NODE || $node->nodeType == XML_CDATA_SECTION_NODE;
        }
    }
    function tag_name($node) {
        if ($this->version < 5) {
            return DomXml4::tag_name($node);
        } else {
            return $node->nodeName;
        }
    }
    
}
?>
