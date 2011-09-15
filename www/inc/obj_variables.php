<?php
require "_domxml.php";

/*
$GetVar = new GetVariables("/usr/local/mmain/xml/_variables.xml", "us");
print "<pre>";
print_r($GetVar->variables); 
print "</pre>";
*/

class GetVariables {

    var $variables = array();
    var $objects; //DOMNodeList 
    var $domxml;

    function GetVariables($file, $object_name) {
        $this->domxml = new DomXml();
        $dom = $this->domxml->open_file($file);
        $this->objects = $this->domxml->get_elements_by_tagname($dom, "object");
        if ($object = $this->find_object($object_name)) {
            $this->get_object_variables($object);
        } else {
            return 0;
        }
    }
    function find_object($object_name) {
        for ($i = 0; $i < count($this->objects); $i++) {
            $name = $this->domxml->get_attribute($this->objects[$i], "name");
            if ($name == $object_name) {
                return $this->objects[$i];
            }
        }
        return 0;
    }
    function get_object_variables($object) {
        if ($this->domxml->has_attribute($object, "extends")) {
            $parent_name = $this->domxml->get_attribute($object, "extends");
            if ($parent_object = $this->find_object($parent_name)) {
                $this->get_object_variables($parent_object);
            }
        }
        $this->get_node_variables($object);
    }
    function get_node_variables($node) {
        if ($protected = $this->domxml->get_elements_by_tagname($node, "protected")) {
    	    if ($variables = $this->domxml->get_elements_by_tagname($protected[0], "variables")) {
                $varnode = $this->domxml->first_child($variables[0]);
                while ($varnode) {
                    if ($this->domxml->tag_name($varnode) == "variable") {
                        $name = $this->domxml->get_attribute($varnode, "name");
                        $value = $this->get_node_value($varnode);
                        $this->variables[$name] = $value;
                    }
                    $varnode = $this->domxml->next_sibling($varnode);
                }
            }
        }
    }
    function get_node_value($node) {
        $type = $this->domxml->get_attribute($node, "type");
        $valnode = $this->domxml->first_child($node);
        while (is_object($valnode)) {
            if ($this->domxml->tag_name($node) == "value" && empty($type) && $this->domxml->is_text_node($valnode)) {
                return $this->domxml->get_content($valnode);
            }
            if ($this->domxml->tag_name($valnode) == "value") {
                if (empty($type) && !isset($value)) {
                    $value = $this->get_node_value($valnode);
                } elseif ($type == "list" || $type === "hash") {
                    if (!isset($value)) {
                        $value = array();
                    }
                    $v = $this->get_node_value($valnode);
                    $key = $this->domxml->get_attribute($valnode, "key");
                    if (empty($key) || (!is_numeric($key) && $type=="list")) {
                        $value[] = $v;
                    } else {
                        $value[$key] = $v;
                    }
                }
            }
            $valnode = $this->domxml->next_sibling($valnode);
        }
        if (isset($value)) return $value; else return "";
    }
}


?>
