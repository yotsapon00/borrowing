<?php
session_start();
include "../connect.php";

// ตรวจสอบว่าผู้ใช้ล็อกอินแล้วหรือไม่
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// ดึงข้อมูลประเภทอุปกรณ์ทั้งหมด
$typeQuery = "SELECT * FROM item_type";
$typeResult = $conn->query($typeQuery);
$typeQuery1 = "SELECT * FROM item_type";
$typeResult1 = $conn->query($typeQuery);
// ดึงข้อมูลอุปกรณ์ทั้งหมด
$itemQuery = "SELECT * FROM items_1";
$itemResult = $conn->query($itemQuery);

// ดึงข้อมูลสถานะทั้งหมดจาก statuslist
$statusQuery = "SELECT st_id, st_name FROM statuslist";
$statusResult = $conn->query($statusQuery);

// สร้างอาเรย์เพื่อเก็บข้อมูลสถานะ
$statusOptions = [];
while ($status = $statusResult->fetch_assoc()) {
    $statusOptions[$status['st_id']] = $status['st_name'];
}
// สร้างอาเรย์เพื่อเก็บข้อมูลประเภทอุปกรณ์
$typeOptions1 = [];
while ($type = $typeResult1->fetch_assoc()) {
    $typeOptions1[$type['type_id']] = $type['type_name'];
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>จัดการ อุปกรณ์ IT</title>

</head>
<style>
    h2 {
        padding-top: 20px;
        padding-bottom: 20px;
        text-align: center;
        width: 100%;
        background-color: turquoise;
        font-weight: bold;
    }

    .btn-back-home {
        display: inline-block;
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        font-size: 1em;
        min-width: 400px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    table thead tr {
        background-color: #009879;
        color: #ffffff;
        text-align: left;
        font-weight: bold;
    }

    table th,
    table td {
        padding: 12px 15px;
        border: 1px solid #ddd;
    }

    table tbody tr {
        border-bottom: 1px solid #dddddd;
    }

    table tbody tr:nth-of-type(even) {
        background-color: #f3f3f3;
    }

    table tbody tr:last-of-type {
        border-bottom: 2px solid #009879;
    }

    table tbody tr:hover {
        background-color: #f1f1f1;
        cursor: pointer;
    }

    .form-control {
        padding: 5px;
        border: 1px solid #ddd;
        border-radius: 4px;
        width: 100%;
    }

    .btn {
        padding: 5px 10px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .btn-success {
        background-color: #28a745;
        color: white;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
    }

    .btn:hover {
        opacity: 0.8;
    }
</style>

<body>
    <h2 id="nav">ประเภทอุปกรณ์ IT</h2>
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <div class="row" id="tools">
                    <a href="home_it.php" class="btn-back-home">Back to Home</a>
                </div>
                <div class="col-md-12">
                    <!-- แก้ไขประเภทอุปกรณ์ -->
                    <section class="section">
                        <h2>จัดการประเภทอุปกรณ์ IT</h2>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Type ID</th>
                                    <th>Type Name</th>
                                    <th>Type Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $typeResult->fetch_assoc()) { ?>
                                    <tr>
                                        <form method="POST" action="update_type.php">
                                            <td><?php echo htmlspecialchars($row['type_id']); ?></td>
                                            <td><input type="text" name="type_name" value="<?php echo htmlspecialchars($row['type_name']); ?>" class="form-control"></td>
                                            <td><input type="text" name="type_description" value="<?php echo htmlspecialchars($row['type_description']); ?>" class="form-control"></td>
                                            <td>
                                                <input type="hidden" name="type_id" value="<?php echo htmlspecialchars($row['type_id']); ?>">
                                                <button type="submit" class="btn btn-success">Update</button>
                                                <a href="delete_type.php?type_id=<?php echo urlencode($row['type_id']); ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                            </td>
                                        </form>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </section>

                    <section class="section">
                        <h2>จัดการ อุปกรณ์ IT</h2>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Equipment ID</th>
                                    <th>Equipment Name</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $itemResult->fetch_assoc()) { ?>
                                    <tr>
                                        <form method="POST" action="update_item.php">
                                            <td><?php echo htmlspecialchars($row['ag_id']); ?></td>
                                            <td><input type="text" name="ag_name" value="<?php echo htmlspecialchars($row['ag_name']); ?>" class="form-control"></td>
                                            <td>
                                                <select name="ag_type" class="form-control">
                                                    <?php foreach ($typeOptions1 as $id => $name) { ?>
                                                        <option value="<?php echo htmlspecialchars($id); ?>"
                                                            <?php echo ($id == $row['ag_type']) ? 'selected' : ''; ?>>
                                                            <?php echo htmlspecialchars($name); ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="ag_status" class="form-control">
                                                    <?php foreach ($statusOptions as $id => $name) { ?>
                                                        <option value="<?php echo htmlspecialchars($id); ?>"
                                                            <?php echo ($id == $row['ag_status']) ? 'selected' : ''; ?>>
                                                            <?php echo htmlspecialchars($name); ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="hidden" name="ag_id" value="<?php echo htmlspecialchars($row['ag_id']); ?>">
                                                <button type="submit" class="btn btn-success">Update</button>
                                                <a href="delete_item.php?ag_id=<?php echo urlencode($row['ag_id']); ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                            </td>
                                        </form>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </section>
                </div>
            </div>
</body>

</html>

<?php
$conn->close();
?>