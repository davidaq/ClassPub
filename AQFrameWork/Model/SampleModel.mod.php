<?php die();?>
This is a Sample model to let you know how a good defined model look like.
A model is generaly a group of related database querying actions.
This first part is the description of this model.

%firstAction					//an action defined starts with a % following with the action name
$argument1					//definition of a argument of the action starting with $
$argument2=5					//=? defines a default value
:$argument2>$argument1		//restriction starts with a :
SELECT * FROM `sometable`
	WHERE `field`>$argument1 && `field`<$argument2

%secondAction							//Another start of a action or EOF will stop the definition of the previous action
SELECT count(*) FROM `sometable`	//Definitions of arguments are optional you can just write a SQL command

%_init										//action with this name will be called on the initialization of the model
CREATE TABLE IF NOT EXISTS `sometable` (
  `field` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
INSERT INTO `sometable` (`field`) VALUES('0');

%_install
