<?php namespace Buonzz\Patos;

use Buonzz\Patos\Identifiers\AssignArrayKeyValue;
use Buonzz\Patos\Identifiers\TwoLevelArrayKeyValue;
use Buonzz\Patos\Identifiers\ClassNode;
use Buonzz\Patos\Identifiers\ConstantNode;

class NodeTypeIdentifier {
	public static function identify($node){
		$identifiers = [
			//new AssignArrayKeyValue(),
			//new TwoLevelArrayKeyValue(),
			new ClassNode(),
			new ConstantNode()
		];

		foreach($identifiers as $identifier){
			if($identifier->identify($node))
			{
				return $identifier;
			}		
		}

		return false;
	}

}