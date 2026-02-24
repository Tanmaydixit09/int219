<?php
class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $name;
    public $email;
    public $password;
    public $role;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        try {
            $query = "INSERT INTO " . $this->table_name . "
                    SET
                        name = :name,
                        email = :email,
                        password = :password,
                        role = :role,
                        created_at = :created_at";

            $stmt = $this->conn->prepare($query);

            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->password = password_hash($this->password, PASSWORD_BCRYPT);
            $this->role = 'farmer'; // Default role
            $this->created_at = date('Y-m-d H:i:s');

            $stmt->bindParam(":name", $this->name);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":password", $this->password);
            $stmt->bindParam(":role", $this->role);
            $stmt->bindParam(":created_at", $this->created_at);

            if($stmt->execute()) {
                $this->id = $this->conn->lastInsertId();
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("User creation error: " . $e->getMessage());
            return false;
        }
    }

    public function login() {
        try {
            $query = "SELECT id, name, password, role FROM " . $this->table_name . "
                    WHERE email = :email LIMIT 1";

            $stmt = $this->conn->prepare($query);
            $this->email = htmlspecialchars(strip_tags($this->email));
            $stmt->bindParam(":email", $this->email);
            $stmt->execute();

            if($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if(password_verify($this->password, $row['password'])) {
                    $this->id = $row['id'];
                    $this->name = $row['name'];
                    $this->role = $row['role'];
                    return true;
                }
            }
            return false;
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            return false;
        }
    }

    public function emailExists() {
        try {
            $query = "SELECT id FROM " . $this->table_name . "
                    WHERE email = :email LIMIT 1";

            $stmt = $this->conn->prepare($query);
            $this->email = htmlspecialchars(strip_tags($this->email));
            $stmt->bindParam(":email", $this->email);
            $stmt->execute();

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Email check error: " . $e->getMessage());
            return false;
        }
    }

    public function readOne() {
        try {
            $query = "SELECT id, name, email, role FROM " . $this->table_name . "
                    WHERE id = :id LIMIT 1";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $this->id);
            $stmt->execute();

            if($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->name = $row['name'];
                $this->email = $row['email'];
                $this->role = $row['role'];
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("User read error: " . $e->getMessage());
            return false;
        }
    }
}
?> 