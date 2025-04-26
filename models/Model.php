<?php
require_once __DIR__ . '/../config/database.php';

abstract class Model {
    protected $db;
    protected $table;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function findAll($conditions = [], $order = '', $limit = null) {
        $sql = "SELECT * FROM " . $this->table;

        if (!empty($conditions)) {
            $sql .= " WHERE ";
            error_log("Conditions array: " . print_r($conditions, true));
            $whereClauses = [];
            foreach ($conditions as $key => $value) {
                $whereClauses[] = $value === null ? "$key IS NULL" : "$key = :$key";
            }
            $sql .= implode(" AND ", $whereClauses);
            error_log("WHERE clause: " . implode(" AND ", $whereClauses));
        }

        if (!empty($order)) {
            error_log("ORDER BY clause: " . $order);
            $sql .= " ORDER BY " . $order;
        }

        if ($limit !== null) {
            $sql .= " LIMIT " . $limit;
        }

        $stmt = $this->db->prepare($sql);

        if (!empty($conditions)) {
            foreach ($conditions as $key => $value) {
                if ($value !== null) {
                    $stmt->bindValue(":$key", $value);
                }
            }
        }

        error_log("Final SQL query: " . $sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        error_log("Query result: " . print_r($result, true));
        return $result;
    }

    public function findById($id) {
        $sql = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        try {
            error_log("Starting create operation for table {$this->table}");
            error_log("Data to insert: " . print_r($data, true));
            
            $columns = implode(", ", array_keys($data));
            $values = ":" . implode(", :", array_keys($data));
            
            $sql = "INSERT INTO " . $this->table . " ($columns) VALUES ($values)";
            error_log("SQL Query: " . $sql);
            
            $stmt = $this->db->prepare($sql);

            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
                error_log("Binding $key with value: " . (is_array($value) ? print_r($value, true) : $value));
            }

            if ($stmt->execute()) {
                $lastId = $this->db->lastInsertId();
                error_log("Insert successful. Last insert ID: " . $lastId);
                return $lastId;
            } else {
                $error = $stmt->errorInfo();
                error_log("Execute failed: " . print_r($error, true));
                return false;
            }
        } catch (PDOException $e) {
            error_log("Database error in create: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            throw $e;
        }
    }

    public function update($id, $data) {
        $setClauses = [];
        foreach ($data as $key => $value) {
            $setClauses[] = "$key = :$key";
        }
        $setClause = implode(", ", $setClauses);

        $sql = "UPDATE " . $this->table . " SET " . $setClause . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->bindValue(":id", $id);

        return $stmt->execute();
    }

    public function delete($id) {
        $sql = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $id);
        return $stmt->execute();
    }

    public function count($conditions = []) {
        $sql = "SELECT COUNT(*) as count FROM " . $this->table;

        if (!empty($conditions)) {
            $sql .= " WHERE ";
            $whereClauses = [];
            foreach ($conditions as $key => $value) {
                $whereClauses[] = $value === null ? "$key IS NULL" : "$key = :$key";
            }
            $sql .= implode(" AND ", $whereClauses);
        }

        $stmt = $this->db->prepare($sql);

        if (!empty($conditions)) {
            foreach ($conditions as $key => $value) {
                if ($value !== null) {
                    $stmt->bindValue(":$key", $value);
                }
            }
        }

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
}
?>
