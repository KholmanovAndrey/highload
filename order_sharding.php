<?php
/**
* Order
*  - id: int
*  - name: varchar
*  - date: datetime
*  - user_id: int
*  - sum: float
*/

class Order {
     public $id;
     public $name;
     public $date;
     public $user_id;
     public $sum;

     public function __construct($name, $date, $user_id, $sum) {
          $this->name = $name;
          $this->date = $date;
          $this->user_id = $user_id;
          $this->sum = $sum;
     }
}

class OrderStorage {
     protected function runQuery($query, Order $order) {
          mysql_query($query, ShardingStragegy::getInstance()->getConnection($order));
     }

     public function insert(Order $order) {
          //добавить запись и вернуть объект с id
          $this->runQuery("insert into order (name, date, user_id, sum) VALUES ({$this->name},{$this->date},{$this->user_id},{$this->sum})", $order);
          return mysql_insert_id();
     }

     public function update(Order $order) {
          //обновить объект
          $this->runQuery("update lalala", $order);
     }

     public function delete(Order $order) {
          //удалить объект
          $this->runQuery("delete lalalal", $order);
     }
}

class ShardingStragegy {
    protected static $instance = null;
    protected $server1;
    protected $server2;

    protected function __construct() {
         $this->server1 = mysql_connect('10.0.2.4', 'root', 'password');
         $this->server2 = mysql_connect('10.0.2.5', 'root', 'password');
    }

    public static function getInstance() {
        if (static::$instance == null) {
            static::$instance = new self();
        }

        return static::$instance;
    }

    // распределение между серверами, если нечетный на 1 сервер, четный на 2 сервер
    public function getConnection(Order $order) {
         $server = $this->server1;
         if ($order->user_id % 2 == 0) $server = $this->server2; 
         return $server;
    }
}

$storage = new OrderStorage();

$someOrder = new Order('test order1', date('Ymd'), 1, 100);
$storage->insert($someOrder);