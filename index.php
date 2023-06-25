<?php

// Specify the file name or path
$file = 'table_data.txt';

// check if the file exists
if (file_exists($file)) {

    // Load the content from the file
    $content = file_get_contents($file);

    // enconding the file content
    $jsonData = json_encode($content);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta property="og:title" content="Calendar File" />
    <meta name="twitter:title" content="Calendar File" />
    <meta name="description" content="Mark your days and save" />
    <meta property="og:description" content="Mark your days and save" />
    <meta name="twitter:description" content="Mark your days and save" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar File</title>
    <link rel="stylesheet" type="text/css" href="index.css" />
</head>

<body>

    <body>

        <div class="container">



            <div class="table">

                <div id="message" class="message">
                </div>

                <div class="title">
                    <p>
                        June 2023
                    </p>
                </div>

                <table id="calendar" class="calendar">
                    <thead>
                        <tr>
                            <th>Sun</th>
                            <th>Mon</th>
                            <th>Tue</th>
                            <th>Wed</th>
                            <th>Thu</th>
                            <th>Fri</th>
                            <th>Sat</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

            <div class="buttons">
                <button id="save" onclick="save()">Save</button>
                <button onclick="selectAll()">Select All</button>
                <button onclick="deselectAll()">Deselect All</button>
            </div>

        </div>


        <script>
            function createCalendar(year, month) {
                const tableBody = document.querySelector('#calendar tbody');
                const daysInMonth = new Date(year, month + 1, 0).getDate();
                const firstDayOfWeek = new Date(year, month, 1).getDay();
                let dateCount = 1;
                for (let i = 0; i < 6; i++) {
                    const row = document.createElement('tr');
                    for (let j = 0; j < 7; j++) {
                        if (i === 0 && j < firstDayOfWeek) {
                            const cell = document.createElement('td');
                            row.appendChild(cell);
                        } else if (dateCount <= daysInMonth) {
                            const cell = document.createElement('td');
                            cell.textContent = dateCount;
                            //mark or unmark when a day is selected
                            cell.addEventListener('click', function() {
                                let cellStatus = cell.classList.toggle('marked');
                                if (cellStatus) {
                                    markedDates.push(cell.innerHTML);
                                } else {
                                    const index = markedDates.indexOf(cell.innerHTML);
                                    const x = markedDates.splice(index, 1);
                                    console.log(x);
                                }
                            });
                            row.appendChild(cell);
                            dateCount++;
                        }
                    }
                    tableBody.appendChild(row);
                }
            }
            //create the calendar and fill on the screen
            createCalendar(2023, 5);

            //array which contains the marked days
            let markedDates = [];

            //select all days at once
            function selectAll() {
                let message = document.getElementById('message');
                message.innerHTML = '';
                const tableDays = document.querySelectorAll('#calendar tbody tr td');
                tableDays.forEach((day) => {
                    if (day.innerHTML.trim() !== '') {
                        day.classList.add('marked');
                        markedDates.push(day.innerHTML);
                    }
                });
            }

            //deselect all days at once
            function deselectAll() {
                let message = document.getElementById('message');
                message.innerHTML = '';
                const tableDays = document.querySelectorAll('#calendar tbody tr td');
                tableDays.forEach((day) => {
                    if (day.innerHTML.trim() !== '') {
                        day.classList.remove('marked');
                        markedDates = [];
                    }
                });
            }

            //save data into the file
            function save() {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'save.php');
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    let message = document.getElementById('message');
                    if (xhr.status === 200) {
                        message.innerHTML = '<p class="success">Data saved successfully</p>';
                    } else {
                        message.innerHTML = '<p class="failed">Failed to save data</p>';
                    }
                };
                xhr.send('markedDates=' + encodeURIComponent(markedDates));
            }

            // When the page is ready, mark the days saved
            document.addEventListener("DOMContentLoaded", function() {
                var tableData = <?php echo $jsonData; ?>;
                var days = tableData.split(",");
                markedDates = days;
                var tdElements = document.getElementsByTagName('td');
                for (var i = 0; i < tdElements.length; i++) {
                    var td = tdElements[i];
                    if (td.innerHTML.trim() !== '') {
                        var tdContent = td.textContent.trim();
                        if (markedDates.includes(tdContent)) {
                            td.classList.add('marked');
                        }
                    }
                }
            });
        </script>
    </body>
</body>

</html>