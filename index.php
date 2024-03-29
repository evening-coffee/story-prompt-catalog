<?php 

include_once 'connect.php';

function redirect_to($otherplace) {
    header("Location: {$otherplace}");
    exit;
  }

if (isset($_SESSION['user'])){
    //logged in
} else {
    //logged out
    redirect_to('login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Prompt Catalog</title>
        <link rel="stylesheet" href="stylesheet.css">
        <link rel="icon" sizes="any" type="image/svg+xml" href="pencil-logo.svg">
    </head>
    <body> 

        <?php

        $use_result = null;
        $search = null;

        if (isset($_GET['search'])){
            $search = trim( (string) $_GET['search']);
            if (isset($search[0])){
                $use_result = search_catalog($search);
            };
        };

        function search_catalog($search){
            global $db_connection;
            $query = "SELECT * FROM catalog_" . $_SESSION['user'] . " WHERE prompt LIKE '%" . $search . "%' OR tag LIKE '%" . $search . "%' OR id LIKE '%" . $search . "%' ORDER BY id DESC";
            $result = mysqli_query($db_connection, $query);
            if ($result && $result-> num_rows > 0){
                $results = $result;
            } else {
                $results = null;
            }
            return $results;
            mysqli_free_result($result);
        };

        include_once 'header.php';

        ?>

        <main>
            <h2>Write Prompts</h2>
            <div id="entry-form">
                <form action="enter-prompt.php" method="post" id="prompt-form">
                    <textarea id="prompt" name="prompt" rows="3" maxlength="75" placeholder="Write a short summary of your idea..."></textarea>

                    <div id="tag-input">
                        <div id="tag-icon">#</div>
                        <input list="tags" name="tag" id="tag-box" title="Tag your idea" placeholder="Tag your idea..." maxlength="10">
                        <datalist id="tags">
                            <?php
                                $datalist_query = "SELECT DISTINCT `tag` from `catalog_" . $_SESSION['user'] . "`";
                                $datalist_results = mysqli_query($db_connection, $datalist_query);
                                while ($row = mysqli_fetch_assoc($datalist_results)){
                                    echo '<option value="' . $row['tag'] . '">';
                                };
                            ?>
                        </datalist>

                    </div>
                    <div id="submit-box">
                        <input type="submit" id="submit" name="submit" value="Enter">
                    </div>
                </form>
            </div>
            
            <form class="search-form" action="" method="get">
                <div class="search-input">
                        <div class="search-icon"><span id="question-search-icon">?</span></div>
                        <input name="search" class="search-box" placeholder="Search..." value="<?php echo $search;?>">
                        <div class="search-submit-box">
                        <input type="submit" id="search-submit" name="search-submit" value="Search">
                        </div>
                    </div>
            </form>
            <?php


            

                $num_query = "SELECT COUNT(id) from `catalog_" . $_SESSION['user'] . "`";
                $num_result = mysqli_query($db_connection, $num_query);
            

            if ($num_result){
                $count_array = mysqli_fetch_array($num_result);
                $count = $count_array[0];

                    echo "<p class='count'>There are currently " . $count . " prompts inside " . $_SESSION['user'] . "'s catalog.</p>";
            };

            if (!$num_result){
                echo 'There are currently no prompts inside the catalog.';
                };


            mysqli_free_result($num_result);

            ?>


            <table id="catalog-listings">
               
            <?php
            
            if($use_result) {
                echo ' <table id="catalog-listings">
                <tr class="header-row">
                    <th class="header-id">ID</th>
                    <th class="header-date">DATE</th>
                    <th class="header-tag">#</th>
                    <th>PROMPT</th>
                </tr>';

                while($row = mysqli_fetch_assoc($use_result)){
                    echo '<tr class="table-row">';
                    echo '<td class="table-id">' . $row['id'] . '</td>';
                    echo '<td class="table-date">' . $row['date'] . '</td>';
                    echo '<td class="table-tag"><div class="tag-fluff">' . $row['tag'] . '</div></td>';
                    echo '<td>' . $row['prompt'] . '</td>';
                    echo '</tr>';
                };
                
            } else if (!$use_result && $search != null) {
                echo '<p id="search-error">No results found.</p>';
            };
                
            /* else if(!$search) {

            $prompt_query = "SELECT * FROM catalog ORDER BY id DESC";
            $prompt_result = mysqli_query($db_connection, $prompt_query);
            
            while($row = mysqli_fetch_assoc($prompt_result)){
                echo '<tr>';
                echo '<td class="table-id">' . $row['id'] . '</td>';
                echo '<td class="table-date">' . $row['date'] . '</td>';
                echo '<td class="table-color"><div class="catalog-color" style="background-color:var(--' . $row['color'] . ');"></td>';
                echo '<td>' . $row['prompt'] . '</td>';
                echo '</tr>';
            };

            }; */
            ?>
            </table>

        </main>

        <script src="script.js"></script>

            <?php include 'footer.php'; ?>

    </body>
</html>
<?php

mysqli_free_result($datalist_results);

?>