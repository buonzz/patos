<?php namespace Buonzz\Patos\Identifiers;

class ConstantNode {

    public function identify($node){

        if(!$node instanceof \PhpParser\Node\Expr\ConstFetch)
            return false;

        return true;
	}

    public function getInfo($node){
        return [
            'name' => $node->name->parts[0],
            'line' => $node->name->getStartLine()
        ];
    }

    public function getSql($node, $file){

        $output = '';
        $info = $this->getInfo($node);
        $output .= "INSERT INTO tbl_constant(`name`, `line`, `path`) VALUES('". $info['name'] . "','" . $info['line'] . "', '".  $file . "');\n";
        return $output;
    }
}