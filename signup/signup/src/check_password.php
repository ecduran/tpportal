<?php 
declare(strict_types = 1);

$q = $_GET['q'];

if (strlen($q) < 6)
{
	echo "<p class='font-weight-bold'>Short password. Minimum of 6 characters</p>";
}
else 
{
	echo "<p class='font-weight-bold text-success'>Excellent!</p>";
}