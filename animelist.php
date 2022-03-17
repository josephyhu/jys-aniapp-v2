<?php
session_start();
require_once 'inc/functions.php';
require_once 'inc/header.php'; ?>
<main>
    <div id="logout"><a href="logout.php">Log out</a></div>
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
        if (!empty($status) && !empty($page)) {
            $data = get_userAnimeList($_SESSION['userId'], $status, $page);
        }
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
            echo '<th>Score</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            for ($i = 0; $i < count($data['mediaList']); $i++) {
                $html = "<tr><td><a href='" . $data['mediaList'][$i]['media']['siteUrl'] . "' target='_blank'><img src='" . $data['mediaList'][$i]['media']['coverImage']['large'] . "' alt='cover'></a></td>";
                $html .= "<td>" . $data['mediaList'][$i]['media']['title']['romaji'] . " (" . $data['mediaList'][$i]['media']['title']['english'] . ")" . "</td>";
                $html .= "<td>" . $data['mediaList'][$i]['startedAt']['year'] . "-" . $data['mediaList'][$i]['startedAt']['month'] . "-" . $data['mediaList'][$i]['startedAt']['day'] . "</td>";
                $html .= "<td>" . $data['mediaList'][$i]['progress'] . "</td>";
                $html .= "<td>" . $data['mediaList'][$i]['score'] . "</td></tr>";
                echo $html;
            }
            echo "</tbody>";
            echo "<tfoot>Page: " . $data['pageInfo']['currentPage'] . " of " . $data['pageInfo']['lastPage'] . "</tfoot>";
            echo "</table>";
        } else if ($status === 'COMPLETED') {
            echo '<h3>Completed</h3>';
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Cover</th>';
            echo '<th>Name</th>';
            echo '<th>Started Date</th>';
            echo '<th>Completed Date</th>';
            echo '<th>Score</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            for ($i = 0; $i < count($data['mediaList']); $i++) {
                $html = "<tr><td><a href='" . $data['mediaList'][$i]['media']['siteUrl'] . "' target='_blank'><img src='" . $data['mediaList'][$i]['media']['coverImage']['large'] . "' alt='cover'></a></td>";
                $html .= "<td>" . $data['mediaList'][$i]['media']['title']['romaji'] . " (" . $data['mediaList'][$i]['media']['title']['english'] . ")" . "</td>";
                $html .= "<td>" . $data['mediaList'][$i]['startedAt']['year'] . "-" . $data['mediaList'][$i]['startedAt']['month'] . "-" . $data['mediaList'][$i]['startedAt']['day'] . "</td>";
                $html .= "<td>" . $data['mediaList'][$i]['completedAt']['year'] . "-" . $data['mediaList'][$i]['completedAt']['month'] . "-" . $data['mediaList'][$i]['completedAt']['day'] . "</td>";
                $html .= "<td>" . $data['mediaList'][$i]['score'] . "</td></tr>";
                echo $html;
            }
            echo "</tbody>";
            echo "<tfoot>Page: " . $data['pageInfo']['currentPage'] . " of " . $data['pageInfo']['lastPage'] . "</tfoot>";
            echo "</table>";
        } else if ($status === 'PLANNING') {
            echo '<h3>Planning to Watch</h3>';
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Cover</th>';
            echo '<th>Name</th>';
            echo '<th>Format</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            for ($i = 0; $i < count($data['mediaList']); $i++) {
                $html = "<tr><td><a href='" . $data['mediaList'][$i]['media']['siteUrl'] . "' target='_blank'><img src='" . $data['mediaList'][$i]['media']['coverImage']['large'] . "' alt='cover'></a></td>";
                $html .= "<td>" . $data['mediaList'][$i]['media']['title']['romaji'] . " (" . $data['mediaList'][$i]['media']['title']['english'] . ")" . "</td>";
                $html .= "<td>" . $data['mediaList'][$i]['media']['format'] . "</td></tr>";
                echo $html;
            }
            echo "</tbody>";
            echo "<tfoot>Page: " . $data['pageInfo']['currentPage'] . " of " . $data['pageInfo']['lastPage'] . "</tfoot>";
            echo "</table>";
        } else if ($status === 'PAUSED') {
            echo '<h3>Paused</h3>';
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Cover</th>';
            echo '<th>Name</th>';
            echo '<th>Started Date</th>';
            echo '<th>Progress</th>';
            echo '<th>Score</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            for ($i = 0; $i < count($data['mediaList']); $i++) {
                $html = "<tr><td><a href='" . $data['mediaList'][$i]['media']['siteUrl'] . "' target='_blank'><img src='" . $data['mediaList'][$i]['media']['coverImage']['large'] . "' alt='cover'></a></td>";
                $html .= "<td>" . $data['mediaList'][$i]['media']['title']['romaji'] . " (" . $data['mediaList'][$i]['media']['title']['english'] . ")" . "</td>";
                $html .= "<td>" . $data['mediaList'][$i]['startedAt']['year'] . "-" . $data['mediaList'][$i]['startedAt']['month'] . "-" . $data['mediaList'][$i]['startedAt']['day'] . "</td>";
                $html .= "<td>" . $data['mediaList'][$i]['progress'] . "</td>";
                $html .= "<td>" . $data['mediaList'][$i]['score'] . "</td></tr>";
                echo $html;
            }
            echo "</tbody>";
            echo "<tfoot>Page: " . $data['pageInfo']['currentPage'] . " of " . $data['pageInfo']['lastPage'] . "</tfoot>";
            echo "</table>";
        } else if ($status === 'DROPPED') {
            echo '<h3>Dropped</h3>';
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Cover</th>';
            echo '<th>Name</th>';
            echo '<th>Started Date</th>';
            echo '<th>Progress</th>';
            echo '<th>Score</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            for ($i = 0; $i < count($data['mediaList']); $i++) {
                $html = "<tr><td><a href='" . $data['mediaList'][$i]['media']['siteUrl'] . "' target='_blank'><img src='" . $data['mediaList'][$i]['media']['coverImage']['large'] . "' alt='cover'></a></td>";
                $html .= "<td>" . $data['mediaList'][$i]['media']['title']['romaji'] . " (" . $data['mediaList'][$i]['media']['title']['english'] . ")" . "</td>";
                $html .= "<td>" . $data['mediaList'][$i]['startedAt']['year'] . "-" . $data['mediaList'][$i]['startedAt']['month'] . "-" . $data['mediaList'][$i]['startedAt']['day'] . "</td>";
                $html .= "<td>" . $data['mediaList'][$i]['progress'] . "</td>";
                $html .= "<td>" . $data['mediaList'][$i]['score'] . "</td></tr>";
                echo $html;
            }
            echo "</tbody>";
            echo "<tfoot>Page: " . $data['pageInfo']['currentPage'] . " of " . $data['pageInfo']['lastPage'] . "</tfoot>";
            echo "</table>";
        } else if ($status === 'REPEATING') {
            echo '<h3>Repeating</h3>';
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Cover</th>';
            echo '<th>Name</th>';
            echo '<th>Started Date</th>';
            echo '<th>Progress</th>';
            echo '<th>Score</th>';
            echo '<th>Repeats</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            for ($i = 0; $i < count($data['mediaList']); $i++) {
                $html = "<tr><td><a href='" . $data['mediaList'][$i]['media']['siteUrl'] . "' target='_blank'><img src='" . $data['mediaList'][$i]['media']['coverImage']['large'] . "' alt='cover'></a></td>";
                $html .= "<td>" . $data['mediaList'][$i]['media']['title']['romaji'] . " (" . $data['mediaList'][$i]['media']['title']['english'] . ")" . "</td>";
                $html .= "<td>" . $data['mediaList'][$i]['startedAt']['year'] . "-" . $data['mediaList'][$i]['startedAt']['month'] . "-" . $data['mediaList'][$i]['startedAt']['day'] . "</td>";
                $html .= "<td>" . $data['mediaList'][$i]['progress'] . "</td>";
                $html .= "<td>" . $data['mediaList'][$i]['score'] . "</td>";
                $html .= "<td>" . $data['mediaList'][$i]['repeat'] . "</td></tr>";
                echo $html;
            }
            echo "</tbody>";
            echo "<tfoot>Page: " . $data['pageInfo']['currentPage'] . " of " . $data['pageInfo']['lastPage'] . "</tfoot>";
            echo "</table>";
        }
    }
    ?>
</main>
<?php require_once 'inc/footer.php'; ?>
