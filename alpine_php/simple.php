<?php
$reversed = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = trim($_POST['text'] ?? '');
    if ($input !== '') {
        $words = explode(' ', $input);
        $reversed = implode(' ', array_reverse($words));
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reverse Words</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background: linear-gradient(135deg, #6a11cb, #2575fc);
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0;
        color: #fff;
    }
    .container {
        background: rgba(255, 255, 255, 0.15);
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(10px);
        width: 350px;
    }
    h2 {
        text-align: center;
        margin-bottom: 20px;
    }
    input[type="text"] {
        width: 100%;
        padding: 10px;
        border-radius: 10px;
        border: none;
        margin-bottom: 15px;
    }
    button {
        width: 100%;
        padding: 10px;
        border: none;
        border-radius: 10px;
        background-color: #fff;
        color: #333;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
    }
    button:hover {
        background-color: #f0f0f0;
    }
    .result {
        margin-top: 20px;
        padding: 10px;
        background: rgba(0, 0, 0, 0.2);
        border-radius: 10px;
        word-wrap: break-word;
    }
</style>
</head>
<body>
<div class="container">
    <h2>Reverse Word Order</h2>
    <form method="POST">
        <input type="text" name="text" placeholder="Enter text..." required>
        <button type="submit">Submit</button>
    </form>
    <?php if ($reversed): ?>
        <div class="result">Reversed: <?php echo htmlspecialchars($reversed); ?></div>
    <?php endif; ?>
</div>
</body>
</html>

