<?php
    use App\Models\Category;


    function getCategories(){
        return Category::orderBy('sort','ASC')->get();
    }
    



?>

















