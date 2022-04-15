<?php

 namespace App\Entity;

 use \App\Db\Database;
 use \PDO;

 class User{

   /**
    * Identificador único
    * @var integer
    */
   public $id;

   /**
    * Título da vaga
    * @var string
    */
   public $name;

   /**
    * Data de cadastro do user
    * @var string
    */
   public $date;

  /**
    * Cadastrar uma nova registro no banco
    * @return boolean
    */
  public function cadastrar(){
    
    //DEFINIR A DATA
    $this->date = date('Y-m-d H:i:s');    

    $obDatabase = new Database('user');
    $this->id = $obDatabase->insert([
      'id'   => $this->id,
      'name' => $this->name,
      'date' => $this->date
    ]);

    //RETORNAR SUCESSO
    return true;
  }

  /**
    * Metodo para buscar uma registro expecifico
    * @param string $is
    * @return objUser
    */
    public static function getUser($id){
      return (new Database('user'))
        ->select('user = '.$id)
        ->fetchObject(self::class);
    }

  /**
    * Método responsável por obter as vagas do banco de dados
    * @param  string $where
    * @param  string $order
    * @param  string $limit
    * @return array
    */   
    public static function getUsers($where =null, $order =null, $limit =null){
      return (new Database('user'))
       ->select($where, $order, $limit)
       ->fetchAll(PDO::FETCH_CLASS, self::class);
  }

  /**
   * Método responsável por atualizar a vaga no banco
   * @return boolean
   */
  public function atualizar(){

    $this->date = date('Y-m-d H:i:s');
    
    return (new Database('user'))
      ->update('id = '.$this->id,[
        'name' => $this->name,
        'date' => $this->date
      ]);
  }

  /**
   * Método responsável por excluir a vaga do banco
   * @return boolean
   */
  public function excluir(){
    return (new Database('user'))
      ->delete('id = '.$this->id);
  }

}
