<?php namespace Buonzz\Patos\NodeVisitors;

use PhpParser\{Node, NodeTraverser, NodeVisitorAbstract};
use NilPortugues\Sql\QueryBuilder\Builder\GenericBuilder;

class ConstantNodeVisitor extends NodeVisitorAbstract{

    private $sql = '';
    private $file = '';

    public function set_file($val){
        $this->file = $val;
    }

    public function leaveNode(Node $node) {

        $builder = new GenericBuilder(); 

        if ($node instanceof \PhpParser\Node\Expr\ConstFetch){

            $constants = get_defined_constants(true);

            // dont include built-in constants
            if($node->name->parts[0] != 'null' 
                && $node->name->parts[0] != 'false' 
                && $node->name->parts[0] != 'true'
                && $node->name->parts[0] != 'NULL'
                && $node->name->parts[0] != 'FALSE'
                && $node->name->parts[0] != 'TRUE'
                //&& in_array($node->name->parts[0], $constants['user'])
                ){

                    $query = $builder->insert()
                    ->setTable('tbl_constant')
                    ->setValues([
                        'name' => $node->name->parts[0],
                        'line'    => $node->name->getStartLine(),
                        'file' =>  $this->file,
                    ]);
                
                $sql = $builder->write($query);

                $this->sql .= $sql . ";\n";                            
            }
        }
    }   

    public function getSQL(){
        return $this->sql;
    }
}