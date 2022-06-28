<?php
session_start();
require_once 'inc/functions.php';
require_once 'inc/header.php'; ?>
<main>
    <?php
    if (!isset($_SESSION['userId'])) {
        header('Location: index.php?logged_in=0');
    }
    ?>
    <div class="links">
        <a href="index.php">Home</a>
        <a href="animelist.php">Anime List</a>
        <a href="mangalist.php">Manga List</a>
        <a href="search.php">Search</a>
    </div>
    <div class="logout"><a href="logout.php">Log out</a></div>
    <h2><?php echo $_SESSION['username'] . "'s Anime List"; ?></h2>
    <form method="post">
        <label for="status">Status<span class="required">*</span></label>
        <select id="status" name="status" required>
            <option value="CURRENT">Currently watching</option>
            <option value="COMPLETED">Completed</option>
            <option value="PLANNING">Plan to watch</option>
            <option value="PAUSED">Paused</option>
            <option value="DROPPED">Dropped</option>
            <optino value="REPEATING">Repeating</option>
        </select><br>
        <label for="page">Page</label>
        <input type="number" id="page" name="page" value="<?php echo isset($_POST['page']) ? htmlspecialchars($_POST['page']) : 1 ?>">
        <label for="perPage">Per page</label>
        <input type="number" id="perPage" name="perPage" value="<?php echo isset($_POST['perPage']) ? htmlspecialchars($_POST['perPage']) : 10 ?>"><br>
        <button type="submit">View your list</button>
    </form>
    <?php
    $status = htmlspecialchars($_POST['status']);
    $page = htmlspecialchars($_POST['page']);
    $perPage = htmlspecialchars($_POST['perPage']);

    try {
        if (!empty($status) && !empty($page) && !empty($perPage)) {
            $data = get_userAnimeList($_SESSION['userId'], $status, $page, $perPage);
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }

    if (!empty($data)) {
        while (count($data['mediaList']) % 10 != 0) {
            $data['mediaList'][] = '';
        }
    
        if ($status === 'CURRENT') {
            echo '<h3>Currently Watching</h3>';
            echo '<table>';
            echo '<tbody>';
            for ($i = 0; $i < count($data['mediaList']); $i++) {
                $html = '<tr>';
                if ($i >= 10) {
                    for ($j = 0; $j < 10; $j++) {
                        $html .= "<img src='" . $data['mediaList'][$j]['media']['coverImage']['medium'] . "' alt='cover'>";
                    }
                }
                else {
                    for ($j = 0; $j < $i; $j++) {
                        $html .= "<img src='" . $data['mediaList'][$j]['media']['coverImage']['medium'] . "' alt='cover'>";
                    }
                }
                $html .= '</tr>';
                echo $html;
            }
            echo "</tbody>";
            echo "<tfoot>Page: " . $data['pageInfo']['currentPage'] . "</tfoot>";
            echo "</table>";
        } else if ($status === 'COMPLETED') {
            echo '<h3>Completed</h3>';
            echo '<table>';
            echo '<tbody>';
            echo '<tr>';
            for ($i = 0; $i < count($data['mediaList']); $i++) {
                if ($data['mediaList'][$i] != '') {
                    echo "<td><img src='" . $data['mediaList'][$i]['media']['coverImage']['medium'] . "' alt='cover'></td>";
                }
                if ($i % 10 == 0) {
                    echo "</tr><tr>";
                }
            }
            echo "</tr>";
            echo "</tbody>";
            echo "<tfoot>Page: " . $data['pageInfo']['currentPage'] . "</tfoot>";
            echo "</table>";
        } else if ($status === 'PLANNING') {
            echo '<h3>Plan to Watch</h3>';
            echo '<table>';
            echo '<tbody>';
            echo '<tr>';
            for ($i = 0; $i < count($data['mediaList']); $i++) {
                if ($data['mediaList'][$i] != '') {
                    echo "<td><img src='" . $data['mediaList'][$i]['media']['coverImage']['medium'] . "' alt='cover'></td>";
                }
                if ($i % 9 == 0) {
                    echo "</tr><tr>";
                }
            }
            echo "</tr>";
            echo "</tbody>";
            echo "<tfoot>Page: " . $data['pageInfo']['currentPage'] . "</tfoot>";
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
            echo '<th>Format</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            for ($i = 0; $i < count($data['mediaList']); $i++) {
                $html = "<tr><td><a href='" . $data['mediaList'][$i]['media']['siteUrl'] . "' target='_blank'><img src='" . $data['mediaList'][$i]['media']['coverImage']['large'] . "' alt='cover'></a></td>";
                $html .= "<td>" . $data['mediaList'][$i]['media']['title']['romaji'] . " (" . $data['mediaList'][$i]['media']['title']['english'] . ")" . "</td>";
                $html .= "<td>" . $data['mediaList'][$i]['startedAt']['year'] . "-" . $data['mediaList'][$i]['startedAt']['month'] . "-" . $data['mediaList'][$i]['startedAt']['day'] . "</td>";
                $html .= "<td>" . $data['mediaList'][$i]['progress'] . "</td>";
                $html .= "<td>" . $data['mediaList'][$i]['score'] . "</td>";
                $html .= "<td>" . $data['mediaList'][$i]['media']['format'] . "</td></tr>";
                echo $html;
            }
            echo "</tbody>";
            echo "<tfoot>Page: " . $data['pageInfo']['currentPage'] . "</tfoot>";
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
            echo '<th>Format</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            for ($i = 0; $i < count($data['mediaList']); $i++) {
                $html = "<tr><td><a href='" . $data['mediaList'][$i]['media']['siteUrl'] . "' target='_blank'><img src='" . $data['mediaList'][$i]['media']['coverImage']['large'] . "' alt='cover'></a></td>";
                $html .= "<td>" . $data['mediaList'][$i]['media']['title']['romaji'] . " (" . $data['mediaList'][$i]['media']['title']['english'] . ")" . "</td>";
                $html .= "<td>" . $data['mediaList'][$i]['startedAt']['year'] . "-" . $data['mediaList'][$i]['startedAt']['month'] . "-" . $data['mediaList'][$i]['startedAt']['day'] . "</td>";
                $html .= "<td>" . $data['mediaList'][$i]['progress'] . "</td>";
                $html .= "<td>" . $data['mediaList'][$i]['score'] . "</td>";
                $html .= "<td>" . $data['mediaList'][$i]['media']['format'] . "</td></tr>";
                echo $html;
            }
            echo "</tbody>";
            echo "<tfoot>Page: " . $data['pageInfo']['currentPage'] . "</tfoot>";
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
            echo '<th>Format</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            for ($i = 0; $i < count($data['mediaList']); $i++) {
                $html = "<tr><td><a href='" . $data['mediaList'][$i]['media']['siteUrl'] . "' target='_blank'><img src='" . $data['mediaList'][$i]['media']['coverImage']['large'] . "' alt='cover'></a></td>";
                $html .= "<td>" . $data['mediaList'][$i]['media']['title']['romaji'] . " (" . $data['mediaList'][$i]['media']['title']['english'] . ")" . "</td>";
                $html .= "<td>" . $data['mediaList'][$i]['startedAt']['year'] . "-" . $data['mediaList'][$i]['startedAt']['month'] . "-" . $data['mediaList'][$i]['startedAt']['day'] . "</td>";
                $html .= "<td>" . $data['mediaList'][$i]['progress'] . "</td>";
                $html .= "<td>" . $data['mediaList'][$i]['score'] . "</td>";
                $html .= "<td>" . $data['mediaList'][$i]['repeat'] . "</td>";
                $html .= "<td>" . $data['mediaList'][$i]['media']['format'] . "</td></tr>";
                echo $html;
            }
            echo "</tbody>";
            echo "<tfoot>Page: " . $data['pageInfo']['currentPage'] . "</tfoot>";
            echo "</table>";
        }
    }
    ?>
</main>
<?php require_once 'inc/footer.php'; ?>
