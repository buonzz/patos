<?php namespace Buonzz\Patos\NodeVisitors;

use PhpParser\{Node, NodeTraverser, NodeVisitorAbstract};

class FunctionNodeVisitor extends NodeVisitorAbstract{

    private $sql = '';
    private $file = '';

    public function set_file($val){
        $this->file = $val;
    }

    public function leaveNode(Node $node) {
        if ($node instanceof \PhpParser\Node\Stmt\Function_){
            $df = get_defined_functions();
            // dont compile built-in functions
            //if(in_array($node->name->name, $df['user']))
                $this->sql .= "INSERT INTO `tbl_functions`(`name`,`start_line`, `end_line`,`file`) VALUES('". $node->name->name . "','". $node->name->getStartLine(). "','". $node->name->getEndLine(). "','".  $this->file . "');\n";                            
        }
    }   

    public function getSQL(){
        return $this->sql;
    }
}