<?php

class Model {
  private $servername;
  private $username;
  private $password;
  private $database = 'db';
  private $mysqli;
  private $tables = [
    'profile' => 'playground.demo_profile_values',
    'users' => 'playground.demo_users'
  ];



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
    $this->mysqli = new mysqli($this->servername, $this->username, $this->password, $this->database);

    // Check connection
    if ($this->mysqli->connect_error) {
      die("Connection failed: " . $this->mysqli->connect_error);
    }
  }

  private function execute($query) {
    /* Execute mysql query */
    $result = $this->mysqli->query($query);
    if (!$result) die('Query Faild: ' . $query);
    return $result;
  }

  // option = 1 add indices to table search fields
  public function migrate($option = 0) {
    foreach($this->tables as $table) $this->execute("DROP TABLE IF EXISTS `$table`");

    // Import the database dump (sql file)
    if (!$this->importSql('home_exercise_db.sql')) return false;

    // Alter the tables for search
    if (!$this->addAdditionalSearchColumns()) return false;

    // Alter the tables for search
    if ($option === 1 && !$this->addIndices()) return false;


    return true;
  }

  private function addIndices() {
    $usersTable = $this->tables['users'];
    $profileTable = $this->tables['profile'];

    $sql = <<<SQL
      ALTER TABLE `$usersTable` ADD INDEX (search_mail1); 
      ALTER TABLE `$usersTable` ADD INDEX (search_mail2);
      ALTER TABLE `$usersTable` ADD INDEX (search_name1); 
      ALTER TABLE `$usersTable` ADD INDEX (search_name2);
      ALTER TABLE `$usersTable` ADD INDEX (search_name3); 
      ALTER TABLE `$profileTable` ADD INDEX (search_fullname1); 
      ALTER TABLE `$profileTable` ADD INDEX (search_fullname2);
    SQL;

    $res = $this->mysqli->multi_query($sql);
    do {
    } while ($this->mysqli->next_result());
    return $res;
  }

  private function importSql($filepath) {
    $sql = file_get_contents($filepath);
    $res = $this->mysqli->multi_query($sql);
    do {} while ($this->mysqli->next_result());
    return $res;
  }

  private function addAdditionalSearchColumns() {
    $usersTable = $this->tables['users'];
    $profileTable = $this->tables['profile'];

    $sql = <<<SQL
      ALTER TABLE `$usersTable` ADD COLUMN search_mail1 VARCHAR(64); 
      ALTER TABLE `$usersTable` ADD COLUMN search_mail2 VARCHAR(64);
      UPDATE `$usersTable` SET search_mail1 = LOWER(SUBSTRING_INDEX(SUBSTRING_INDEX(mail, '@', 1), '.', 1));
      UPDATE `$usersTable` SET search_mail2 = LOWER(SUBSTRING_INDEX(SUBSTRING_INDEX(mail, '@', 1), '.', -1));

      ALTER TABLE `$usersTable` ADD COLUMN search_name1 VARCHAR(60); 
      ALTER TABLE `$usersTable` ADD COLUMN search_name2 VARCHAR(60);
      ALTER TABLE `$usersTable` ADD COLUMN search_name3 VARCHAR(60);
      UPDATE `$usersTable` SET search_name2 = LOWER(SUBSTRING_INDEX(SUBSTRING_INDEX(name, '_', 2), '_', -1));
      UPDATE `$usersTable` SET search_name1 = LOWER(SUBSTRING_INDEX(name, '_', 1));
      UPDATE `$usersTable` SET search_name3 = LOWER(SUBSTRING_INDEX(name, '_', -1));

      ALTER TABLE `$profileTable` ADD COLUMN search_fullname1 VARCHAR(64); 
      ALTER TABLE `$profileTable` ADD COLUMN search_fullname2 VARCHAR(64);

      UPDATE `$profileTable` 
      SET search_fullname1 = LOWER(SUBSTRING_INDEX(value, ' ', 1))
      WHERE fid = 3;

      UPDATE `$profileTable` 
      SET search_fullname2 = LOWER(SUBSTRING_INDEX(value, ' ', -1))
      WHERE fid = 3;
    SQL;

    $res = $this->mysqli->multi_query($sql);
    do {} while ($this->mysqli->next_result());
    return $res;
  }

  public function search($str, $limit = 5) {
    $str = mysqli_real_escape_string($this->mysqli, $str);
    $sql = <<<SQL
      SELECT t_users.uid, value as fullname, mail, t_users.name as username, total_weight
      FROM (
        SELECT uid, SUM(weight) AS total_weight
        FROM (
          SELECT uid, search_name1 AS s1, search_name2 AS s2, search_name3 AS s3, 10 AS weight 
          FROM `playground.demo_users`
          WHERE search_name1 LIKE '$str%' OR
                search_name2 LIKE '$str%' OR
                search_name3 LIKE '$str%'
          
          UNION ALL
          
          SELECT uid, search_fullname1 AS s1, search_fullname2 AS s2, NULL AS s3, 40 AS weight  
          FROM `playground.demo_profile_values`
          WHERE search_fullname1 LIKE '$str%' OR
                search_fullname2 LIKE '$str%'
          
          UNION ALL
          
          SELECT uid, search_mail1 AS s1, search_mail2 AS s2, NULL AS s3, 20 AS weight  
          FROM `playground.demo_users`
          WHERE search_mail1 LIKE '$str%' OR
                search_mail2 LIKE '$str%'
        ) AS t_combined
        GROUP BY uid
        ORDER BY total_weight DESC
        LIMIT $limit
      ) AS t_aggregated
      LEFT JOIN `playground.demo_users` as t_users
      ON t_aggregated.uid = t_users.uid
      LEFT JOIN `playground.demo_profile_values` as t_profile
      ON t_aggregated.uid = t_profile.uid
      WHERE t_profile.fid = 3
    SQL;

    $start = microtime(true);

    $res = $this->execute($sql);

    $elapsed = microtime(true) - $start;

    $rows = [];
    while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
      $rows[] = $row;
    }
    return [
      'rows' => $rows,
      'elapsed' => $elapsed
    ];
  }
}