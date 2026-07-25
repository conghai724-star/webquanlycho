<?php

/**
 * Database adapter shared by the legacy frontend and the adminmaster module.
 *
 * Legacy code uses query()/fetch_*(), while the adminmaster models use named
 * parameters, select(), selectOne() and transactions.  Keeping both APIs in
 * one adapter prevents the new module from bypassing the existing connection.
 */
class Database {
    private $connection;
    private $result = null;
    private static $instance;

    public function __construct() {
    }

    public static function getInstance() {
        if (!self::$instance) {
            $database = new self();
            $database->connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            self::$instance = $database;
        }

        return self::$instance;
    }

    public function connect($address, $account, $password, $name) {
        $this->connection = mysqli_connect($address, $account, $password, $name);
        if (!$this->connection) {
            throw new RuntimeException('Database connection failed: ' . mysqli_connect_error());
        }

        mysqli_set_charset($this->connection, 'utf8mb4');
    }

    public function closeConnect() {
        if ($this->connection) {
            mysqli_close($this->connection);
            $this->connection = null;
        }
    }

    public function sqlQuote($value) {
        return $this->escapestring($value);
    }

    public function escapestring($string) {
        return mysqli_real_escape_string($this->connection, (string) $string);
    }

    /**
     * Execute a legacy SQL string or a parameterized adminmaster statement.
     * Named placeholders are converted to mysqli positional placeholders.
     */
    public function query($sql, array $params = []) {
        if (!$params) {
            $this->result = mysqli_query($this->connection, $sql);
            if ($this->result === false) {
                throw new RuntimeException('Database query failed: ' . mysqli_error($this->connection));
            }
            return $this->result;
        }

        $values = [];
        $statementSql = preg_replace_callback('/(?<!:):([A-Za-z_][A-Za-z0-9_]*)/', function ($matches) use (&$values, $params) {
            $key = $matches[1];
            if (!array_key_exists($key, $params)) {
                throw new InvalidArgumentException("Missing database parameter: {$key}");
            }
            $values[] = $params[$key];
            return '?';
        }, $sql);

        $statement = mysqli_prepare($this->connection, $statementSql);
        if ($statement === false) {
            throw new RuntimeException('Database prepare failed: ' . mysqli_error($this->connection));
        }

        $this->bindParams($statement, $values);
        if (!mysqli_stmt_execute($statement)) {
            $error = mysqli_stmt_error($statement);
            mysqli_stmt_close($statement);
            throw new RuntimeException('Database query failed: ' . $error);
        }

        $result = mysqli_stmt_get_result($statement);
        $this->result = $result === false ? true : $result;
        mysqli_stmt_close($statement);
        return $this->result;
    }

    public function select($sql, array $params = []) {
        $result = $this->query($sql, $params);
        if (!$result instanceof mysqli_result) {
            return [];
        }

        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function selectOne($sql, array $params = []) {
        $rows = $this->select($sql, $params);
        return $rows[0] ?? null;
    }

    public function beginTransaction() {
        if (!mysqli_begin_transaction($this->connection)) {
            throw new RuntimeException('Could not start database transaction: ' . mysqli_error($this->connection));
        }
    }

    public function commit() {
        if (!mysqli_commit($this->connection)) {
            throw new RuntimeException('Could not commit database transaction: ' . mysqli_error($this->connection));
        }
    }

    public function rollback() {
        if (!mysqli_rollback($this->connection)) {
            throw new RuntimeException('Could not roll back database transaction: ' . mysqli_error($this->connection));
        }
    }

    public function inTransaction() {
        // mysqli does not expose this state.  The adminmaster module only uses
        // this as a guard before rolling back, and rollback is safe here.
        return true;
    }

    public function fetch_array($first_row = false) {
        if (!$this->result instanceof mysqli_result) {
            return $first_row ? null : [];
        }

        if ($first_row) {
            return mysqli_fetch_array($this->result);
        }

        $rows = [];
        while ($row = mysqli_fetch_array($this->result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function fetch_object($first_row = false) {
        if (!$this->result instanceof mysqli_result) {
            return $first_row ? null : [];
        }

        if ($first_row) {
            return mysqli_fetch_object($this->result);
        }

        $rows = [];
        while ($row = mysqli_fetch_object($this->result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function num_row() {
        return $this->result instanceof mysqli_result ? mysqli_num_rows($this->result) : 0;
    }

    public function insert_id() {
        return mysqli_insert_id($this->connection);
    }

    public function lastInsertId() {
        return $this->insert_id();
    }

    private function bindParams($statement, array &$values) {
        if (!$values) {
            return;
        }

        $types = '';
        foreach ($values as $value) {
            $types .= is_int($value) ? 'i' : (is_float($value) ? 'd' : 's');
        }

        $bindValues = [$types];
        foreach ($values as $index => &$value) {
            $bindValues[] = &$value;
        }

        if (!call_user_func_array('mysqli_stmt_bind_param', array_merge([$statement], $bindValues))) {
            throw new RuntimeException('Could not bind database parameters: ' . mysqli_stmt_error($statement));
        }
    }
}
