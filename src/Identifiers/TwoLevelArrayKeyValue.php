<?php namespace Buonzz\Patos\Identifiers;

class TwoLevelArrayKeyValue {

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

        // make sure $node->expr->var->dim exists
        if(!property_exists($node->expr->var, 'dim') || !($node->expr->var->dim instanceof \PhpParser\Node\Scalar\String_))
            return false;

        // make sure $node->expr->var->dim exists
        if(!property_exists($node->expr->var, 'dim') || !($node->expr->var->dim instanceof \PhpParser\Node\Scalar\String_))
            return false;

        // make sure $node->expr->expr exists
        if(!property_exists($node->expr, 'expr'))
            return false;

        // make sure $node->expr->expr exists
        if(!property_exists($node->expr->expr, 'items'))
            return false;

        return true;
	}

    function getKey($node){
        return $node->expr->var->dim->value;
    }

    function getValue($node){
        $output = [];

        foreach($node->expr->expr->items as $idx => $item){
            if($item->value instanceof \PhpParser\Node\Scalar\String_){
                $key = '';
                if(isset($item->key->value))
                    $key = $item->key->value;
                else if(isset($item->key->name) && isset($item->key->name->parts) && isset( $item->key->name->parts[0])) 
                    $key = $item->key->name->parts[0];
                else if(is_string($item->value->value))
                    $key = $idx;
                else
                    echo "1. cannot find the key! " . print_r($item->value, true);


                $output[$key] = $item->value->value;
            }
            else if($item->value instanceof \PhpParser\Node\Expr\ConstFetch)
            {
                $key = '';
                if(isset($item->key->value))
                    $key = $item->key->value;
                else if(isset($item->key->name) && isset($item->key->name->parts) && isset( $item->key->name->parts[0])) 
                    $key = $item->key->name->parts[0];
                else if(isset($item->value->name) && isset($item->value->name->parts) && isset( $item->value->name->parts[0])) 
                    $key = $item->value->name->parts[0];
                else
                    echo "2. cannot find the key! " . print_r($item->value, true);

                $output[$key] = $item->value->name->parts[0];
            }
        }

        return $output;
    }
}