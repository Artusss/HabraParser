<?php
$pages = array(); //массив страниц 
$pages_count = 0; //колличество страниц, которые надо спарсить

if(isset($_POST['searchPosts'])){ //при нажатии кнопки добавляет страницы в массив $pages
    $pages_count = $_POST['pages_count'];
    for ($i=1; $i <= $pages_count; $i++) {
        $site_content = file_get_contents("https://habr.com/ru/all/page{$i}");
        $site_content = str_replace("\n", '', $site_content);
        $content = array();
        //регулярное выражение, которое ищет ссылку на пост, дату и теги к статье
        $pattern = "{<header class=\"post__meta\">\s*.+?<span class=\"post__time\">(?<date>.+?)</span>\s*</header>\s*<h2 class=\"post__title\">(?<href>.+?)</h2>\s*<ul class=\"post__hubs inline-list\">(?<hubs>.+?)</ul>}im";
        preg_match_all($pattern, $site_content, $content);
        $pages[] = $content;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>HabraHunter</title>
    <link rel="stylesheet" href="main.css">
</head>
    <body>
        <form action="HabraHunter.php" method="POST">
            <label for="pages_count">Колличество страниц:</label>
            <input type="text" name="pages_count">
            <button type="submit" name="searchPosts">Найти</button>
        </form>
        <?php 
        for ($i=0; $i < $pages_count; $i++) { 
            for ($j=0; $j < count($pages[$i][0]); $j++) { ?> 
                <div class="parse_post">
                    <div>
                        <span class="href"><?=$pages[$i]['href'][$j]?></span>
                        <span class="date"><em><?=$pages[$i]['date'][$j]?></em></span>
                    </div>
                    <span class="hubs"><ul><?=$pages[$i]['hubs'][$j]?></ul></span>
                </div>
            <?php } 
        } ?>
    </body>
</html>
