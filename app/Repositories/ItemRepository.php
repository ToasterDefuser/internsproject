<?php

namespace App\Repositories;

use App\Models\Item;

class ItemRepository
{
    public function getAllUniqueEAN()
    {
        return Item::select('EAN')->distinct()->get();
    }
}

