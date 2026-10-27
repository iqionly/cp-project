<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Http\Request;

class SearchMemberFilter
{
    public function __construct(
        private ?Request $request,
        private ?array $searchColumns = ['member' => 'first_name'],
    ) {
        //
    }

    public function __invoke(EloquentBuilder $query): void
    {
        if (! $this->request->isMethod('post')) {
            return;
        }

        foreach ($this->searchColumns as $key => $col) {
            $value = $this->request->get('filter_member_'.$col);
            if (! $value) {
                continue;
            }

            $query->whereHas($key, function (EloquentBuilder $builder) use ($value, $col) {
                $builder->where($col, 'like', '%'.$value.'%');
            });
        }
    }
}
