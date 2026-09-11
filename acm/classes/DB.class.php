<?php

require_once 'SQL.class.php';

class DB extends SQL
{
    public $pdo;
    public $i = '`';
    protected static $q = array();
    public $statement;

    public function __construct($c)
    {
        extract($c);
        $this->pdo = new PDO($dsn, $user, $pass, $args);
    }
    public function column($q, $p = null, $k = 0)
    {
        return ($s = $this->query($q, $p)) ? $s->fetchColumn($k) : 0;
    }
    public function row($q, $p = null)
    {
        return ($s = $this->query($q, $p)) ? $s->fetch(PDO::FETCH_OBJ) : 0;
    }
    public function fetch($q, $p = null)
    {
        return ($s = $this->query($q, $p)) ? $s->fetchAll(PDO::FETCH_OBJ) : 0;
    }
    public function query($q, $p = null)
    {
        $s = $this->pdo->prepare(self::$q[] = str_replace('"', $this->i, $q));
        $s->execute($p);

        $this->statement = $s;
        return $s;
    }
    public function total($t, $q = null, $p = null)
    {
		$sql = 'SELECT COUNT(*) as total FROM ' . $t;
		if ($q != '') {
			$sql .= ' WHERE ' . $q;
		}
		if (is_array($p)) {
			$row = $this->row($sql, $p);
		} else {
			$row = $this->row($sql);
		}
		return $row->total;
    }

    public function printQuery($query, $values)
    {
        $db = $this->pdo;

        $ret = preg_replace_callback(
            "#(\\?)(?=(?:[^']|['][^']*')*$)#ms",
            // Notice the &$values - here, we want to modify it.
            function ($match) use ($db, &$values) {
                if (empty($values)) {
                    throw new PDOException('not enough values for query');
                }
                $value  = array_shift($values);

                // Handle special cases: do not quote numbers, booleans, or NULL.
                if (is_null($value)) {
                    return 'NULL';
                }
                if (true === $value) {
                    return 'true';
                }
                if (false === $value) {
                    return 'false';
                }
                if (is_numeric($value)) {
                    return $value;
                }

                return $db->quote($value);
            },
            $query
        );

        if (!empty($values)) {
            throw new PDOException('not enough placeholders for values');
        }

        echo '<pre>' . $ret;
        exit();
    }
}
