<?php

abstract class SQL
{
    /**
     * @var PDO
     */
    public $pdo;
    abstract public function column($q, $p = null, $k = 0);
    abstract public function query($q, $p = null);

    public function delete($t, $w = 0)
    {
        $q = "DELETE FROM $t";
        list($w, $p) = $this->where($w);
        if ($w) {
            $q .= " WHERE $w";
        }
        return ($s = $this->query($q, $p)) ? $s->rowCount() : 0;
    }
    public function select($c = 0, $t = 0, $w = 0, $l = 0, $o = 0, $s = 0)
    {
        $c = $c ?: '*';
        $q = "SELECT $c FROM \"$t\"";
        list($w, $p) = $this->where($w);
        if ($w) {
            $q .= " WHERE $w";
        }
        return array($q . ($s ? " ORDER BY $s" : '') . ($l ? " LIMIT $o,$l" : ''), $p);
    }
    public function count($t, $w = 0)
    {
        list($q, $p) = $this->select('COUNT(*)', $t, $w);
        return $this->column($q, $p);
    }

    public function insert($t, $d)
    {
        $q = "INSERT INTO $t (\"" . implode('","', array_keys($d)) . '")VALUES(' . rtrim(str_repeat('?,', count($d)), ',') . ')';
        return $this->query($q, array_values($d)) ? $this->pdo->lastInsertId() : 0;
    }

    public function update($t, $d, $w = null)
    {
        $q = "UPDATE $t SET \"" . implode('"=?,"', array_keys($d)) . '"=? WHERE ';
        list($a, $b) = $this->where($w);
        return (($s = $this->query($q . $a, array_merge(array_values($d), $b))) ? $s->rowCount() : null);
    }

    public function where($w)
    {
        $a = $s = array();
        if ($w) {
            foreach ($w as $c => $v) {
                if (is_int($c)) {
                    $s[] = $v;
                } else {
                    $s[] = "\"$c\"=?";
                    $a[] = $v;
                }
            }
        }
        return array(join(' AND ', $s), $a);
    }
}
