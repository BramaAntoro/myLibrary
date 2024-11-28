    <?php

    class Controller
    {
        protected static function view($page, $data = [])
        {
            $data;
            require $page;
        }
    }
