<?php

class Model {
  private $servername;
  private $username;
  private $password;
  private $mysqli;

  public function __construct(
    $servername = "db", 
    $username = "db_user", 
    $password = "db_password"
  ) {
    
    $this->servername = $servername;
    $this->username = $username;
    $this->password = $password;

    $this->connect();
  }

  private function connect() {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    // Create connection
    $this->mysqli = new mysqli($this->servername, $this->username, $this->password, 'db');

    // Check connection
    if ($this->mysqli->connect_error) {
      die("Connection failed: " . $this->mysqli->connect_error);
    }
  }

  public function execute($query) {
    /* Execute mysql query */
    $result = $mysqli->query($query);
    printf("Select returned %d rows.\n", $result->num_rows);
  }
}