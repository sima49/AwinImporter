<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <script src="/awinProductImport/js/pace.min.js" type="text/javascript"></script>
        <link rel="stylesheet" href="/awinProductImport/js/pace-theme-flash.tmpl.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
        <script>
            //pace options
            paceOptions = {
                ajax: true,
                document: true, 
            };
        </script>
        <title>Import Products</title>
    </head>
    <body>
        <div class="jumbotron">
            <div class="container">
                <h1>Product Import</h1><small>From awin datafeed</small>
                <form>
                    <label  for="inputHelpBlock"><strong>File Feed</strong></label>
                    <input class="form-control" id="inputHelpBlock" type="text" id="url" placeholder="This is the awin cid's" aria-describedby="helpBlock"/>
                    <span id="helpBlock" class="help-block">
                        All you need to put in this block is the highlighted from the url the script will handle the rest.<br />
                        https://productdata.awin.com/datafeed/download/apikey/YOUR API KEY/language/en/cid/
                        <span class="bg-info">
                            61,62,72,73,71,74,75,76,77,78,63,80,82,64,83,84,85,65,86,87,88,90,89,91,67,94,33,54,53,57,58,603,60,66,128,130,133,212,207,209,210,211,68,69,213,217,220,221,70,224,225,226,228,229
                        </span>
                        /columns/aw_deep_link,product_name,aw_product_id,merchant_product_id,merchant_image_url,description,merchant_category,search_price,merchant_name,merchant_id,category_name,category_id,aw_image_url,currency,store_price,delivery_cost,merchant_deep_link,language,last_updated,display_price,data_feed_id/format/csv/delimiter/%2C/compression/zip/adultcontent/1/
                    </span>
                    <label><strong>Category</strong></label>
                    <input class="form-control" type="text" id="cat" placeholder="Your category"/>
                    <button id="buttonS" type="submit">Import</button>
                </form>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col">

                    <div class="Data">
                    </div>
                </div>
            </div>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         
        </div>
        <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
        <script>
            $(document).ready(function () {
                $("#buttonS").click(function (e) {
                    e.preventDefault();
                    var bla = $("#url").val();
                    var C = $('#cat').val();
                    $.ajax({
                        type: 'GET',
                        url: '/awinProductImport/ajax/Import.php',
                        dataType: "html",
                        data: {"i": bla,
                            "c": C},
                        success: function (Data) {
                            //alert(Data);
                            $('.Data').html(Data);

                        },
                        error: function () {
                            alert('Error loading ');
                        }
                    });
                });
            });
        </script>
    </body>
</html>
