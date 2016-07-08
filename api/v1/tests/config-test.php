<?php namespace API\Test;
require_once dirname(dirname(__FILE__)) . "/services/APIConfig";

echo "<h3>API Config</h3>";
echo '<pre><code>';
print_r(\API\APIConfig::get());
echo "</code></pre>";

echo "<h3>SERVER</h3>";
echo '<pre><code>';
print_r($_SERVER);
echo "</code></pre>";
