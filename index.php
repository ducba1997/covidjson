<?php
$source =  isset($_POST['jsonsource']) ? $_POST['jsonsource'] : '';
$source_array = json_decode($source);
$end = [];
$except_person = [];
if (is_array($source_array)) {
    foreach ($source_array as $key => $value) {
        if (in_array("covid", $value)) {
            array_push($value, 0);
            array_push($end, $value);
        }
    }
}
foreach ($end as $key => $value) {
    add_to_array($source_array, $except_person, $value[0], $end[$key], 0);
}
function add_to_array($arr_compare, &$arr_except, $person, &$arr_add, $order)
{
    if (in_array($person, $arr_except))
        return;
    foreach ($arr_compare as $item) {
        if (in_array('covid', $item))
            continue;
        if ($person === $item[0])
            continue;
        if (in_array($person, $item)) {
            array_push($arr_add, [$item[0], $person, $order + 1]);
        }
    }
    $arr_except[] = $person;
    if (is_array(($arr_add)))
        foreach ($arr_add as $key => $value) {
            add_to_array($arr_compare, $arr_except, $value[0], $arr_add[$key], $order + 1);
        }
}
?>
<!DOCTYPE html>
<html lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Title Page</title>

    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #json {
            outline: 1px solid #ccc;
            padding: 5px;
            margin: 5px;
        }

        .string {
            color: green;
        }

        .number {
            color: darkorange;
        }

        .boolean {
            color: blue;
        }

        .null {
            color: magenta;
        }

        .key {
            color: red;
        }
    </style>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.3/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
</head>

<body>
    <form action="" method="POST">
        <div class="row">
            <div class="col-sm-5">
                <div class="form-group">
                    <label for="input-id" class="col-sm-2">Json nguồn</label>

                    <textarea name="jsonsource" id="" cols="60" rows="30"><?php if (isset($_POST['jsonsource'])) echo $_POST['jsonsource']  ?></textarea>
                </div>
            </div>
            <div class="col-sm-2" style="margin-top: 20%;">
                <button type="submit" class="btn btn-success" style="margin-left: 50%">Xử lý</button>
            </div>
            <div class="col-sm-5">
                <label for="input-id" class="col-sm-2">Json đích</label>


                <pre id="json"></pre>
            </div>
        </div>
    </form>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Bootstrap JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script>
        function output(inp) {
            $('#json').html(inp);
        }

        function syntaxHighlight(json) {
            json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
            return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function(match) {
                var cls = 'number';
                if (/^"/.test(match)) {
                    if (/:$/.test(match)) {
                        cls = 'key';
                    } else {
                        cls = 'string';
                    }
                } else if (/true|false/.test(match)) {
                    cls = 'boolean';
                } else if (/null/.test(match)) {
                    cls = 'null';
                }
                return '<span class="' + cls + '">' + match + '</span>';
            });
        }

        var obj = <?php if (isset($end)) echo json_encode($end) ?>;
        var str = JSON.stringify(obj, undefined, 4);

        output(str);
        output(syntaxHighlight(str));
    </script>
</body>

</html>