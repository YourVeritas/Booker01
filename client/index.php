<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Calendar</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"  
        integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="calendar">
                <table>
                    <tr><th>пн</th><th>вт</th><th>ср</th><th>чт</th><th>пт</th><th>сб</th><th>вс</th></tr><tr>
                    <tr v-for="row in items">
                        <th v-for="day in row">
                            {{day.data}}
                        </th>
                    </tr>
                </table>
        </div>
        <script src="https://unpkg.com/vue/dist/vue.js"></script>
        <script src="vue.js"></script>
    </body>
</html>
