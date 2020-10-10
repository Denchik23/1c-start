<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

    <title>Тестовое задание: Парсер ОГРН</title>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="text-center pb-3">Тестовое задание: Парсер ОГРН</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="/" method="post">
                        <input type="hidden" name="start_pars" value="1">
                        <button type="submit" class="btn btn-primary">Запустить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mt-3">
            {if count($alert) > 0}
                <div class="alert alert-dark" role="alert">
                    <ul>
                        {foreach from=$alert item=foo}
                            <li>{$foo}</li>
                        {/foreach}
                    </ul>
                </div>
            {/if}

        </div>
    </div>
</div>

</body>
</html>