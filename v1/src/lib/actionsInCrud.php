<?php
class ActionsInCrud{
	static function list(string $condicion) : array {
		return crud()->select(config(entity()."/table"),"*", $condicion);
	}
	static function find(string $attr, string $text) : array {
		return crud()->find(config(entity()."/table"),"*", $attr, $text);
	}
	static function setById(string $id , string $key , string $val) : bool {
		return crud()->update(
			config(entity()."/table"),
			"$key='$val'",
			"id='$id'"
		);
	}
	static function removeById(string $id) : bool {
		return crud()->remove( config(entity()."/table"), (int)$id );
	}
	static function set(array $row) : int {
		if($row["id"] == "new"){
			$row["id"]=NULL;
		}
		return crud()->save(
			config(entity()."/table"),
			$row
		);
	}
}