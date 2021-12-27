<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventBudgetEstimation extends Model
{
    protected $guarded = ['id'];

    public function budgetType(){
        return $this->hasMany(BudgetType::class);
    }
}
