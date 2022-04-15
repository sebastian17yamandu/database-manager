<?php

 namespace App\Db;

 use \PDO;
 use \PDOException;

 class Database{

  /**
    * Host de conexão com o banco de dados
    * @var string
    * private static $hostname ='localhost';
    */
  private static $hostname;

  /**
    * nomo do banco de dados
    * @var string
    */
  private static $database;

  /**
    * Usuario do banco de dados
    * @var string
    */
  private static $userDatabase;

  /**
    * Senha do usuario do banco de dados
    * @var string
    */
  private static $ppwdDatabase;


  /**
    * Porta de acesso ao banco de dados
    * @var string
    */
  private static $portDatabase;

  /**
    * Nome da table a ser manipulada
    * @var string
    */
  private $table;

  /**
    * Instanciar a conexão com o banco de dados
    * @var PDO
    */
  private $connection;

  public static function config($hostname, $database, $userDatabase, $ppwdDatabase, $portDatabase = 3306){
    self::$hostname     =$hostname;
    self::$database     =$database;
    self::$userDatabase =$userDatabase;
    self::$ppwdDatabase =$ppwdDatabase;
    self::$portDatabase =$portDatabase;
  }

  /**
    * Definir tabela e instancia a conexão
    * @param [type] $table
    */
  public function __construct($table =null)
  {
    $this->table =$table;
    $this->setConnection();
  }

  /**
    * Criar uma conexão com o banco de dados
    */
  private function setConnection(){
    try {
      $this->connection = new PDO('mysql:host='.self::$hostname.';dbname='.self::$database.';port='.self::$portDatabase,self::$userDatabase,self::$ppwdDatabase);
      $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      die('Error: '.$e->getMessage());
    }
  }

  /**
    * Executar query dentro do banco de dados
    * @param string $query
    * @param array $params
    * @return PDOStatement
    */
  public function execute($query, $params = []){
    try {
      $stmt =$this->connection->prepare($query);
      $stmt->execute($params);
      return $stmt;
    } catch (PDOException $e) {
      die('Error: '.$e->getMessage());
    }
  }

  /**
    * Inseir dados no banco de dados
    * @param array $values [field => value]
    * @return integer ID inserido
    */
  public function insert($values){
    
    // Setar values no insert
    $fields =array_keys($values);

    // igualando o numero de values enviados com o numero de campos no sql
    $binds =array_pad([], count($fields), '?');

    $query ='INSERT INTO '.$this->table.' ('.implode(', ', $fields).') VALUES('.implode(', ', $binds).')';

    // EXECUTA O INSERT
    $this->execute($query, array_values($values));

    // RETORNA O ID INSEIDO
    return $this->connection->lastInsertId();
    
  }

   /**
    * Executar uma consulta no banco
    * @param string $fields
    * @param string $where
    * @param string $order
    * @param string $limit
    * @return PDOStatement
    */
   public function select($where =null, $order =null, $limit =null, $fields ='*'){

     // DADOS DA QUERY
     $where =strlen($where) ? 'WHERE '.$where : '';
     $order =strlen($order) ? 'ORDER BY '.$order : '';
     $limit =strlen($limit) ? 'LIMIT '.$limit : '';

     $query ='SELECT '.$fields.' FROM '.$this->table.' '.$where.' '.$order.' '.$limit;
     return $this->execute($query);

   }

  /**
   * Executar UPDATE na base de dados
   * @param  string $where
   * @param  array $values [ field => value ]
   * @return boolean
   */
  public function update($where, $values){

    // DADOS DA QUERY
    $fields =array_keys($values);

    // MONTA A QUERY
    $query ='UPDATE '.$this->table.' SET '.implode('=?, ', $fields).'=? WHERE '.$where;

    // EXECUTA A QUERY
    $this->execute($query, array_values($values));

    //var_dump($query);
    //exit;

    // RETORNA ERROR OU SUCCESS
    return true;
  }

  /**
   * Excluir dados do banco
   * @param string $where
   * @return boolean
   */
  public function delete($where){

    // MONTA QUERY
    $query ='DELETE FROM '.$this->table.' WHERE  '.$where;

    // EXECUTA A QUERY
    $this->execute($query);

    //RETORNA ERROR OU SUCESSO
    return true;
  }

 }
