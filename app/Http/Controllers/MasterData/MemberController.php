<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\MasterData\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    protected $nextNumber = 0;

    protected function getNumber()
    {
        if($this->nextNumber > 0) {
            return str_pad($this->nextNumber++, 5, "0", STR_PAD_LEFT);
        }

        $this->nextNumber = DB::table('members')->count() + 1;

        return $this->getNumber();
    }

    public function index(Request $request)
    {
        return view('pages.masterdata.member_index', [
            'data' => Member::with('user')->paginate(
                $request->input('limit'),
                ['*'],
                'page',
                (int) $request->input('page', 1),
            )
        ]);
    }

    public function edit(Request $request, Member $member)
    {
        if($request->isMethod('put')) {
            $data = $request->except([
                '_token',
                '_method',
                'member_code',
            ]);

            $member->fill($data);
            $member->save();
        }

        return view('pages.masterdata.member_edit', [
            'data' => $member,
        ]);
    }

    public function delete(Request $request, Member $member)
    {
        if($member->delete()) {
            return redirect()->back();
        }
    }
}
