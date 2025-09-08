<?php ?>
<!DOCTYPE html>
<html>
<head>
    <title>Invoice - Collectible</title>
    <link rel="stylesheet" href="css/collectibles.css">
    <style>
        .invoice {
            max-width: 600px;
            margin: 40px auto;
            padding: 30px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background: #f9f9f9;
            font-family: Arial, sans-serif;
        }
        .invoice h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        .invoice table {
            width: 100%;
            border-collapse: collapse;
        }
        .invoice table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        .invoice table td.label {
            font-weight: bold;
            width: 30%;
            color: #555;
        }
        .invoice img {
            max-width: 200px;
            display: block;
            margin: 10px 0;
        }
        .back-link {
            display: block;
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="invoice">
    <h2>Invoice</h2>
    <table>
        <tr>
            <td class="label">Product:</td>
            <td><?= htmlspecialchars($collectible['title']) ?></td>
        </tr>
        <tr>
            <td class="label">Description:</td>
            <td><?= htmlspecialchars($collectible['description']) ?></td>
        </tr>
        <tr>
            <td class="label">Price:</td>
            <td><?= htmlspecialchars($collectible['price']) ?>TK</td>
        </tr>
        <tr>
            <td class="label">Seller:</td>
            <td><?= htmlspecialchars($collectible['username']) ?></td>
        </tr>
        <tr>
            <td class="label">Buyer:</td>
            <td><?= htmlspecialchars($collectible['buyer_name'] ?? $_SESSION['username'] ?? 'Guest') ?></td>
        </tr>
        <tr>
            <td class="label">Contact:</td>
            <td><?= htmlspecialchars($collectible['buyer_contact']) ?></td>
        </tr>
        <tr>
            <td class="label">Location:</td>
            <td><?= htmlspecialchars($collectible['buyer_location']) ?></td>
        </tr>
        <?php if ($collectible['image_url']): ?>
        <tr>
            <td class="label">Image:</td>
            <td><img src="<?= htmlspecialchars($collectible['image_url']) ?>" alt="Product Image"></td>
        </tr>
        <?php endif; ?>
    </table>
    <a class="back-link" href="index.php?page=collectibles">Back to Marketplace</a>
</div>
</body>
</html>
