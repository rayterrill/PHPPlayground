# PHPPlayground
## SQL to Google Chart JSON
We have some data we'd like to surface from SQL Server using Google Charts, which requires a specific JSON formatting. I'm sure someone had written a library for this before, but I couldn't find it, and could only find halfway-done examples.

I went ahead and built a PHP class to allow you to convert a SQL statement into JSON for use in Google Charts. I didn't map every datatype, but I mapped the most common ones.

What's included here:

* SQLtoGoogleChartJSON.php - The class that does all the work
* UseTheClass.php - An example of using an instance of the class to get some data from a SQL Server
* displayChart.html - The Google Chart code, calling the UseTheClass.php PHP page asynchronously, and building out a pie chart

Hope you find this userful!
