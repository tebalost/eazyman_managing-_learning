<?php


namespace App\Providers;
use App\subjectModel;
use Illuminate\support\serviceProvider;

class dropdownMenu extends  serviceProvider

{
    public function boot()
    {
        view()->composer( '*', function ($view) {
            $view->with('$subject', subjectModel::all());
        }
        );
    }

}

