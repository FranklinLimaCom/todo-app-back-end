<?php 

namespace Database\Models;
use \Database\Config\Sql;

ini_set('default_charset', 'UTF-8');


class Todo
{
    public static function find($id = null) { 
        $sql = new Sql();
        $results = [];
        if(!is_null($id)) {
            $results = $sql->execute("SELECT * FROM todos WHERE id = :ID", [":ID" => $id ])[0];
            $results["done"] = boolval($results["done"]);
        };

        if(is_null($id)) {
            $results = $sql->execute("SELECT * FROM todos");
            foreach($results as $value) { 
                $results[$key]["done"] = boolval($value["done"]); 
            }
        };

        return $results;
    }

    public static function findDone($id = null) { 
        $sql = new Sql();
        $results = [];
        if(!is_null($id)) {
            $results = $sql->execute("SELECT * FROM todos WHERE id = :ID, done = :DONE", [ 
                ":ID" => $id,
                ":DONE" => true
            ])[0];
            $results["done"] = boolval($results["done"]);
        } 

        if(is_null($id)) {
            $results = $sql->execute("SELECT * FROM todos WHERE done = :DONE", [ 
                ":DONE" => "1"
            ]);
            foreach($results as $key => $value) { 
                $results[$key]["done"] = boolval($value["done"]); 
            }
        } 

        return $results;
    }

    public static function findUndone($id = null) { 
        $sql = new Sql();
        $results = [];

        if(!is_null($id)) {
            $results = $sql->execute("SELECT * FROM todos WHERE id = :ID, done = :DONE", [ 
                ":ID" => $id,
                ":DONE" => true
            ])[0];
            $results["done"] = boolval($results["done"]);
        } 

        if(is_null($id)) {
            $results = $sql->execute("SELECT * FROM todos WHERE done = :DONE", [ 
                ":DONE" => "0"
            ]);
            foreach($results as $key => $value) { 
                $results[$key]["done"] = boolval($value["done"]); 
            }
        } 

        return $results;
    }

    public static function create($values) {
        $sql = new Sql();
        $sql->execute("INSERT INTO todos (name, task, done) VALUES (:NAME, :TASK, :DONE)", 
        [
            ":NAME" => $values["name"],
            ":TASK" => $values["task"],
            ":DONE" => $values["done"],
        ]);

        
        $result = $sql->execute("SELECT * FROM todos WHERE id=LAST_INSERT_ID()")[0]; 
        $result["done"] = boolval($result["done"]);

        return $result;
    }

    public static function update($values){
        $record = Todo::find($values["id"]);

        foreach($record as $key => $value) {
            if(!isset($values[$key])) $values[$key] = $value;
        }

        $sql = new Sql();
        $sql->execute("UPDATE todos SET name = :NAME, task = :TASK, done = :DONE WHERE id=:ID", 
        [
            ":ID" => $values["id"],
            ":NAME" => $values["name"],
            ":TASK" => $values["task"],
            ":DONE" => $values["done"],
        ]);

        $result = $sql->execute("SELECT * FROM todos WHERE id=:ID", [
            ":ID" => $values["id"]
        ])[0];

        $result["done"] = boolval($result["done"]);
        
        return $result;
    }

    public static function delete($id){
        
        $sql = new Sql();
        $sql->execute("DELETE FROM todos WHERE id = :ID", 
        [
            ":ID" => $id
        ]);

        return;
    }
}


 ?>