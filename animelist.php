<?php
session_start();
require_once 'inc/functions.php';
require_once 'inc/header.php'; ?>
<main>
    <h2><?php echo $_SESSION['username'] . "'s Anime List"; ?></h2>
    <form method="post">
        <label for="status">Status<span class="required">*</span></label>
        <select id="status" name="status" required>
            <option value="CURRENT">Currently watching</option>
            <option value="COMPLETED">Completed</option>
            <option value="PLANNING">Planning to watch</option>
            <option value="PAUSED">Paused</option>
            <option value="DROPPED">Dropped</option>
            <optino value="REPEATING">Repeating</option>
        </select><br>
        <label for="page">Page</label>
        <input type="number" id="page" name="page" value="<?php echo isset($_POST['page']) ? htmlspecialchars($_POST['page']) : 1 ?>">
        <button type="submit">View your list</button>
    </form>
    <?php
    $status = htmlspecialchars($_POST['status']);
    $page = htmlspecialchars($_POST['page']);

    try {
        $data = get_userAnimeList($_SESSION['userId'], $status, $page);
    } catch (Exception $e) {
        echo $e->getMessage();
    }

    if (!empty($data)) {
        if ($status === 'CURRENT') {
            echo '<h3>Currently Watching</h3>';
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Cover</th>';
            echo '<th>Name</th>';
            echo '<th>Started Date</th>';
            echo '<th>Progress</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            for ($i = 0; $i < count($data['mediaList']); $i++) {
                $html = "<tr><td><a href='" . $data['mediaList'][$i]['media']['siteUrl'] . "' target='_blank'><img src='" . $data['mediaList'][$i]['media']['coverImage']['large'] . "' alt='cover'></a></td>";
                $html .= "<td>" . $data['mediaList'][$i]['media']['title']['romaji'] . " (" . $data['mediaList'][$i]['media']['title']['english'] . ")" . "</td>";
                $html .= "<td>" . $data['mediaList'][$i]['startedAt']['year'] . "-" . $data['mediaList'][$i]['startedAt']['month'] . "-" . $data['mediaList'][$i]['startedAt']['day'] . "</td>";
                $html .= "<td>" . $data['mediaList'][$i]['progress'] . "</td></tr>";
                echo $html;
            }
            echo "</tbody>";
            echo "<tfoot>Page: " . $data['currentPage'] . " of " . $data['lastPage'] . "</tfoot>";
            echo "</table>";
        }
    }
    ?>
</main>
<?php require_once 'inc/footer.php'; ?>