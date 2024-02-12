<?php namespace Buonzz\Patos\Identifiers;

class AssignArrayKeyValue {

    public function identify($node){

        // make sure this is an statement
        if(!$node instanceof \PhpParser\Node\Stmt\Expression)
            return false;

        // make sure $node->expr exists
        if(!property_exists($node, 'expr') || !($node->expr instanceof \PhpParser\Node\Expr\Assign))
            return false;

        // make sure $node->expr->var exists and its assignment
        if(!property_exists($node->expr, 'var') || !($node->expr->var instanceof \PhpParser\Node\Expr\ArrayDimFetch))
            return false;

        // make sure $node->expr->var->var exists
        if(!property_exists($node->expr->var, 'var') || !($node->expr->var->var instanceof \PhpParser\Node\Expr\Variable))
        return false;

        // make sure $node->expr->var->var->name exists
        if(!property_exists($node->expr->var->var, 'name'))
            return false;

        // make sure $node->expr->var->dim exists
        if(!property_exists($node->expr->var, 'dim'))
            return false;

        // make sure $node->expr->var->dim->value exists
        if((is_object($node->expr->var->dim) || is_string($node->expr->var->dim)) && !property_exists($node->expr->var->dim, 'value'))
            return false;

        // make sure $node->expr->expr exists
        if(!property_exists($node->expr, 'expr')){
            return false;
        }

        // make sure $node->expr->expr->value exists or $node->expr->expr->name
        if(!property_exists($node->expr->expr, 'value') && !property_exists($node->expr->expr, 'name')){
            return false;            
        }

        return true;
	}

    function getKey($node){
        return $node->expr->var->dim->value;
    }

    function getValue($node){
        if($node->expr->expr instanceof \PhpParser\Node\Scalar\String_)
            return $node->expr->expr->value;
        else if($node->expr->expr instanceof \PhpParser\Node\Expr\ConstFetch)
            return $node->expr->expr->name->parts[0];
        else 
            return null;
    }

    public function getSql($node, $file){

        $output = '';
        $output .= "INSERT INTO tbl_key_values(`array_key`, `array_value`, `file`) VALUES('". $this->getKey($node) . "','" . $this->getValue($node) ."','" . $file . "');";

        return $output;
    }
}