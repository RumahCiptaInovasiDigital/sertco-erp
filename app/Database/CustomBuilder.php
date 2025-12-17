<?php

namespace App\Database;


use Illuminate\Database\Eloquent\Builder;

class CustomBuilder extends Builder
{

    public function whereLikeColumns(array $columns, $value): Builder
    {
        $tokens = explode(" ", $value);
        foreach ($columns as $column) {
            $this->where(function (Builder $query2) use ($column, $tokens) {
                foreach ($tokens as $token) {
                    if (empty($token)) {
                        continue;
                    }
                    $query2->orWhereLike($column, "%{$token}%");
                }
            });
        }
        return $this;
    }

}
