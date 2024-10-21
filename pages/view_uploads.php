<?php
include '../config/database.php';

$sql = "SELECT u.title, u.type, u.course, u.file_path, s.name FROM uploads u JOIN students s ON u.student_id = s.id";
$stmt = $pdo->query($sql);
$uploads = $stmt->fetchAll();
?>

<h2>Uploaded Files</h2>
<table>
    <tr>
        <th>Title</th>
        <th>Type</th>
        <th>Course</th>
        <th>Uploaded by</th>
        <th>Download</th>
    </tr>
    <?php foreach ($uploads as $upload): ?>
        <tr>
            <td><?= htmlspecialchars($upload['title']) ?></td>
            <td><?= htmlspecialchars($upload['type']) ?></td>
            <td><?= htmlspecialchars($upload['course']) ?></td>
            <td><?= htmlspecialchars($upload['name']) ?></td>
            <td><a href="<?= htmlspecialchars($upload['file_path']) ?>" download>Download</a></td>
        </tr>
    <?php endforeach; ?>
</table>
