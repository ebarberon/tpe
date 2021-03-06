<?php

class productosModel{

    private $db;

    function __construct(){
        $this->db = new PDO('mysql:host=localhost;'.'dbname=db_tpe;charset=utf8', 'root','');
    }
    
    function obtenerProductos(){
        $query = $this->db->prepare('SELECT producto.id_producto, producto.nombre, producto.descripcion, producto.id_categoria, categoria.nombre_categoria FROM producto INNER JOIN categoria ON producto.id_categoria = categoria.id_categoria');
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);

    }

    function insertarProducto($nombre,$descripcion,$id_categoria){
        $query = $this->db->prepare('INSERT INTO producto (nombre, descripcion, id_categoria) VALUES (?,?,?)');
        $query->execute([$nombre,$descripcion,$id_categoria]);
    }

    function borrarProducto($id_producto){
        $query = $this->db->prepare('DELETE FROM producto WHERE id_producto=(?)');
        $query->execute([$id_producto]);
    }

    function editarProducto($id_producto,$nombre,$descripcion,$id_categoria){
        $query = $this->db ->prepare('UPDATE producto SET nombre=?, descripcion = ?, id_categoria = ? WHERE id_producto=?');
        $query->execute([$nombre,$descripcion,$id_categoria,$id_producto]);
    }
}
?>