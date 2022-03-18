<?php
session_start();
require_once 'inc/functions.php';
require_once 'inc/header.php';
?>
<main>
    <div class="links">
        <a href="index.php">Home</a>
        <?php if (isset($_SESSION['userId'])) { ?>
            <a href="animelist.php">Anime List</a>
            <a href="mangalist.php">Manga List</a>
        <?php } ?>
        <a href="search.php">Search</a>
    </div>
    <?php if (isset($_SESSION['userId'])) { ?>
        <div class="logout"><a href="logout.php">Log out</a></div>
    <?php } ?>
    <?php
    echo "<form method='post'>";
        echo "<label for='type'>Type<span class='required'>*</span></label><br>";
        echo "<input type='radio' id='anime' name='type' value='ANIME' required><label for='anime'>Anime</label> ";
        echo "<input type='radio' id='manga' name='type' value='MANGA'><label for='manga'>Manga</label><br>";
        echo "<label for='search'>Search<span class='required'>*</span></label> ";
        ?>
        <input type='text' id='search' name='search' value='<?php echo isset($_POST['search']) ? htmlspecialchars($_POST['search']) : ''?>' required><br>
        <label for='page'>Page</label>
        <input type='number' id='page' name='page' value='<?php echo isset($_POST['page']) ? htmlspecialchars($_POST['page']) : 1 ?>'><br>
        <label for='perPage'>Per page</label>
        <input type='number' id='perPage' name='perPage' value='<?php echo isset($_POST['perPage']) ? htmlspecialchars($_POST['perPage']) : 10 ?>'><br>
        <?php
        echo "<button type='submit'>Search</button><br>";
        echo "</form>";
        $type = htmlspecialchars($_POST['type']);
        $search = htmlspecialchars($_POST['search']);
        $page = htmlspecialchars($_POST['page']);
        $perPage = htmlspecialchars($_POST['perPage']);
        if (!empty($type) && !empty($search)) {
            echo "<h2>Searched for " . $search . " in " . $type . "</h2>";
        }
        echo "<div>";
        try {
            if (!empty($type) && !empty($search) && !empty($page) && !empty($perPage)) {
                $data = search_media($type, $page, $perPage, $search);
            }
            if (!empty($data)) {
                echo "<table>";
                echo "<thead>";
                echo "<tr>";
                echo "<th>Cover</th>";
                echo "<th>Title</th>";
                echo "<th>Format</th>";
                echo "<th>Start Date</th>";
                echo "<th>End Date</th>";
                echo "<th>Average Score</th>";
                echo "</tr>";
                echo "<thead>";
                echo "<tbody>";
                for ($i = 0; $i < count($data['media']); $i++) {
                    $html = "<tr><td><a href='" . $data['media'][$i]['siteUrl'] . "' target='_blank'><img src='" . $data['media'][$i]['coverImage']['large'] . "' alt='cover'></a></td>";
                    $html .= "<td>" . $data['media'][$i]['title']['romaji'] . " (" . $data['media'][$i]['title']['english'] . ")" . "</td>";
                    $html .= "<td>" . $data['media'][$i]['format'] . "</td>";
                    $html .= "<td>" . $data['media'][$i]['startDate']['year'] . "-" . $data['media'][$i]['startDate']['month'] . "-" . $data['media'][$i]['startDate']['day'] . "</td>";
                    $html .= "<td>" . $data['media'][$i]['endDate']['year'] . "-" . $data['media'][$i]['endDate']['month'] . "-" . $data['media'][$i]['endDate']['day'] . "</td>";
                    $html .= "<td>" . $data['media'][$i]['averageScore'] . "</td></tr>";
                    echo $html;
                }
                echo "<tfoot>Page: " . $data['pageInfo']['currentPage'] . "</tfoot>";
                echo "</tbody>";
                echo "</table>";
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        echo "</div>";
        ?>
</main>
<?php require_once 'inc/footer.php'; ?>
