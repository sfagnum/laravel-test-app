<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Test App</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="{{ asset('/css/lib.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">

    <script src="{{ asset('/js/lib.min.js')}}"></script>
    <script src="{{ asset('/js/app.min.js')}}"></script>
</head>
<body ng-app="testApp" ng-cloak="">
    <div class="container" ng-controller="MainController">
        <h1>Курсы валют</h1>
        <table class="table">
            <thead>
            <th>Code</th>
            <th>Rate</th>
            </thead>
            <tbody>
            <tr ng-repeat="currency in currencies track by $index" ng-class="{'success' : highlight && currency.changed}" class="highlight">
                <td>{% currency.isoCode %}</td>
                <td>{% currency.rate %}</td>
            </tr>
            </tbody>
        </table>
    </div>
</body>
</html>