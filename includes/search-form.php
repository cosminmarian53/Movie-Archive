<form action="search-results.php" method="GET">
    <input type="text" name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search'], ENT_QUOTES, 'UTF-8') : ''; ?>" placeholder="Search...">
    <button class="btn btn-std" type="submit">Search</button>
</form>
