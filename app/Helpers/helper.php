<?php
    use App\Models\Category;

    
    function getCategories(){
        return Category::orderBy('sort', 'ASC')
        ->with(['sub_category' => function ($query) {
            $query->orderBy('sort', 'ASC'); 
        }])
        ->where('show_on_home', 'Yes')
        ->where('status', 1)
        ->get();
    }


?>

















