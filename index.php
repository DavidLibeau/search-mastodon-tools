<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Search Mastodon tools</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="theme-color" content="#2b90d9">
        <link rel="stylesheet" href="style.css"/>
        <link rel="stylesheet" href="https://dav.li/forkawesome/1.0.11/css/fork-awesome.min.css"/>
    </head>
    <body>
        <header>
            <h1><i class="fa fa-mastodon-alt" aria-hidden="true"></i></h1>
            <form id="searchbar"><input type="text" placeholder="Search Mastodon tools…"/><button><i class="fa fa-chevron-right" aria-hidden="true"></i></button></form>
            <!--p><i class="fa fa-search" aria-hidden="true"></i> We found 4 results!</p-->
        </header>
        <main>
            <p id="noResults" style="display:none;">No results found. :(</p>
            <?php
            $database=file_get_contents("database.xml", FILE_USE_INCLUDE_PATH);
            if($database!=false){
                $xml=simplexml_load_string($database);
                $xmlArray = array();
                foreach ($xml->tool as $tool_tmp) $xmlArray[] = $tool_tmp;
                $xmlArray = array_reverse($xmlArray);
                foreach ($xmlArray as $tool) {?>
                <a class="tool" data-id="<?php echo($tool->id); ?>" href="<?php echo($tool->link); ?>" target="_blank" rel="noopener noreferrer">
                    <h2><i class="fa <?php echo($tool->icon); ?>" aria-hidden="true"></i><?php echo($tool->name); ?></h2>
                    <p><?php echo($tool->description); ?></p>
                    <span class="link" title="<?php echo($tool->link); ?>"><?php echo($tool->link); ?></span>
                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                </a>
                <?php
                }
            } ?>
            <a id="addYours" href="https://github.com/DavidLibeau/search-mastodon-tools" target="_blank" rel="noopener noreferrer" title="Add your tool to the database">
                <i class="fa fa-plus" aria-hidden="true"></i>
            </a>
        </main>
        <footer>
            <p>This website tries to respect your privacy. It is hosted by OVH. The third party "Dav.Li" domain is hosted on the same server of this website. The software is <a href="https://github.com/DavidLibeau/search-mastodon-tools" target="_blank" rel="noopener noreferrer">free and open source</a>.<br/>Mastodon is a community driven free software not affiliated with this website. <a href="https://joinmastodon.org" target="_blank" rel="noopener noreferrer">Join Mastodon</a>.</p>
        </footer>
        <script src="//dav.li/jquery/2.1.4.js"></script>
        <script>
            function searchObject(q) {
                if (q == "") {
                    $(".tool").show();
                } else {
                    $(".tool").hide();
                    console.log(q);
                    parser = new DOMParser();
                    //var databaseXml = parser.parseFromString(database, "text/xml");
                    //console.log(databaseXml);
                    var it = database.evaluate("//tool[contains(translate(name, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'),translate('" + q + "', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz')) or contains(translate(description, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'),translate('" + q + "', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz')) or contains(translate(link, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'),translate('" + q + "', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz')) or contains(translate(keywords//keyword, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'),translate('" + q + "', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'))]/id/text()", database, null, XPathResult.ANY_TYPE, null);
                    console.log(it);
                    var results = [];
                    var node;
                    while (node = it.iterateNext()) {
                        $(".tool[data-id=\"" + node.textContent + "\"]").show();
                        results.push(node.textContent);
                    }
                    if (results.length == 0) {
                        $("#noResults").show();
                    } else {
                        $("#noResults").hide();
                    }
                    return results;
                }
            }
            var database;
            $(function() {
                $.ajax({
                    url: "database.xml"
                }).done(function(data) {
                    console.log(data);
                    database = data;
                }).error(function(data) {
                    console.log("Error while loading database.");
                    console.log(data);
                });
                $("#searchbar").on("submit keypress", function(evt) {
                    if (evt.type == "submit") {
                        evt.preventDefault();
                    }
                    console.log(searchObject($(this).children("input").val()));
                });
            });
        </script>
    </body>
</html>
