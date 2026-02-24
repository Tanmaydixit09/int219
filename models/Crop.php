<?php
class Crop {
    // Database connection and table name
    private $conn;
    private $table_name = "crops";

    // Object properties
    public $id;
    public $user_id;
    public $crop_type;
    public $field_size;
    public $planting_date;
    public $harvest_date;
    public $location;
    public $status;
    public $created_at;
    public $updated_at;

    // Constructor with $db as database connection
    public function __construct($db) {
        $this->conn = $db;
    }

    // Create crop
    public function create() {
        // Query to insert record
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    user_id = :user_id,
                    crop_type = :crop_type,
                    field_size = :field_size,
                    planting_date = :planting_date,
                    harvest_date = :harvest_date,
                    location = :location,
                    status = :status";

        // Prepare query
        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->crop_type = htmlspecialchars(strip_tags($this->crop_type));
        $this->field_size = htmlspecialchars(strip_tags($this->field_size));
        $this->planting_date = htmlspecialchars(strip_tags($this->planting_date));
        $this->harvest_date = htmlspecialchars(strip_tags($this->harvest_date));
        $this->location = htmlspecialchars(strip_tags($this->location));
        $this->status = 'planted';

        // Bind values
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":crop_type", $this->crop_type);
        $stmt->bindParam(":field_size", $this->field_size);
        $stmt->bindParam(":planting_date", $this->planting_date);
        $stmt->bindParam(":harvest_date", $this->harvest_date);
        $stmt->bindParam(":location", $this->location);
        $stmt->bindParam(":status", $this->status);

        // Execute query
        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    // Read crops by user ID
    public function readByUser($user_id) {
        $query = "SELECT * FROM " . $this->table_name . "
                WHERE user_id = :user_id
                ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();

        return $stmt;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . "
                SET
                    crop_type = :crop_type,
                    field_size = :field_size,
                    planting_date = :planting_date,
                    harvest_date = :harvest_date,
                    location = :location,
                    status = :status
                WHERE
                    id = :id AND user_id = :user_id";

        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $this->crop_type = htmlspecialchars(strip_tags($this->crop_type));
        $this->field_size = htmlspecialchars(strip_tags($this->field_size));
        $this->planting_date = htmlspecialchars(strip_tags($this->planting_date));
        $this->harvest_date = htmlspecialchars(strip_tags($this->harvest_date));
        $this->location = htmlspecialchars(strip_tags($this->location));
        $this->status = htmlspecialchars(strip_tags($this->status));

        // Bind values
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":crop_type", $this->crop_type);
        $stmt->bindParam(":field_size", $this->field_size);
        $stmt->bindParam(":planting_date", $this->planting_date);
        $stmt->bindParam(":harvest_date", $this->harvest_date);
        $stmt->bindParam(":location", $this->location);
        $stmt->bindParam(":status", $this->status);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . "
                WHERE id = :id AND user_id = :user_id";

        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));

        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":user_id", $this->user_id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?> 