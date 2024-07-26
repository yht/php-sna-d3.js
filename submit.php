<?php
require_once('db/conn.php');

// Sanitize and validate input
$entityName = isset($_POST['entityName']) ? trim($_POST['entityName']) : '';
$entityType = isset($_POST['entityType']) ? trim($_POST['entityType']) : '';
$company = isset($_POST['company']) ? trim($_POST['company']) : '';
$owner = isset($_POST['owner']) ? trim($_POST['owner']) : '';

// Check if the entity is provided
if ($entityName && $entityType) {
    $stmt = $conn->prepare("INSERT INTO entities (name, type) VALUES (?, ?)");
    $stmt->bind_param("ss", $entityName, $entityType);
    if ($stmt->execute()) {
        echo "Entity added successfully.<br>";
    } else {
        echo "Error adding entity: " . $conn->error . "<br>";
    }
    $stmt->close();
}

// Check if both company and owner are provided for relationships
if ($company && $owner) {
    // Fetch entity IDs
    $stmt = $conn->prepare("SELECT id FROM entities WHERE name = ? AND type = 'PT'");
    $stmt->bind_param("s", $company);
    $stmt->execute();
    $result = $stmt->get_result();
    $companyId = $result->fetch_assoc()['id'];
    $stmt->close();

    $stmt = $conn->prepare("SELECT id FROM entities WHERE name = ? AND type = 'OP'");
    $stmt->bind_param("s", $owner);
    $stmt->execute();
    $result = $stmt->get_result();
    $ownerId = $result->fetch_assoc()['id'];
    $stmt->close();

    if ($companyId && $ownerId) {
        $stmt = $conn->prepare("INSERT INTO entity_relationships (entity_id, related_entity_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $companyId, $ownerId);
        if ($stmt->execute()) {
            echo "Relationship added successfully.<br>";
        } else {
            echo "Error adding relationship: " . $conn->error . "<br>";
        }
        $stmt->close();
    } else {
        echo "Company or owner not found.<br>";
    }
}

$conn->close();
?>
