<?php

use GuzzleHttp\Psr7\Response;

require_once(__DIR__ . '/vendor/autoload.php');

$config = Finnhub\Configuration::getDefaultConfiguration()->setApiKey('token', 'c82gr7aad3ia12592efg');
$client = new Finnhub\Api\DefaultApi(
    new GuzzleHttp\Client(),
    $config
);
$search = $_GET["search"] ?? "";

$previewStocks = ["Z","AMZN","TSLA","FB","AAPL"];

$tableOfSearch = json_decode(file_get_contents("https://finnhub.io/api/v1/search?q={$search}&token=c82gr7aad3ia12592efg"));



function growColor(float $dp):string
{
    return $dp > 0 ? "rgb(52, 235, 73)" : "rgb(235, 64, 52)";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stocks</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <style>
    * {
        margin: auto;
        font-family: 'Roboto', sans-serif;
    }

    body {
        background-image: url("/images/page-bg.jpg");
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-position: center;
        background-size: cover;


        background-color: #2C2F40;

    }

    img {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        border: 4px solid rgba(110, 110, 110, .3);
    }


    div {
        display: flex;
        padding: 5px;

    }

    p {
        padding-left: 5px;
        padding-right: 5px;
        color: rgb(190, 190, 190);
        font-weight: bold;
    }

    table,
    th,
    td {
        border: 1px solid #131826;
        background-color: #585F73;
    }

    table {
        width: 60%;
    }

    .symbols,
    .price {
        vertical-align: text-top;
        color: #8D93A6;
    }


    .stock-info {
        display: table;
    }

    .Stock-bg {
        border-collapse: collapse;
        width: 100%;
        background-color: #131826;
        border-left: 1px solid rgba(10, 10, 10, .4);
        border-right: 1px solid rgba(10, 10, 10, .4);
        box-shadow: rgba(0, 0, 0, 0.35) 0px -50px 36px -28px inset;
    }

    .Top-div {

        box-shadow: rgba(0, 0, 0, 0.35) 0px -50px 36px -28px inset;
        padding: 0px;
    }

    .search-input,
    .search-button {
        border-radius: 6px;
        padding: 5px;
        border: 2px solid rgb(70, 70, 70);
        background-color: #131826;
        color: #585F73;
        transition: 0.2s;
    }

    .search-button {
        width: 100px;
    }

    .search-button:hover {
        background-color: rgba(50, 50, 50, .5);
        transition: 0.2s;
    }

    .description-text {
        color: rgb(20, 20, 20);
    }
    .search-result-text{
        font-size: 85%;
    }
    .search-div{
        padding: 0px;
        background-color: #2C2F40;
        box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;
    }
    </style>
</head>

<body>

    <div class="Top-div">
        <?php for ($i=0; $i < count($previewStocks); $i++): ?>

        <div class="Stock-bg">
            <img src="<?php echo $client->companyProfile2($previewStocks[$i])["logo"]; ?>">
            <div class="stock-info">
                <div>
                    <p class="symbols"><?php echo $client->companyProfile2($previewStocks[$i])["ticker"]; ?></p>
                    <p class="price"><?php echo round($client->quote($previewStocks[$i])["c"], 2); ?>$</p>
                </div>
                <p
                    style="color: <?php echo growColor(round($client->quote($previewStocks[$i])["dp"], 2)) ?>; font-size:110%;">
                    <?php echo round($client->quote($previewStocks[$i])["dp"], 2); ?>%</p>
            </div>
        </div>

        <?php endfor; ?>


    </div>

    <div class="search-div">
        <form method="GET" action="/">
            <input class="search-input" name="search" value="" placeholder="AAPL..." />
            <button class="search-button" type="submit">Search</button>
        </form>
        <div>
            <p class="search-result-text">Search results: <?php echo $tableOfSearch->count ?></p>
        </div>
    </div>
    <div class="search-results">
        <table>
            <thead>
                <th class="description-text">DESCRIPTION</th>
                <th class="description-text">SYMBOL</th>
            </thead>
            <tbody>
                <?php foreach ($tableOfSearch->result as $result): ?>

                <tr>
                    <td class="description-text"><?php echo $result->description; ?></td>
                    <td class="description-text"><?php echo $result->symbol; ?></td>
                </tr>

                <?php endforeach ?>
            </tbody>
        </table>
    </div>

</body>

</html>