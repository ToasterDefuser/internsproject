<?php

namespace App\Repositories;

use App\Models\Buyer;

class BuyerRepository
{
    public function getAllUniqueNames()
    {
        return Buyer::select('Name')->distinct()->get();
    }
}

