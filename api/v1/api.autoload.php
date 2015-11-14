<?php

class APIAutoLoad {
    
    public function run() {
        // Get current directory.
        $dir = dirname(__FILE__);
        
        // Recursively get list of directory files
        $files = $this->getDirectoryFilesRecursively($dir);
        
        // Require list of files
        $this->requireFiles($files);
    }
    
    private function printFiles($files) {
        echo '<pre><code><ol>';
        foreach ($files AS $file) {
            echo '<li>' . $file . '</li>';
        }
        echo '</ol></code></pre>';
    }
    
    private function requireFiles($files) {
        foreach ($files AS $file) {
            require_once $file;
        }
    }

    /*
     * Get Directory Files Recursively
     * 
     * Recursively get a list of all php files in a directory and its sub 
     * directories except for the vendor folder because those are our
     * composer dependencies.
     * 
     * Notice that the $resutls array is passed by reference.
     */
    public function getDirectoryFilesRecursively($dir, &$results = array()){
        // The first time this function is called a new array is created
        
        // Get all files and folders in this directory
        $files = scandir($dir);

        foreach($files as $key => $fileName){
            // Returns canonicalized absolute pathname
            $path = realpath($dir.DIRECTORY_SEPARATOR.$fileName);
            
            if (is_file($path) && preg_match('/\.php$/', $fileName)) {
                // If this is a a valid PHP file save it in the results
                $results[] = $path;
            } else if(is_dir($path) && $fileName != "vendor" && $fileName != "." && $fileName != "..") {
                // If this is a directory recursivly get its contents
                $this->getDirectoryFilesRecursively($path, $results);
            }
        }

        // Return the list of results
        return $results;
        
    }
    
}

$loader = new APIAutoLoad();
$loader->run();